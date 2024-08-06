<?php

namespace Eventsaaspro\Http\Controllers;

use App\Http\Controllers\Controller;
use Facades\Eventsaaspro\EventSaaSPro;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Eventsaaspro\Models\Venue;
use Eventsaaspro\Models\Category;
use Eventsaaspro\Models\Country;
use Eventsaaspro\Models\User;

use Eventsaaspro\Notifications\MailNotification;

class VenueController extends Controller
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

        $this->venue    = new Venue;

        $this->category = new Category;

        $this->country  = new Country;


        $this->organiser_id = null;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $view = 'eventsaaspro::venues.index', $extra = [])
    {
        $path = false;
        if(!empty(config('eventsaaspro.route.prefix')))
            $path = config('eventsaaspro.route.prefix');

        if($request->wantsJson()) {
            return $this->venues($request);
        }

        return EventSaaSPro::view($view, compact('path', 'extra'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Venue $venue)
    {

        return view('eventsaaspro::venues.show', compact('venue'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // filters for get_venues function
    protected function venue_filters(Request $request)
    {
        $request->validate([
            'category'          => 'max:256|String|nullable',
            'search'            => 'max:256|String|nullable',
            'start_date'        => 'date_format:Y-m-d|nullable',
            'end_date'          => 'date_format:Y-m-d|nullable',
            'price'             => 'max:256|String|nullable',
            'city'              => 'max:256|String|nullable',
            'state'             => 'max:256|String|nullable',
            'country'           => 'max:256|String|nullable',

        ]);

        $category_id            = null;
        $category               = urldecode($request->category);
        $search                 = $request->search;
        $price                  = $request->price;
        $city                   = $request->city == 'All' ? '' : $request->city;
        $state                  = $request->state == 'All' ? '' : $request->state;
        $country_id             = null;
        $country                = urldecode($request->country);

        // search category id
        if(!empty($category))
        {
            $categories  = $this->category->get_categories();

            foreach($categories as $key=> $value)
            {
                if($value['name'] == $category)
                    $category_id = $value['id'];
            }
        }

        // search country id
        if(!empty($country))
        {
            $countries = $this->country->get_countries();

            foreach($countries as $key=> $value)
            {
                if($value['country_name'] == $country)
                    $country_id = $value['id'];
            }
        }

        $filters                    = [];
        $filters['category_id']     = $category_id;
        $filters['search']          = $search;
        $filters['price']           = $price;
        $filters['start_date']      = $request->start_date;
        $filters['end_date']        = $request->end_date;
        $filters['city']            = $city;
        $filters['state']           = $state;
        $filters['country_id']      = $country_id;

        return $filters;
    }

    // EVENT LISTING APIs
    // get all venues
    protected function venues(Request $request)
    {
        $filters         = [];
        // call event fillter function
        $filters         = $this->venue_filters($request);

        $venues          = $this->venue->venues($filters);

        // set pagination values
        $venue_pagination = $venues->jsonSerialize();

        // get all countries
        $data = $this->country->get_countries_having_events($filters['country_id']);

        $countries = $data['countries'];
        $states    = $data['states'];
        $cities    = $data['cities'];

        return response([
            'venues'=> [
                'currency' => setting('regional.currency_default'),
                'data' => $venues,
                'total' => $venue_pagination['total'],
                'per_page' => $venue_pagination['per_page'],
                'current_page' => $venue_pagination['current_page'],
                'last_page' => $venue_pagination['last_page'],
                'from' => $venue_pagination['from'],
                'to' => $venue_pagination['to'],
                'countries' => $countries,
                'cities'    => $cities,
                'states'    => $states
            ],
        ], Response::HTTP_OK);
    }

    protected function is_admin(Request $request)
    {
        // if login user is Organiser then
        // organiser id = Auth::id();
        $this->organiser_id = Auth::id();

        // if admin is creating event
        // then user Auth::id() as $organiser_id
        // and organiser id will be the id selected from Vue dropdown
        if(Auth::user()->hasRole('admin'))
        {
            $request->validate([
                'organiser_id'       => 'required|numeric|min:1|regex:^[1-9][0-9]*$^',
            ]);

            $this->organiser_id = $request->organiser_id;
        }
    }

    public function request_quote(Request $request)
    {
        $request->validate([
            'name'           => 'required|max:256',
            'email'          => 'required|email|max:256',
            'contact_email'  => 'required|email|max:256',
            'phone'          => 'required|max:256',
            'guests'         => 'required|max:256',
            'message'        => 'required|min:2|max:1000',
        ]);

        // ====================== Notification ======================
        //send notification after bookings
        $msg[]                  = __('eventsaaspro-pro::em.name').' - '.$request->name;
        $msg[]                  = __('eventsaaspro-pro::em.email').' - '.$request->email;
        $msg[]                  = __('eventsaaspro-pro::em.phone').' - '.$request->phone;
        $msg[]                  = __('eventsaaspro-pro::em.guests').' - '.$request->guests;
        $msg[]                  = __('eventsaaspro-pro::em.message').' - '.$request->message;
        $extra_lines            = $msg;

        $mail['mail_subject']   = __('eventsaaspro-pro::em.message_sent');
        $mail['mail_message']   = __('eventsaaspro-pro::em.organizer_contact_back');
        $mail['action_title']   = __('eventsaaspro-pro::em.view').' '.__('eventsaaspro-pro::em.all').' '.__('eventsaaspro-pro::em.events');
        $mail['action_url']     = route('eventsaaspro.events_index');
        $mail['n_type']         = "contact";

        // notification for
        $notification_ids       = [
            User::whereId(1)->first(), // admin
            $request->email,
            $request->contact_email,
        ];

        $users = $notification_ids;
        try {
            \Notification::route('mail', $users)->notify(new MailNotification($mail, $extra_lines));
        } catch (\Throwable $th) {}
        // ====================== Notification ======================

        return redirect()->back()->with('msg', __('eventsaaspro-pro::em.message_sent'));
    }

      /**
     * search venues
     */
    public function search_venues($params = [])
    {
        $query =  Venue::query();

        if(!empty($params['search']))
        {
            $query->where(function($query) use($params) {

                $query->orWhere('title','LIKE',"%{$params['search']}%");

            });
        }

        $venues = $query->orderBy('title', 'ASC')->limit(10)->get();

        return response()->json(['status' => true, 'venues' => $venues]);
    }
}
