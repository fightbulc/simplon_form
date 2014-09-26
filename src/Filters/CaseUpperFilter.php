<?php

namespace Simplon\Form\Filters;

use Simplon\Form\Filters\Core\CoreFilter;

/**
 * CaseUpperFilter
 * @package Simplon\Form\Filters
 * @author Tino Ehrich (tino@bigpun.me)
 */
class CaseUpperFilter extends CoreFilter
{
    /**
     * @param string $elementValue
     *
     * @return string
     */
    public function applyFilter($elementValue)
    {
        if (function_exists('mb_convert_case'))
        {
            $elementValue = mb_convert_case($elementValue, MB_CASE_UPPER, 'UTF-8');
        }

        return $elementValue;
    }
}