<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\View\RenderHelper;

/**
 * Class InputText
 * @package Simplon\Form\View\Elements
 */
class InputText extends Element
{
    /**
     * @return array
     */
    public function getWidgetWrapperAttributes()
    {
        $base = [
            'class' => ['form-element-widget', 'input-text'],
        ];

        if (empty($this->attrs) === false)
        {
            foreach ($this->attrs as $name => $value)
            {
                if (isset($base[$name]))
                {
                    if (is_array($base[$name]))
                    {
                        $base[$name][] = $value;
                    }
                    else
                    {
                        $base[$name] = $base[$name] . ' ' . $value;
                    }
                }
                else
                {
                    $base[$name] = $value;
                }
            }
        }

        return $base;
    }

    /**
     * @return array
     */
    public function getWidgetFieldAttributes()
    {
        return [
            'type'  => 'text',
            'id'    => $this->renderElementId(),
            'name'  => $this->renderElementName(),
            'value' => $this->getField()->getValue(),
        ];
    }

    /**
     * @return string
     */
    public function renderLabel()
    {
        if ($this->hasLabel())
        {
            /** @noinspection HtmlUnknownAttribute */
            $html = '<div {attrs-wrapper}><label {attrs-for}>' . $this->getLabel() . '</label></div>';

            $attrs = [
                'attrs-wrapper' => [
                    'class' => ['form-element-label'],
                ],
                'attrs-for'     => [
                    'for' => $this->renderElementId(),
                ],
            ];

            return RenderHelper::attributes($html, $attrs);
        }

        return null;
    }

    /**
     * @return string
     */
    public function getWidgetHtml()
    {
        /** @noinspection HtmlUnknownAttribute */
        return '<div {attrs-wrapper}><input {attrs-field}></div>';
    }

    /**
     * @return string
     */
    public function renderWidget()
    {

        $attrs = [
            'attrs-wrapper' => $this->getWidgetWrapperAttributes(),
            'attrs-field'   => $this->getWidgetFieldAttributes(),
        ];

        return RenderHelper::attributes($this->getWidgetHtml(), $attrs);
    }
}