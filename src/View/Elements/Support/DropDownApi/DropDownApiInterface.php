<?php

namespace Simplon\Form\View\Elements\Support\DropDownApi;

/**
 * @package Simplon\Form\View\Elements\Support\DropDownApi
 */
interface DropDownApiInterface
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
     * @return null|string
     */
    public function getBeforeXHR(): ?string;

    /**
     * @return string
     */
    public function getBeforeSend(): string;

    /**
     * @return DropDownApiResponseInterface
     */
    public function getOnResponse(): DropDownApiResponseInterface;
}