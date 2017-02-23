<?php

namespace Simplon\Form\View\Elements\Support\DropDownApi\Algolia;

use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiInterface;
use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiResponseInterface;

/**
 * @package Simplon\Form\View\Elements\Support\DropDownApi\Algolia
 */
class AlgoliaPlacesApi implements DropDownApiInterface
{
    /**
     * @return string
     */
    public function getMethod(): string
    {
        return 'POST';
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return 'https://places-dsn.algolia.net/1/places/query';
    }

    /**
     * @return null|string
     */
    public function getBeforeXHR(): ?string
    {
        return "xhr.setRequestHeader('X-Algolia-API-Key', algolia.key); xhr.setRequestHeader ('X-Algolia-Application-Id', algolia.id);";
    }

    /**
     * @return string
     */
    public function getBeforeSend(): string
    {
        return "settings.data = JSON.stringify({query: settings.urlData.query, type: \"city\", language: \"en\" });";
    }

    /**
     * @return DropDownApiResponseInterface
     */
    public function getOnResponse(): DropDownApiResponseInterface
    {
        return new AlgoliaPlacesApiResponse();
    }
}