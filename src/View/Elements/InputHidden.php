<?php

namespace Simplon\Form\View\Elements;

/**
 * Class InputHidden
 * @package Simplon\Form\View\Elements
 */
class InputHidden extends InputText
{
    /**
     * @return array
     */
    public function getWidgetFieldAttributes()
    {
        $element = [
            'type' => 'hidden',
        ];

        return array_merge(parent::getWidgetFieldAttributes(), $element);
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