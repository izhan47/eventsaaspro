<?php

namespace Eventsaaspro\Http\Controllers;

use App\Http\Controllers\Controller;
use Eventsaaspro\Jobs\SyncEventJob;
use Eventsaaspro\Models\Booking;
use Eventsaaspro\Models\Category;
use Eventsaaspro\Models\Commission;
use Eventsaaspro\Models\Country;
use Eventsaaspro\Models\Event;
use Eventsaaspro\Models\Ticket;
use Eventsaaspro\Models\User;
use Eventsaaspro\Models\Venue;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EventBriteController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->private_token = config('services.eventbrite.private_token');
        $this->api_key = config('services.eventbrite.api_key');
        $this->client_secret = config('services.eventbrite.client_secret');
        $this->public_token = config('services.eventbrite.public_token');

    }
    public function syncVenues($organization_id = '428099586040', $page = 1)
    {
        $client = new Client();

        try {
            $response = $client->request('GET', 'https://www.eventbriteapi.com/v3/organizations/' . $organization_id . '/venues/?page=' . $page, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->private_token,
                ],
            ]);

            $body = $response->getBody()->getContents();
            $body = json_decode($body);
            $organizer_id = User::where('role_id', 3)->pluck('id')->first();

            foreach ($body->venues as $key => $value) {
                $country_id = Country::where('country_code', $value->address->country)->pluck('id')->first();
                $check = Venue::where('address', $value->address->address_1)
                    ->where('city', $value->address->city)
                    ->where('country_id', $country_id)
                    ->where('state', $value->address->region)
                    ->where('zipcode', $value->address->postal_code)
                    ->first();
                if (!isset($check)) {
                    $slug = self::generateSlug($value->name);
                    $venue = Venue::create([
                        'eventbrite_id' => $value->id,
                        'title' => $value->name,
                        'description' => $value->name,
                        'slug' => $slug . '-' . $value->id,
                        'address' => $value->address->address_1,
                        'city' => $value->address->city,
                        'country_id' => $country_id,
                        'state' => $value->address->region,
                        'zipcode' => $value->address->postal_code,
                        'glat' => $value->address->latitude,
                        'glong' => $value->address->longitude,
                        'images' => "[]",
                        'organizer_id' => $organizer_id,
                        'status' => 1,

                    ]);
                }
            }
            if ($body->pagination->has_more_items) {
                $nextPage = $body->pagination->page_number + 1;
                self::syncVenues($organization_id, $nextPage);
            }
            return response()->json(['messages' => 'Venues Sync Successfully', 'status' => true]);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    public function syncCategories($page = 1)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', 'https://www.eventbriteapi.com/v3/categories/?page=' . $page, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->private_token,
                ],
            ]);
            $body = $response->getBody()->getContents();
            $body = json_decode($body);
            foreach ($body->categories as $key => $value) {
                $check = Category::where('eventbrite_id', $value->id)->first();
                if (!isset($check)) {
                    $slug = self::generateSlug($value->name);
                    Category::create([
                        'eventbrite_id' => $value->id,
                        'name' => $value->name,
                        'slug' => $slug,
                        'status' => 1,
                    ]);
                }
            }
            if ($body->pagination->has_more_items) {
                $nextPage = $body->pagination->page_number + 1;
                self::syncCategories($nextPage);
            }
            return response()->json(['messages' => 'Categories Sync Successfully', 'status' => true]);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function syncEvents($organization_id = '428099586040', $page = 1)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', 'https://www.eventbriteapi.com/v3/organizations/' . $organization_id . '/events?status=live&&page=' . $page, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->private_token,
                ],
            ]);
            $body = $response->getBody()->getContents();
            $body = json_decode($body);
            foreach ($body->events as $key => $value) {
                $check = Event::where('eventbrite_id', $value->id)->first();
                if (!isset($check)) {
                    SyncEventJob::dispatch($value, $this->private_token, $this->api_key, $this->client_secret, $this->public_token, $organization_id);
                }
            }
            if ($body->pagination->has_more_items) {
                $nextPage = $body->pagination->page_number + 1;
                self::syncEvents($organization_id, $nextPage);
            }
            return response()->json(['messages' => 'Event Sync Jobs Dispatched Successfully', 'status' => true]);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        if (!empty($request->api_url)) {
            $client = new Client();
            $response = $client->request('GET', $request->api_url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->private_token,
                ],
            ]);
            $body = $response->getBody()->getContents();
            $body = json_decode($body);
            if ($request->config['action'] == "event.published") {
                $check = Event::where('eventbrite_id', $body->id)->first();
                if (!isset($check)) {
                    SyncEventJob::dispatch($body, $this->private_token, $this->api_key, $this->client_secret, $this->public_token);
                }
            } elseif ($request->config['action'] == "order.placed") {
                $check = Booking::where('eventbrite_id', $body->id)->first();
                if (!$check) {
                    $customer_id = User::where('email', 'eventbrite@comicbook.com')->pluck('id')->first();
                    $organiser_id = User::where('role_id', 3)->pluck('id')->first();
                    $event = Event::where('eventbrite_id', $body->event_id)->first();
                    if (!empty($event)) {
                        $category = Category::where('id', $event->category_id)->first();
                        if (!empty($category)) {
                            $booking = Booking::create([
                                'eventbrite_id' => $body->id,
                                'created_at' => Carbon::parse($body->created),
                                'updated_at' => Carbon::parse($body->created),
                                'customer_id' => $customer_id,
                                'organiser_id' => $organiser_id,
                                'event_id' => $event->id,
                                'quantity' => 1,
                                'price' => $body->costs->base_price->major_value,
                                'net_price' => $body->costs->base_price->major_value,
                                'status' => 1,
                                'event_title' => $event->title,
                                'event_start_date' => $event->start_date,
                                'event_end_date' => $event->end_date,
                                'event_start_time' => $event->start_time,
                                'event_end_time' => $event->end_time,
                                'event_repetitive' => $event->repetitive,
                                'event_category' => $category->name,
                                'item_sku' => time() . rand(1, 988),
                                'order_number' => time() . rand(1, 988),
                                'customer_name' => $body->first_name . ' ' . $body->last_name,
                                'customer_email' => $body->email,
                            ]);
                            if (!empty($booking)) {
                                Commission::create([
                                    'organiser_id' => $organiser_id,
                                    'booking_id' => $booking->id,
                                    'created_at' => Carbon::parse($body->created),
                                    'updated_at' => Carbon::parse($body->created),
                                    'admin_commission' => "0.00",
                                    'customer_paid' => $body->costs->base_price->major_value,
                                    'organiser_earning' => $body->costs->base_price->major_value,
                                    'month_year' => Carbon::parse($booking->created_at)->format('m Y'),
                                    'event_id' => $event->id,
                                    'admin_tax' => "0.00",
                                ]);
                            }
                            $this->syncTicket($event->eventbrite_id, 1);
                        }
                    }
                }
            }
        }
        return $request;
    }

    public function syncTicket($event_id, $page = 1)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', 'https://www.eventbriteapi.com/v3/events/' . $event_id . '/ticket_classes/?page=' . $page, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->private_token,
                ],
            ]);
            $body = $response->getBody()->getContents();
            $body = json_decode($body);
            $id = Event::where('eventbrite_id', $event_id)->pluck('id')->first();
            foreach ($body->ticket_classes as $key => $value) {
                if ($value->category !== "add_on") {
                    $noOfPerson = $value->name;
                    preg_match('/\d+/', $noOfPerson, $matches);
                }
                $ticketData = [
                    'title' => (isset($value->category) && $value->category == "add_on") ? $value->name . " - addon" : $value->name,
                    'price' => (!isset($value->cost->major_value) || $value->cost->major_value == null) ? 0.00 : $value->cost->major_value,
                    'quantity' => $value->quantity_total - $value->quantity_sold,
                    'description' => $value->description,
                    'event_id' => $id,
                    'eventbrite_id' => $value->id,
                    'status' => 1,
                    'customer_limit' => ($value->maximum_quantity_per_order != 0) ? $value->maximum_quantity_per_order : null,
                    'no_of_persons' => !empty($matches) ? (int) $matches[0] : 1,
                ];

                $ticket = Ticket::where('eventbrite_id', $value->id)->first();

                if ($ticket) {
                    $ticket->update($ticketData);
                } else {
                    Ticket::create($ticketData);
                }
            }
            if ($body->pagination->has_more_items) {
                $nextPage = $body->pagination->page_number + 1;
                self::syncTicket($event_id, $nextPage);
            }
            return response()->json(['messages' => 'Categories Sync Successfully', 'status' => true]);
        } catch (Exception $e) {
            \Log::error('Sync Ticket Webhook Error');
            \Log::error($e->getMessage());
        }
    }
    public function generateSlug($slug)
    {
        $slug = strtolower($slug);
        $slug = str_replace(' ', '-', $slug);
        $slug = str_replace('"', '', $slug);
        $slug = str_replace(')', '', $slug);
        $slug = str_replace('(', '', $slug);
        $slug = str_replace(',', '', $slug);
        $slug = str_replace("'", '', $slug);
        return $slug;
    }

    public function test()
    {
        $events = Event::where('publish', 1)
            ->whereNotNull('eventbrite_id')
            ->select('title', 'eventbrite_id', 'start_date', 'end_date')
            ->without('bookings', 'comedian', 'user', 'coupon')
            ->get();
        return $events;
    }
}
