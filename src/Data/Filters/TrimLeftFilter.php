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
    public function apply($value)
    {
        if (isset($this->trimChars))
        {
            return ltrim($value, $this->trimChars);
        }

        return ltrim($value);
    }
}