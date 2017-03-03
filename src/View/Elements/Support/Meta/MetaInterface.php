<?php

namespace Simplon\Form\View\Elements\Support\Meta;

/**
 * @package Simplon\Form\View\Elements\Support\Meta
 */
interface MetaInterface
{
    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return array
     */
    public function getData(): array;
}