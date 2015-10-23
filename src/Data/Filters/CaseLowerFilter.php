<?php

namespace Simplon\Form\Data\Filters;

/**
 * Class CaseLowerFilter
 * @package Simplon\Form\Data\Filters
 */
class CaseLowerFilter implements FilterInterface
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
            $value = mb_convert_case($value, MB_CASE_LOWER, 'UTF-8');
        }

        return $value;
    }
}