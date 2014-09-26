<?php

namespace Simplon\Form\Filters;

/**
 * TrimRightFilter
 * @package Simplon\Form\Filters
 * @author Tino Ehrich (tino@bigpun.me)
 */
class TrimRightFilter extends TrimFilter
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
            $elementValue = rtrim($elementValue, $this->trimChars);
        }
        else
        {
            $elementValue = rtrim($elementValue);
        }

        return $elementValue;
    }
}