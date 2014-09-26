<?php

namespace Simplon\Form\Filters;

use Simplon\Form\Filters\Core\CoreFilter;

/**
 * CaseTitleFilter
 * @package Simplon\Form\Filters
 * @author Tino Ehrich (tino@bigpun.me)
 */
class CaseTitleFilter extends CoreFilter
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
            $elementValue = mb_convert_case($elementValue, MB_CASE_TITLE, 'UTF-8');
        }

        return $elementValue;
    }
}