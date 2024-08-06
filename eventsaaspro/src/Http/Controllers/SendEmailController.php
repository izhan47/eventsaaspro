<?php

namespace Eventsaaspro\Http\Controllers;

use App\Http\Controllers\Controller;
use Eventsaaspro\Models\Event;
use Eventsaaspro\Models\User;
use Eventsaaspro\Notifications\BookingNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SendEmailController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // language change
        $this->middleware('common');

        // this middleware work all functions but not work get_tickets because it is public function
        $this->middleware('auth');

        $this->event = new Event;

    }

    /**
     *  send email after successful bookings
     */
    public function send_email(Request $request)
    {
        $booking_data = session('booking_email_data');
        if (empty($booking_data)) {
            return response()
                ->json(['status' => 0]);
        }

        // get online event info
        $event = $this->event->get_event(null, $booking_data[key($booking_data)]['event_id']);
        $mail['is_online'] = false;
        $mail['additional_data'] = null;
        if (!empty($event->faq)) {
            $mail['additional_data'] = $event->faq;
        }
        if (!empty($event->online_location)) {
            $mail['is_online'] = true;
        }

        // ====================== Notification ======================
        //send notification after bookings
        $mail['mail_data'] = $booking_data;
        $mail['action_title'] = __('eventsaaspro-pro::em.download_tickets');
        $mail['action_url'] = route('eventsaaspro.mybookings_index');
        $mail['mail_subject'] = __('eventsaaspro-pro::em.booking_success');
        $mail['n_type'] = "bookings";

        $notification_ids = [1, $booking_data[key($booking_data)]['organiser_id'], $booking_data[key($booking_data)]['customer_id']];

        $users = User::whereIn('id', $notification_ids)->get();
        try {
            \Notification::locale(\App::getLocale())->send($users, new BookingNotification($mail));
        } catch (\Throwable $th) {
            \Log::error('Sending Mail Error: ' . $th->getMessage());
            return response()
                ->json(['error' => $th->getMessage()]);
        }
        // ====================== Notification ======================

        // delete booking_email_data data from session
        session()->forget(['booking_email_data']);

        return response()
            ->json(['status' => 1]);
    }

}
