<?php

namespace Simplon\Form\View\Elements\Support\DropDownApi;

interface DropDownApiJsInterface
{
    /**
     * @return string
     */
    public function getMethod(): string;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @return array|null
     */
    public function getData(): ?array;

    /**
     * @return array|null
     */
    public function getSignals(): ?array;

    /**
     * @return null|string
     */
    public function renderBeforeXHRJsString(): ?string;

    /**
     * @return null|string
     */
    public function renderBeforeSendJsString(): ?string;

    /**
     * @return DropDownApiResponseDataInterface
     */
    public function getOnResponse(): DropDownApiResponseDataInterface;
}