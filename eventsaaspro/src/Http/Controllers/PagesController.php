<?php

namespace Eventsaaspro\Http\Controllers;
use App\Http\Controllers\Controller;
use Facades\Eventsaaspro\EventSaaSPro;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Eventsaaspro\Models\Page;


class PagesController extends Controller
{

    public function __construct()
    {
        // language change
        $this->middleware('common');
    }

    // get featured events
    public function view($page = null, $view = 'eventsaaspro::pages', $extra = [])
    {
        $page   = Page::where(['slug' => $page])->firstOrFail();
        return EventSaaSPro::view($view, compact('page', 'extra'));
   }
}
