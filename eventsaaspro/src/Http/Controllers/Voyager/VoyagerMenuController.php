<?php

namespace Eventsaaspro\Http\Controllers\Voyager;
use Facades\Eventsaaspro\EventSaaSPro;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use TCG\Voyager\Facades\Voyager;

use TCG\Voyager\Http\Controllers\VoyagerMenuController as BaseVoyagerMenuController;

class VoyagerMenuController extends BaseVoyagerMenuController
{
    public function builder($id)
    {
        $menu = Voyager::model('Menu')->findOrFail($id);

        $this->authorize('edit', $menu);

        $isModelTranslatable = is_bread_translatable(Voyager::model('MenuItem'));

        $view = 'eventsaaspro::vendor.voyager.menus.builder';
        return EventSaaSPro::view($view, compact(
            'menu', 'isModelTranslatable'
        ));
    }
}
