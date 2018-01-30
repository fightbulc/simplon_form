<?php

namespace Simplon\Form\View\Elements;

class RadioElement extends CheckboxElement
{
    /**
     * @return string
     */
    public function getElementType(): string
    {
        return self::TYPE_RADIO;
    }
}