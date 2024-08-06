<?php

namespace Eventsaaspro\Http\Controllers;
use App\Http\Controllers\Controller;
use Facades\Eventsaaspro\EventSaaSPro;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Eventsaaspro\Models\Event;
use Eventsaaspro\Models\User;
use Eventsaaspro\Models\Ticket;
use Eventsaaspro\Models\Banner;
use Eventsaaspro\Models\Tag;
use Eventsaaspro\Models\Category;
use Eventsaaspro\Models\Post;
use Eventsaaspro\Models\Country;
use Carbon\Carbon;


class WelcomeController extends Controller
{

    public function __construct()
    {

        // language change
        $this->middleware('common');

        $this->event            = new Event;
        $this->ticket           = new Ticket;
        $this->banner           = new Banner;
        $this->tag              = new Tag;
        $this->user             = new User;
        $this->category         = new Category;
        $this->post             = new Post;
        $this->country          = new Country;
    }


    // get featured events
    public function index($view = 'eventsaaspro::welcome', $extra = [])
    {

        $featured_events     = collect($this->get_featured_events());
        $top_selling_events  = collect($this->get_top_selling_events());
        $events_nearby       = collect($this->get_events_nearby());
        $events              = collect($this->get_events());
        $upcomming_events    = collect($this->get_upcomming_events());
        $banners             = $this->banner->get_banners();
        $categories          = $this->category->get_categories();
        $currency            = setting('regional.currency_default');
        $cities_events       = $this->event->get_cities_events();

        $countries           = $this->country->get_countries_having_events();
        $cities              = $countries['cities'];

        //get blog for welcome page
        $posts               = $this->post->index();

        return EventSaaSPro::view($view,
            compact(
                'featured_events', 'top_selling_events', 'events_nearby', 'events',
                'upcomming_events', 'banners',
                'categories', 'posts', 'currency', 'cities_events', 'cities',
                'extra'
            ));

    }

    // get featured events API
    protected function get_featured_events()
    {
        $featured_events  = $this->event->get_featured_events();

        $event_ids       = [];

        foreach($featured_events as $key => $value)
            $event_ids[] = $value->id;

        // pass events ids
        // tickets
        $events_tickets     = $this->ticket->get_events_tickets($event_ids);

        $events_data             = [];
        foreach($featured_events as $key => $value)
        {
            // online event - yes or no
            $value                  = $value->makeVisible('online_location');
            // check event is online or not
            $value->online_location    = (!empty($value->online_location)) ? 1 : 0;

            $events_data[$key]             = $value;

           foreach($events_tickets as $key1 => $value1)
            {
                // check relevant event_id with ticket id
                if($value->id == $value1['event_id'])
                {
                    $events_data[$key]->tickets[]       = $value1;
                }
            }
        }

        return  $events_data;

    }

    // get top selling events API
    protected function get_top_selling_events()
    {
        $top_selling_events  = $this->event->get_top_selling_events();

        $event_ids           = [];

        foreach($top_selling_events as $key => $value)
        {
            if($value->total_booking)
                $event_ids[] = $value->id;
        }

        // pass events ids
        // tickets
        $events_tickets     = $this->ticket->get_events_tickets($event_ids);

        $events_data             = [];
        foreach($top_selling_events as $key => $value)
        {
            if($value->total_booking)
            {
                // online event - yes or no
                $value                  = $value->makeVisible('online_location');
                // check event is online or not
                $value->online_location    = (!empty($value->online_location)) ? 1 : 0;

                $events_data[$key]     = $value;
            }

           foreach($events_tickets as $key1 => $value1)
            {
                // check relevant event_id with ticket id
                if($value->id == $value1['event_id'])
                {
                    $events_data[$key]->tickets[]       = $value1;
                }
            }
        }

        return  $events_data;

    }

    // get upcomming events
    protected function get_upcomming_events()
    {
        $upcomming_events  = $this->event->get_upcomming_events();

        $event_ids           = [];

        foreach($upcomming_events as $key => $value)
            $event_ids[] = $value->id;

        // pass events ids
        // tickets
        $events_tickets     = $this->ticket->get_events_tickets($event_ids);

        $events_data             = [];
        foreach($upcomming_events as $key => $value)
        {
            // online event - yes or no
            $value                  = $value->makeVisible('online_location');
            // check event is online or not
            $value->online_location    = (!empty($value->online_location)) ? 1 : 0;

            $events_data[$key]             = $value;

           foreach($events_tickets as $key1 => $value1)
            {
                // check relevant event_id with ticket id
                if($value->id == $value1['event_id'])
                {
                    $events_data[$key]->tickets[]       = $value1;
                }
            }
        }

        return  $events_data;

    }
    // get events
    protected function get_events()
    {
        $events  = $this->event->get_events();

        $event_ids           = [];

        foreach($events as $key => $value)
            $event_ids[] = $value->id;

        // pass events ids
        // tickets
        $events_tickets     = $this->ticket->get_events_tickets($event_ids);

        $events_data             = [];
        foreach($events as $key => $value)
        {
            // online event - yes or no
            $value                  = $value->makeVisible('online_location');
            // check event is online or not
            $value->online_location    = (!empty($value->online_location)) ? 1 : 0;

            $events_data[$key]             = $value;

           foreach($events_tickets as $key1 => $value1)
            {
                // check relevant event_id with ticket id
                if($value->id == $value1['event_id'])
                {
                    $events_data[$key]->tickets[]       = $value1;
                }
            }
        }

        return  $events_data;

    }
    // get events nearby
    protected function get_events_nearby()
    {
        $events  = $this->event->get_events_nearby();

        $event_ids           = [];

        foreach($events as $key => $value)
            $event_ids[] = $value->id;

        // pass events ids
        // tickets
        $events_tickets     = $this->ticket->get_events_tickets($event_ids);

        $events_data             = [];
        foreach($events as $key => $value)
        {
            // online event - yes or no
            $value                  = $value->makeVisible('online_location');
            // check event is online or not
            $value->online_location    = (!empty($value->online_location)) ? 1 : 0;

            $events_data[$key]             = $value;

           foreach($events_tickets as $key1 => $value1)
            {
                // check relevant event_id with ticket id
                if($value->id == $value1['event_id'])
                {
                    $events_data[$key]->tickets[]       = $value1;
                }
            }
        }

        return  $events_data;

    }
}
