<?php

namespace Simplon\Form\View\Elements\Support\Typeahead;

/**
 * Interface TypeaheadSourceInterface
 * @package Simplon\Form\View\Elements\Support\Typeahead
 */
interface TypeaheadSourceInterface
{
    /**
     * @return string
     */
    public function getIdAttr();

    /**
     * @return string
     */
    public function getLabelAttr();

    /**
     * @return string
     */
    public function renderSource();

    /**
     * @return array
     */
    public function getAssets();

    /**
     * @return string
     */
    public function getCode();
}