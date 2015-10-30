<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\View\RenderHelper;

/**
 * Class DateCalendarElement
 * @package Simplon\Form\View\Elements
 */
class DateCalendarElement extends Element
{
    /**
     * @var string
     */
    private $placeholder;

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
     * @return TypeAheadElement
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
        return '<div {attrs-wrapper}><div {attrs-field-wrapper}><input {attrs-field}><i class="calendar icon"></i></div></div>';
    }

    /**
     * @return string
     */
    public function renderWidget()
    {
        $attrs = [
            'attrs-wrapper'       => [
                'class' => ['ui input'],
            ],
            'attrs-field-wrapper' => [
                'class' => ['ui right icon input'],
            ],
            'attrs-field'         => $this->getWidgetAttributes(),
        ];

        return RenderHelper::attributes($this->getWidgetHtml(), $attrs);
    }

    /**
     * @return array
     */
    public function getAssets()
    {
        return [
            'rome/2.1.x/rome.min.css',
            'rome/rome.custom.min.css',
            'momentjs/2.10.x/moment-with-locales.min.js',
            'rome/2.1.x/rome.standalone.min.js',
        ];
    }

    /**
     * @return null
     */
    public function getCode()
    {
        return 'rome(document.getElementById("' . $this->renderElementId() . '"), {time: false})';
    }
}