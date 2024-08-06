<?php

namespace Eventsaaspro\Jobs;

use Eventsaaspro\Models\Booking;
use Eventsaaspro\Models\Category;
use Eventsaaspro\Models\Commission;
use Eventsaaspro\Models\Country;
use Eventsaaspro\Models\Coupon;
use Eventsaaspro\Models\Event;
use Eventsaaspro\Models\Ticket;
use Eventsaaspro\Models\User;
use Eventsaaspro\Models\Venue;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SyncEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;
    protected $private_token;
    protected $api_key;
    protected $client_secret;
    protected $public_token;
    protected $organization_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event, $private_token, $api_key, $client_secret, $public_token, $organization_id)
    {
        $this->event = $event;
        $this->private_token = $private_token;
        $this->api_key = $api_key;
        $this->client_secret = $client_secret;
        $this->public_token = $public_token;
        $this->organization_id = $organization_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user_id = User::where('role_id', 3)->pluck('id')->first();
        $data['thumbnail'] = $data['poster'] = !empty($this->event->logo) ? $this->event->logo->original->url : null;
        $image = self::store_media($data);
        $venue = Venue::where('eventbrite_id', $this->event->venue_id)->first();
        if (!$venue) {
            $client = new Client();
            $response = $client->request('GET', "https://www.eventbriteapi.com/v3/venues/{$this->event->venue_id}/", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->private_token,
                ],
            ]);

            $eventVenue = $response->getBody()->getContents();
            $eventVenue = json_decode($eventVenue);
            $country_id = Country::where('country_code', $eventVenue->address->country)->pluck('id')->first();
            $venue = Venue::where('address', $eventVenue->address->address_1)
                ->where('city', $eventVenue->address->city)
                ->where('country_id', $country_id)
                ->where('state', $eventVenue->address->region)
                ->where('zipcode', $eventVenue->address->postal_code)
                ->first();
        }
        $slug = self::generateSlug($this->event->name->text);

        $start_date = Carbon::parse($this->event->start->local)->format('y-m-d');
        $start_time = Carbon::parse($this->event->start->local)->format('H:i:s');

        $end_date = Carbon::parse($this->event->end->local)->format('y-m-d');
        $end_time = Carbon::parse($this->event->end->local)->format('H:i:s');

        $category_id = Category::where('eventbrite_id', $this->event->category_id)->pluck('id')->first();
        $is_publishable['detail'] = 1;
        $is_publishable['location'] = 1;
        $is_publishable['timing'] = 1;
        $is_publishable['publish'] = 1;
        $is_publishable['media'] = 1;
        $is_publishable['tickets'] = 1;
        $event = Event::updateOrCreate(
            ['eventbrite_id' => $this->event->id],
            [
                'eventbrite_id' => $this->event->id,
                'title' => $this->event->name->text,
                'description' => $this->event->description->text,
                'excerpt' => $this->event->summary,
                'thumbnail' => $image['thumbnail'],
                'poster' => $image['poster'],
                'venue' => $venue->title,
                'address' => $venue->address,
                'city' => $venue->city,
                'state' => $venue->state,
                'zipcode' => $venue->zipcode,
                'country_id' => $venue->country_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'status' => 1,
                'category_id' => $category_id,
                'user_id' => $user_id,
                'slug' => $slug . '-' . $this->event->id,
                'price_type' => 1,
                'latitude' => $venue->glat,
                'longitude' => $venue->glong,
                'publish' => 0,
                'item_sku' => (string) time() . rand(1, 98),
                'is_publishable' => json_encode($is_publishable),
            ]
        );
        if ($event) {
            $event->venues()->sync($venue->id);
            $this->syncTicket($event->id, $this->event->id, 1);
            $this->syncCoupon($event->id, $this->event->id, 1);
            $this->syncMedia($this->event->id, 0, 1);
            $this->syncOrders($this->event->id, 1);
        }
    }
    public function store_media($data)
    {
        if (!empty($data['thumbnail'])) {
            $params = [
                'image' => $data['thumbnail'],
                'path' => 'events',
                'width' => 854,
                'height' => 480,
            ];
            $thumbnail = $this->upload_base64_image($params);
        }

        if (!empty($data['poster'])) {
            $params = [
                'image' => $data['poster'],
                'path' => 'events',
                'width' => 1280,
                'height' => 720,
            ];

            $poster = $this->upload_base64_image($params);
        }
        $params = [
            "thumbnail" => !empty($thumbnail) ? $thumbnail : null,
            "poster" => !empty($poster) ? $poster : null,
        ];
        return $params;
    }

    public function syncTicket($id, $event_id, $page = 1)
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
                self::syncTicket($id, $event_id, $nextPage);
            }
            return response()->json(['messages' => 'Categories Sync Successfully', 'status' => true]);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function syncCoupon($event_id, $eventbrite_id, $page = 1)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', 'https://www.eventbriteapi.com/v3/organizations/' . $this->organization_id . '/discounts/?scope=event&event_id=' . $eventbrite_id . '&page=' . $page, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->private_token,
                ],
            ]);
            $body = $response->getBody()->getContents();
            $body = json_decode($body);
            foreach ($body->discounts as $key => $value) {
                $tickets_id = [];
                $count = 0;
                if (isset($value->ticket_ids) && $value->ticket_ids != null) {
                    foreach ($value->ticket_ids as $key => $ticket_id) {
                        $ticket = Ticket::where('eventbrite_id', $ticket_id)->pluck('id')->first();
                        if (isset($ticket)) {
                            $tickets_id[$count] = $ticket;
                            $count++;
                        }
                    }
                } else {
                    $tickets_id[$count] = ['all'];
                }

                $couponData = [
                    'event_id' => $event_id,
                    'eventbrite_id' => $value->id,
                    'title' => $value->code,
                    'amount' => (isset($value->amount_off) && $value->amount_off != null) ? $value->amount_off : $value->percent_off,
                    'type' => (isset($value->amount_off) && $value->amount_off != null) ? 'fixed' : 'percent',
                    'start_date' => (isset($value->start_date) && $value->start_date != null) ? Carbon::parse($value->start_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
                    'expire_date' => (isset($value->end_date) && $value->end_date != null) ? Carbon::parse($value->end_date)->format('Y-m-d') : null,
                    'ticket' => json_encode($tickets_id),
                    'status' => 1,
                    'quantity' => $value->quantity_available,
                    'quantity_sold' => $value->quantity_sold,
                ];
                $coupon = Coupon::where('eventbrite_id', $value->id)->first();
                if ($coupon) {
                    $coupon->update($couponData);
                } else {
                    $coupon = Coupon::create($couponData);
                }
            }
            if ($body->pagination->has_more_items) {
                $nextPage = $body->pagination->page_number + 1;
                self::syncCoupon($event_id, $eventbrite_id, $nextPage);
            }
            return response()->json(['messages' => 'Categories Sync Successfully', 'status' => true]);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    public function syncMedia($event_id, $count = 0, $page = 1)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', 'https://www.eventbriteapi.com/v3/events/' . $event_id . '/structured_content/?purpose=listing&&page=' . $page, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->private_token,
                ],
            ]);
            $body = $response->getBody()->getContents();
            $body = json_decode($body);
            $video_link = null;
            $images = [];
            foreach ($body->modules as $key => $value) {
                if ($value->type == "video") {
                    $video_link = substr($value->data->video->url, strrpos($value->data->video->url, '/') + 1);
                } elseif ($value->type == "image") {
                    $images[$count] = $value->data->image->original->url;
                    $count++;
                }
            }
            if ($body->pagination->page_number < $body->pagination->page_count) {
                $nextPage = $body->pagination->page_number + 1;
                self::syncMedia($event_id, $count, $nextPage);
            }
            $content = self::storeVideoAndGallery($video_link, $images);
            if ($content) {
                $event = Event::where('eventbrite_id', $event_id)->first();
                $event->video_link = isset($content['video_link']) ? $content['video_link'] : null;
                $event->images = isset($content['images']) ? $content['images'] : null;
                $event->save();
            }
            return response()->json(['messages' => 'Categories Sync Successfully', 'status' => true]);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    public function storeVideoAndGallery($video_link, $imageUrls)
    {
        if (sizeof($imageUrls) > 0) {
            foreach ($imageUrls as $key => $url) {
                $params = [
                    'image' => $url,
                    'path' => 'events',
                    'width' => 854,
                    'height' => 480,
                ];
                $filename = $this->upload_base64_image($params);
                $images[$key] = $filename;
            }
        }

        $params = [
            "video_link" => $video_link,
        ];

        if (!empty($images)) {
            if (!empty($result->images)) {
                $exiting_images = json_decode($result->images, true);
                $images = array_merge($images, $exiting_images);
            }
            $params["images"] = json_encode(array_values($images));
        }
        return $params;

    }

    protected function upload_base64_image($params = [])
    {
        if (!empty($params['image'])) {
            $image = base64_encode(file_get_contents($params['image']));
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);

            $filename = time() . Str::random(10) . '.' . 'webp';
            $path = $params['path'] . '/' . Carbon::now()->format('FY') . '/';

            $image_resize = Image::make(base64_decode($image))->encode('webp', 90)->resize($params['width'], $params['height']);

            $s3Path = $path . $filename;
            Storage::disk('s3')->put($s3Path, $image_resize->stream()->__toString(), 'public');

            return Storage::disk('s3')->url($s3Path);
        }
    }
    public function syncOrders($event_id, $page = 1)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', 'https://www.eventbriteapi.com/v3/events/' . $event_id . '/orders?page=' . $page, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->private_token,
                ],
            ]);
            $body = $response->getBody()->getContents();
            $body = json_decode($body);
            $customer_id = User::where('email', 'eventbrite@comicbook.com')->pluck('id')->first();
            $organiser_id = User::where('role_id', 3)->pluck('id')->first();
            foreach ($body->orders as $key => $value) {
                $check = Booking::where('eventbrite_id', $value->id)->first();
                if (!$check) {
                    $event = Event::where('eventbrite_id', $event_id)->first();
                    $category = Category::where('id', $event->category_id)->first();
                    if (!empty($event) && !empty($category)) {
                        $booking = Booking::create([
                            'eventbrite_id' => $value->id,
                            'created_at' => Carbon::parse($value->created),
                            'updated_at' => Carbon::parse($value->created),
                            'customer_id' => $customer_id,
                            'organiser_id' => $organiser_id,
                            'event_id' => $event->id,
                            'quantity' => 1,
                            'price' => $value->costs->base_price->major_value,
                            'net_price' => $value->costs->base_price->major_value,
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
                            'customer_name' => $value->first_name . ' ' . $value->last_name,
                            'customer_email' => $value->email,
                        ]);
                        if (!empty($booking)) {
                            Commission::create([
                                'organiser_id' => $organiser_id,
                                'booking_id' => $booking->id,
                                'created_at' => Carbon::parse($value->created),
                                'updated_at' => Carbon::parse($value->created),
                                'admin_commission' => "0.00",
                                'customer_paid' => $value->costs->base_price->major_value,
                                'organiser_earning' => $value->costs->base_price->major_value,
                                'month_year' => Carbon::parse($booking->created_at)->format('m Y'),
                                'event_id' => $event->id,
                                'admin_tax' => "0.00",
                            ]);
                        }
                    }
                }
            }
            if ($body->pagination->has_more_items) {
                $nextPage = $body->pagination->page_number + 1;
                self::syncOrders($event_id, $nextPage);
            }
            return response()->json(['messages' => 'Event Sync Jobs Dispatched Successfully', 'status' => true]);
        } catch (Exception $e) {
            dd($e->getMessage());
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
}
