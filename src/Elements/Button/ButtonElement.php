<?php

namespace Simplon\Form\Elements\Button;

use Simplon\Form\Elements\CoreElement;

/**
 * ButtonElement
 * @package Simplon\Form\Elements\Button
 * @author Tino Ehrich (tino@bigpun.me)
 */
class ButtonElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<button class=":class" :attrs>:label</button>';

    /**
     * @return array
     */
    protected function getFieldPlaceholders()
    {
        $coreFieldPlaceholders = parent::getFieldPlaceholders();

        // add options
        $coreFieldPlaceholders['class'] = 'btn';

        return $coreFieldPlaceholders;
    }
}