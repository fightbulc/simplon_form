<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\View\Element;
use Simplon\Form\View\RenderHelper;

/**
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
    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     *
     * @return static
     */
    public function setPlaceholder(string $placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @return array
     */
    public function getWidgetAttributes(): array
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
     * @return null|string
     */
    public function renderLabel(): ?string
    {
        if ($this->hasLabel())
        {
            /** @noinspection HtmlUnknownAttribute */
            $html = '<label {attrs}>' . $this->getLabel() . $this->renderDescription('&nbsp;') . '</label>';

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
    public function getWidgetHtml(): string
    {
        /** @noinspection HtmlUnknownAttribute */
        return '<input {attrs-field}>';
    }

    /**
     * @return string
     */
    public function renderWidget(): string
    {
        $attrs = [
            'attrs-field' => $this->getWidgetAttributes(),
        ];

        return RenderHelper::attributes($this->getWidgetHtml(), $attrs);
    }
}