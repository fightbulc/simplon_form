<?php

namespace Simplon\Form\View\Elements\Support\DropDownApi;

interface DropDownApiResponseDataInterface
{
    /**
     * @return string
     */
    public function renderResultObjectJsString(): string;

    /**
     * @return string
     */
    public function renderLabelJsString(): string;

    /**
     * @return string
     */
    public function renderNameJsString(): string;

    /**
     * @return string
     */
    public function renderRemoteIdJsString(): string;

    /**
     * @return null|string
     */
    public function renderMetaJsString(): ?string;
}