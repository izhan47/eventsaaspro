<?php

namespace Eventsaaspro\Http\Controllers;

use App\Http\Controllers\Controller;
use Eventsaaspro\Models\Contact;
use Eventsaaspro\Notifications\MailNotification;
use Facades\Eventsaaspro\EventSaaSPro;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    public function __construct()
    {
        // language change
        $this->middleware('common');

        $this->contact = new Contact;
    }

    // get featured events
    public function index($view = 'eventsaaspro::contact', $extra = [])
    {
        return EventSaaSPro::view($view, compact('extra'));
    }

    // contact save
    public function store_contact(Request $request)
    {

        try {
            $request->validate([
                'name' => 'required|max:256',
                'email' => 'required|email',
                'title' => 'required|min:3|max:256',
                'message' => 'required|min:2|max:1000',
                'type' => 'required',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ];
        $contact = $this->contact->store_contact($data);

        if (empty($contact)) {
            return redirect()->back()->with('msg', __('eventsaaspro-pro::em.message_sent_fail'));
        }

        // ====================== Notification ======================
        //send notification after bookings
        $msg[] = __('eventsaaspro-pro::em.name') . ' - ' . $contact->name;
        $msg[] = __('eventsaaspro-pro::em.email') . ' - ' . $contact->email;
        $msg[] = __('eventsaaspro-pro::em.title') . ' - ' . $contact->title;
        $msg[] = __('eventsaaspro-pro::em.message') . ' - ' . $contact->message;
        $extra_lines = $msg;

        $mail['mail_subject'] = __('eventsaaspro-pro::em.message_sent');
        $mail['mail_message'] = __('eventsaaspro-pro::em.get_tickets');
        $mail['action_title'] = __('eventsaaspro-pro::em.view') . ' ' . __('eventsaaspro-pro::em.all') . ' ' . __('eventsaaspro-pro::em.events');
        $mail['action_url'] = route('eventsaaspro.events_index');
        $mail['n_type'] = "contact";

        // notification for
        $notification_ids = [
            'comedy@theriothtx.com', // admin
            $contact->email,
        ];
        if ($request->type === 'technical-support') {
            $notification_ids[] = 'izhan47@gmail.com';
        }
        // $users = User::whereIn('id', $notification_ids)->get();
        $users = $notification_ids;

        try {
            \Notification::route('mail', $users)->notify(new MailNotification($mail, $extra_lines));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        // ====================== Notification ======================

        return redirect()->back()->with('msg', __('eventsaaspro-pro::em.message_sent'));
    }
}
