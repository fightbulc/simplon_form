<?php

namespace Simplon\Form\View\Elements\Support\DropDownApi\Semantic;

use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiJsInterface;
use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiResponseDataInterface;

/**
 * @package Simplon\Form\View\Elements\Support\DropDownApi\Semantic
 */
class SemanticApiJs implements DropDownApiJsInterface
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $method;
    /**
     * @var DropDownApiResponseDataInterface
     */
    private $onResponseHandler;

    /**
     * @param string $url
     * @param string $method
     */
    public function __construct(string $url, string $method = 'GET')
    {
        $this->url = $url;
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return null|string
     */
    public function renderBeforeXHRJsString(): ?string
    {
        return null;
    }

    /**
     * @return null|string
     */
    public function renderBeforeSendJsString(): ?string
    {
        return null;
    }

    /**
     * @return DropDownApiResponseDataInterface
     */
    public function getOnResponse(): DropDownApiResponseDataInterface
    {
        if (!$this->onResponseHandler)
        {
            $this->onResponseHandler = new SemanticApiResponseData();
        }

        return $this->onResponseHandler;
    }

    /**
     * @param DropDownApiResponseDataInterface $onResponseHandler
     *
     * @return SemanticApiJs
     */
    public function setOnResponseHandler(DropDownApiResponseDataInterface $onResponseHandler): SemanticApiJs
    {
        $this->onResponseHandler = $onResponseHandler;

        return $this;
    }
}