<?php

namespace Simplon\Form\Filters\Core;

/**
 * Interface CoreFilterInterface
 * @package Simplon\Form\Filters
 */
interface CoreFilterInterface
{
    /**
     * @param mixed $elementValue
     *
     * @return mixed
     */
    public function applyFilter($elementValue);
}
