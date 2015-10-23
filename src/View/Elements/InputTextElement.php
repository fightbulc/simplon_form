<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\View\RenderHelper;

/**
 * Class InputTextElement
 * @package Simplon\Form\View\Elements
 */
class InputTextElement extends Element
{
    /**
     * @var string
     */
    protected $placeholder;

    /**
     * @return string|null
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     *
     * @return static
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @return array
     */
    public function getWidgetAttributes()
    {
        $base = [
            'type'        => 'text',
            'id'          => $this->renderElementId(),
            'name'        => $this->renderElementName(),
            'value'       => $this->getField()->getValue(),
            'placeholder' => $this->getPlaceholder(),
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
     * @return string
     */
    public function renderLabel()
    {
        if ($this->hasLabel())
        {
            /** @noinspection HtmlUnknownAttribute */
            $html = '<label {attrs}>' . $this->getLabel() . '</label>';

            $attrs = [
                'attrs' => [
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
        return '<input {attrs-field}>';
    }

    /**
     * @return string
     */
    public function renderWidget()
    {
        $attrs = [
            'attrs-field' => $this->getWidgetAttributes(),
        ];

        return RenderHelper::attributes($this->getWidgetHtml(), $attrs);
    }
}