<?php

namespace Simplon\Form\View\Elements;

/**
 * Class RadioElement
 * @package Simplon\Form\View\Elements
 */
class RadioElement extends CheckboxElement
{
    /**
     * @return string
     */
    public function getElementType()
    {
        return self::TYPE_RADIO;
    }
}