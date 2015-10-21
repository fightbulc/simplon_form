<?php

namespace Simplon\Form\View\Elements;

/**
 * Class InputPassword
 * @package Simplon\Form\View\Elements
 */
class InputPassword extends InputText
{
    /**
     * @return array
     */
    public function getWidgetFieldAttributes()
    {
        $element = [
            'type' => 'password',
        ];

        return array_merge(parent::getWidgetFieldAttributes(), $element);
    }
}