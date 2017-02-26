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
     * @var string
     */
    private $apiKey;
    /**
     * @var string
     */
    private $appId;
    /**
     * @var string
     */
    private $language = 'en';
    /**
     * @var string
     */
    private $type = 'city';

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     *
     * @return AlgoliaPlacesApi
     */
    public function setApiKey(string $apiKey): AlgoliaPlacesApi
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * @param string $appId
     *
     * @return AlgoliaPlacesApi
     */
    public function setAppId(string $appId): AlgoliaPlacesApi
    {
        $this->appId = $appId;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     *
     * @return AlgoliaPlacesApi
     */
    public function setLanguage(string $language): AlgoliaPlacesApi
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return AlgoliaPlacesApi
     */
    public function setType(string $type): AlgoliaPlacesApi
    {
        $this->type = $type;

        return $this;
    }

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
        if ($this->apiKey && $this->appId)
        {
            return "xhr.setRequestHeader('X-Algolia-API-Key', " . $this->getApiKey() . "); xhr.setRequestHeader ('X-Algolia-Application-Id', " . $this->getAppId() . ");";
        }

        return null;
    }

    /**
     * @return string
     */
    public function getBeforeSend(): string
    {
        return "settings.data = JSON.stringify({query: settings.urlData.query, type: \"" . $this->getType() . "\", language: \"" . $this->getLanguage() . "\" });";
    }

    /**
     * @return DropDownApiResponseInterface
     */
    public function getOnResponse(): DropDownApiResponseInterface
    {
        return new AlgoliaPlacesApiResponse();
    }
}