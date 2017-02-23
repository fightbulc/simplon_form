<?php

namespace Simplon\Form\View\Elements\Support\DropDownApi\Algolia;

use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiResponseInterface;

/**
 * @package Simplon\Form\View\Elements\Support\DropDownApi\Algolia
 */
class AlgoliaPlacesApiResponse implements DropDownApiResponseInterface
{
    /**
     * @return string
     */
    public function getResultItemsKey(): string
    {
        return 'hits';
    }

    /**
     * @return string
     */
    public function renderName(): string
    {
        return "<i class=\"flag '+ item.country_code + '\"></i>' + item.locale_names[0] + ', ' + item.country'";
    }

    /**
     * @return string
     */
    public function renderValue(): string
    {
        return "item.objectID";
    }
}