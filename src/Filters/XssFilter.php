<?php

namespace Simplon\Form\Filters;

use Simplon\Form\Filters\Core\CoreFilter;

/**
 * XssFilter
 * @package Simplon\Form\Filters
 * @author Tino Ehrich (tino@bigpun.me)
 */
class XssFilter extends CoreFilter
{
    /**
     * @param string $elementValue
     *
     * @return string
     */
    public function applyFilter($elementValue)
    {
        $filters = [
            '\<script.*?\>'               => '', // <script...> ...
            '\w+\(.*?\)'                  => '', // methodName("params") | methodName(params)
            '\&#x*\d+\w*;*'               => '', // &#100 | &#x100c | ...
            '(%\d+){2,}'                  => '', // %67%68...
            '0x\d+\.*'                    => '', // 0x42...
            'u\d+\w*\.*'                  => '', // \u003c...
            '(" | \')\s*on\w+\s*=("|\')*' => '', // " on...="
            '\w+\.\w+\('                  => '', // className.methodName(
            'document\.\w+\s*='           => '', // document.methodName=
        ];

        foreach ($filters as $pattern => $replace)
        {
            $elementValue = preg_replace('/^.*?' . $pattern . '.*?$/ui', $replace, $elementValue);
        }

        return (string)$elementValue;
    }
}