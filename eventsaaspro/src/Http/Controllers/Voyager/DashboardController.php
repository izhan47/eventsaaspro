<?php

namespace Eventsaaspro\Http\Controllers\Voyager;
use Facades\Eventsaaspro\EventSaaSPro;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Eventsaaspro\Models\Event;
use Eventsaaspro\Models\User;
use Eventsaaspro\Models\Booking;

use Eventsaaspro\Charts\EventChart;
use Eventsaaspro\Models\Notification;
use Yajra\Datatables\Datatables;

use Eventsaaspro\Services\Dashboard;

class DashboardController extends VoyagerBaseController
{
    public function __construct()
    {
        $this->middleware(['admin.user']);

        $this->event         = new Event;
        $this->booking       = new Booking;
        $this->notification  = new Notification;
        $this->user          = new User;
        $this->dashboard_service = new Dashboard;
    }

    /**
     *  index page
     */
    public function index(Request $request)
    {
        return $this->dashboard_service->index($request);
    }

    /**
     * sales report
     */

    public function sales_report(Request $request)
    {
        return $this->dashboard_service->sales_report($request);

    }


    /**
     * sale report of particular event
     */

    public function export_sales_report(Request $request)
    {
        return $this->dashboard_service->export_sales_report($request);
    }

    /**
     *  Event total by sales price
     */

    public function EventTotalBySalesPrice(Request $request)
    {
        $data = $this->dashboard_service->EventTotalBySalesPrice($request);
        echo json_encode($data);

    }

    /**
     *  get Event
     */

    public function getEvent(Request $request)
    {
        return $this->dashboard_service->getEvent($request);
    }



}
