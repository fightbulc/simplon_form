<?php

namespace Simplon\Form\View\Elements\Support\DropDownApi\Algolia;

use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiJsInterface;
use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiResponseDataInterface;
use Simplon\Form\View\RenderHelper;

/**
 * @see https://community.algolia.com/places/rest.html
 */
class AlgoliaPlacesApiJs implements DropDownApiJsInterface
{
    const TYPE_CITY = 'city';
    const TYPE_COUNTRY = 'country';
    const TYPE_ADDRESS = 'address';
    const TYPE_BUSSTOP = 'busStop';
    const TYPE_TRAINSTATION = 'trainStation';
    const TYPE_TOWNHALL = 'townhall';
    const TYPE_AIRPORT = 'airport';

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
     * @var array
     */
    private $countries = [];
    /**
     * @var string
     */
    private $type;
    /**
     * @var DropDownApiResponseDataInterface
     */
    private $onResponseHandler;

    /**
     * @param null|string $apiKey
     * @param null|string $appId
     */
    public function __construct(?string $apiKey = null, ?string $appId = null)
    {
        $this->apiKey = $apiKey;
        $this->appId = $appId;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
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
     * @return AlgoliaPlacesApiJs
     */
    public function setLanguage(string $language): AlgoliaPlacesApiJs
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return AlgoliaPlacesApiJs
     */
    public function setType(string $type): AlgoliaPlacesApiJs
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getCountries(): ?array
    {
        return $this->countries;
    }

    /**
     * @param string $countryCode
     *
     * @return AlgoliaPlacesApiJs
     */
    public function addCountries(string $countryCode)
    {
        $this->countries[] = $countryCode;

        return $this;
    }

    /**
     * @param array $countries
     *
     * @return AlgoliaPlacesApiJs
     */
    public function setCountries(array $countries)
    {
        $this->countries = $countries;

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
     * @return array|null
     */
    public function getData(): ?array
    {
        return [
            'language'  => $this->getLanguage(),
            'type'      => $this->getType(),
            'countries' => RenderHelper::jsonEncode($this->getCountries()),
        ];
    }

    /**
     * @return array|null
     */
    public function getSignals(): ?array
    {
        return null;
    }

    /**
     * @return null|string
     */
    public function renderBeforeXHRJsString(): ?string
    {
        if ($this->apiKey && $this->appId)
        {
            return 'xhr.setRequestHeader("X-Algolia-API-Key", "' . $this->getApiKey() . '"); xhr.setRequestHeader ("X-Algolia-Application-Id", "' . $this->getAppId() . '")';
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function renderBeforeSendJsString(): ?string
    {
        return 'var data = ' . RenderHelper::jsonEncode($this->getData()) . '; data.query = settings.urlData.query.replace(/str\./, "straße"); settings.data = JSON.stringify(data)';
    }

    /**
     * @return DropDownApiResponseDataInterface
     */
    public function getOnResponse(): DropDownApiResponseDataInterface
    {
        if (!$this->onResponseHandler)
        {
            $this->onResponseHandler = new AlgoliaPlacesApiResponseData();
        }

        return $this->onResponseHandler;
    }

    /**
     * @param DropDownApiResponseDataInterface $onResponseHandler
     *
     * @return AlgoliaPlacesApiJs
     */
    public function setOnResponseHandler(DropDownApiResponseDataInterface $onResponseHandler): AlgoliaPlacesApiJs
    {
        $this->onResponseHandler = $onResponseHandler;

        return $this;
    }
}