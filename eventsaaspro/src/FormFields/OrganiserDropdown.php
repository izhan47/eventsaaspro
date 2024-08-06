<?php

namespace Eventsaaspro\FormFields;
use Facades\Eventsaaspro\EventSaaSPro;

use TCG\Voyager\FormFields\AbstractHandler;

use Eventsaaspro\Models\User;

class OrganiserDropdown extends AbstractHandler
{
    protected $codename = 'organiser';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        $this->user         = new User;
        // get organisers
        $organisers         = $this->user->get_organisers();

        $view = 'eventsaaspro::vendor.voyager.formfields.organiser';

        return EventSaaSPro::view($view, [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'organisers' => $organisers,

        ]);
    }
}
