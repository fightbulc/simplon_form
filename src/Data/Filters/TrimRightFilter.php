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
    public function applyFilter($value)
    {
        if (isset($this->trimChars))
        {
            return rtrim($value, $this->trimChars);
        }

        return rtrim($value);
    }
}