<?php

namespace Simplon\Form\Filters;

/**
 * TrimLeftFilter
 * @package Simplon\Form\Filters
 * @author Tino Ehrich (tino@bigpun.me)
 */
class TrimLeftFilter extends TrimFilter
{

    /**
     * @param string $elementValue
     *
     * @return string
     */
    public function applyFilter($elementValue)
    {
        if ($this->trimChars !== null)
        {
            return ltrim($elementValue, $this->trimChars);
        }

        return ltrim($elementValue);
    }
}