<?php

namespace Eventsaaspro\Http\Controllers;
use App\Http\Controllers\Controller;
use Facades\Eventsaaspro\EventSaaSPro;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


use Eventsaaspro\Models\Event;
use Eventsaaspro\Models\Ticket;
use Eventsaaspro\Models\Category;
use Eventsaaspro\Models\Country;
use Eventsaaspro\Models\Schedule;
use Eventsaaspro\Models\Tag;
use Eventsaaspro\Models\Tax;
use Eventsaaspro\Models\Booking;
use Eventsaaspro\Services\USAePay;


class CheckoutController extends Controller
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

        $this->event    = new Event;

        $this->ticket   = new Ticket;

        $this->category = new Category;

        $this->country  = new Country;

        $this->schedule = new Schedule;

        $this->tag  = new Tag;

        $this->tax      = new Tax;

        $this->booking      = new Booking;

        $this->organiser_id = null;

        $this->USAePay = new USAePay;
    }

    /* ==================  EVENT LISTING ===================== */

    /**
     * Show all events
     *
     * @return array
     */
    public function index($view = 'eventsaaspro::checkout.index', $extra = [])
    {
        // get prifex from eventsaaspro config
        $path = false;
        if(!empty(config('eventsaaspro.route.prefix')))
            $path = config('eventsaaspro.route.prefix');

        return EventSaaSPro::view($view, compact('path', 'extra'));
    }
}
