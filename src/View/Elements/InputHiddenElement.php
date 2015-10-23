<?php

namespace Simplon\Form\View\Elements;

/**
 * Class InputHiddenElement
 * @package Simplon\Form\View\Elements
 */
class InputHiddenElement extends InputTextElement
{
    /**
     * @return array
     */
    public function getWidgetAttributes()
    {
        $base = [
            'type' => 'hidden',
        ];

        return array_merge(parent::getWidgetAttributes(), $base);
    }

    /**
     * @return string
     */
    public function renderLabel()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getWidgetHtml()
    {
        /** @noinspection HtmlUnknownAttribute */
        return '<input {attrs-field}>';
    }

    /**
     * @return string
     */
    public function renderElement()
    {
        return $this->renderWidget();
    }
}