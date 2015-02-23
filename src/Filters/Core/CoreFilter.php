<?php

namespace Simplon\Form\Filters\Core;

/**
 * CoreFilter
 * @package Simplon\Form\Filters
 * @author Tino Ehrich (tino@bigpun.me)
 */
class CoreFilter implements CoreFilterInterface
{
    /**
     * @param mixed $elementValue
     *
     * @return mixed
     */
    public function applyFilter($elementValue)
    {
        return $elementValue;
    }
}