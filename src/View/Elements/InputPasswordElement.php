<?php

namespace Simplon\Form\View\Elements;

/**
 * Class InputPasswordElement
 * @package Simplon\Form\View\Elements
 */
class InputPasswordElement extends InputTextElement
{
    /**
     * @return array
     */
    public function getWidgetAttributes()
    {
        $base = [
            'type' => 'password',
        ];

        return array_merge(parent::getWidgetAttributes(), $base);
    }
}