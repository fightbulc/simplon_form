<?php

namespace Simplon\Form\Data\Filters;

/**
 * Class TrimRightFilter
 * @package Simplon\Form\Data\Filters
 */
class TrimRightFilter extends TrimFilter
{
    /**
     * @param string $value
     *
     * @return string
     */
    protected function convert($value)
    {
        return rtrim($value, $this->trimChars);
    }
}