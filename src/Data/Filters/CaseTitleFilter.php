<?php

namespace Simplon\Form\Data\Filters;

/**
 * CaseTitleFilter
 * @package Simplon\Form\Filters
 * @author Tino Ehrich (tino@bigpun.me)
 */
class CaseTitleFilter implements FilterInterface
{
    /**
     * @param string $value
     *
     * @return string
     */
    public function apply($value)
    {
        if (function_exists('mb_convert_case'))
        {
            $value = mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
        }

        return $value;
    }
}