<?php

namespace Eventsaaspro\Http\Controllers\Voyager;
use Facades\Eventsaaspro\EventSaaSPro;

use TCG\Voyager\Http\Controllers\VoyagerController as BaseVoyagerController;
use Auth;

class VoyagerController extends BaseVoyagerController
{
    public function index()
    {
        return EventSaaSPro::view('eventsaaspro::vendor.voyager.dashboard');
    }

    public function logout()
    {
        Auth::logout();

        return redirect(config('eventsaaspro.route.prefix').'/'.config('eventsaaspro.route.admin_prefix'));
    }
}
