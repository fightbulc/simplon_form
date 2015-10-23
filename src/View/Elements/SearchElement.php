<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\View\RenderHelper;

/**
 * Class SearchElement
 * @package Simplon\Form\View\Elements
 */
class SearchElement extends Element
{
    /**
     * @var string
     */
    private $placeholder;

    /**
     * @var string
     */
    private $fieldValueId;

    /**
     * @var string
     */
    private $fieldValueLabel;

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     *
     * @return SearchElement
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
            'type'  => 'hidden',
            'id'    => $this->renderElementId(),
            'name'  => $this->renderElementName(),
            'value' => $this->getField()->getValue(),
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
        return '<div {attrs-wrapper}><div {attrs-field-wrapper}><input {attrs-field}><input {attrs-search-field}><i class="search icon"></i></div></div>';
    }

    /**
     * @return string
     */
    public function renderWidget()
    {
        $attrs = [
            'attrs-wrapper'       => [
                'class' => ['ui search fluid'],
            ],
            'attrs-field-wrapper' => [
                'class' => ['ui', 'right icon', 'input'],
            ],
            'attrs-field'         => $this->getWidgetAttributes(),
            'attrs-search-field'  => [
                'class'       => ['prompt'],
                'value'       => $this->getFieldValueLabel(),
                'placeholder' => $this->getPlaceholder(),
            ],
        ];

        return RenderHelper::attributes($this->getWidgetHtml(), $attrs);
    }

    /**
     * @return null|string
     */
    private function getFieldValueLabel()
    {
        if ($this->fieldValueLabel === null)
        {
            $this->parseFieldValue();
        }

        return $this->fieldValueLabel;
    }

    private function parseFieldValue()
    {
        if ($this->getField()->getValue() && strpos($this->getField()->getValue(), ',') !== false)
        {
            $parts = explode(',', $this->getField()->getValue());
            $this->fieldValueId = array_shift($parts);
            $this->fieldValueLabel = join(',', $parts);
        }
    }
}