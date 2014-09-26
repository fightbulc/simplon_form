<?php

namespace Simplon\Form\Filters;

use Simplon\Form\Filters\Core\CoreFilter;

/**
 * CaseLowerFilter
 * @package Simplon\Form\Filters
 * @author Tino Ehrich (tino@bigpun.me)
 */
class CaseLowerFilter extends CoreFilter
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
            $elementValue = mb_convert_case($elementValue, MB_CASE_LOWER, 'UTF-8');
        }

        return $elementValue;
    }
}