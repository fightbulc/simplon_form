<?php

namespace Simplon\Form\View\Elements\Support\DropDownApi\Semantic;

use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiResponseDataInterface;

/**
 * @package Simplon\Form\View\Elements\Support\DropDownApi\Semantic
 */
class SemanticApiResponseData implements DropDownApiResponseDataInterface
{
    /**
     * @return string
     */
    public function renderResultObjectJsString(): string
    {
        return 'results';
    }

    /**
     * @return string
     */
    public function renderLabelJsString(): string
    {
        return 'item.name';
    }

    /**
     * @return string
     */
    public function renderNameJsString(): string
    {
        return 'item.name';
    }

    /**
     * @return string
     */
    public function renderRemoteIdJsString(): string
    {
        return 'item.value';
    }

    /**
     * @return null|string
     */
    public function renderMetaJsString(): ?string
    {
        return null;
    }
}