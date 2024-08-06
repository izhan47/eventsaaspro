<?php

namespace Eventsaaspro\FormFields;
use Facades\Eventsaaspro\EventSaaSPro;

use TCG\Voyager\FormFields\AbstractHandler;

use Eventsaaspro\Models\Country;

class CountryDropdown extends AbstractHandler
{
    protected $codename = 'country';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        $this->country         = new Country;
        // get countries
        $countries         = $this->country->get_countries(false);

        $view = 'eventsaaspro::vendor.voyager.formfields.country';

        return EventSaaSPro::view($view, [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
            'countries' => $countries,

        ]);
    }
}
