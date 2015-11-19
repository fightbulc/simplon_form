<?php

namespace Simplon\Form\View\Elements\Support\Meta;

/**
 * Interface MetaInterface
 * @package Simplon\Form\View\Elements\Support\Meta
 */
interface MetaInterface
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @return array
     */
    public function getData();
}