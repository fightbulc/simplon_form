<?php

namespace Simplon\Form\Data\Filters;

/**
 * Class TrimLeftFilter
 * @package Simplon\Form\Data\Filters
 */
class TrimLeftFilter extends TrimFilter
{
    /**
     * @param string $value
     *
     * @return string
     */
    protected function convert($value)
    {
        return ltrim($value, $this->trimChars);
    }
}