<?php

namespace Simplon\Form\Data\Filters;

/**
 * Class XssFilter
 * @package Simplon\Form\Data\Filters
 */
class XssFilter implements FilterInterface
{
    /**
     * @param string $value
     *
     * @return string
     */
    public function apply($value)
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
            $value = preg_replace('/^.*?' . $pattern . '.*?$/ui', $replace, $value);
        }

        return (string)$value;
    }
}