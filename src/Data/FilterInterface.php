<?php

namespace Simplon\Form\Data;

/**
 * @package Simplon\Form\Data
 */
interface FilterInterface
{
    public function apply($value);
}