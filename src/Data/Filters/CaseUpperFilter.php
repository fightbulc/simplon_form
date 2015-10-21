<?php

namespace Simplon\Form\Data\Filters;

/**
 * Class CaseUpperFilter
 * @package Simplon\Form\Data\Filters
 */
class CaseUpperFilter implements FilterInterface
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
            $value = mb_convert_case($value, MB_CASE_UPPER, 'UTF-8');
        }

        return $value;
    }
}