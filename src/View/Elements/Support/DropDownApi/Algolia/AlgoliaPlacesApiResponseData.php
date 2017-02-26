<?php

namespace Simplon\Form\View\Elements\Support\DropDownApi\Algolia;

use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiResponseDataInterface;

/**
 * @package Simplon\Form\View\Elements\Support\DropDownApi\Algolia
 */
class AlgoliaPlacesApiResponseData implements DropDownApiResponseDataInterface
{
    /**
     * @return string
     */
    public function renderResultObjectJsString(): string
    {
        return 'hits';
    }

    /**
     * @return string
     */
    public function renderLabelJsString(): string
    {
        return "'<i class=\"flag '+ item.country_code + '\"></i>' + item.locale_names[0] + " . $this->renderAdministrativeString() . " + ', ' + item.country";
    }

    /**
     * @return string
     */
    public function renderNameJsString(): string
    {
        return "item.locale_names[0] + " . $this->renderAdministrativeString() . " + ', ' + item.country";
    }

    /**
     * @return string
     */
    public function renderRemoteIdJsString(): string
    {
        return 'item.objectID';
    }

    /**
     * @return string
     */
    public function renderMetaJsString(): string
    {
        return '{geo: item._geoloc}';
    }

    /**
     * @return string
     */
    private function renderAdministrativeString(): string
    {
        return "(item.administrative[0] !== undefined ? ', ' + item.administrative[0] : '' )";
    }
}