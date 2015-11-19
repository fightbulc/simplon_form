<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\FormException;
use Simplon\Form\View\RenderHelper;

/**
 * Class DropDownElement
 * @package Simplon\Form\View\Elements
 */
class DropDownElement extends Element
{
    const DELIMITER = ',';

    /**
     * @var string
     */
    private $placeholder;

    /**
     * @var bool
     */
    private $multiple = false;

    /**
     * @var bool
     */
    private $searchable = false;

    /**
     * @var bool
     */
    private $allowAdditions = false;

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
     * @return static
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasMultiple()
    {
        return $this->multiple;
    }

    /**
     * @param boolean $multiple
     *
     * @return static
     */
    public function isMultiple($multiple)
    {
        $this->multiple = $multiple === true;

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasSearchable()
    {
        return $this->searchable;
    }

    /**
     * @param boolean $searchable
     *
     * @return static
     */
    public function isSearchable($searchable)
    {
        $this->searchable = $searchable === true;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getAllowAdditions()
    {
        return $this->allowAdditions;
    }

    /**
     * @param boolean $allowAdditions
     *
     * @return static
     */
    public function allowAdditions($allowAdditions)
    {
        $this->allowAdditions = $allowAdditions === true;

        return $this;
    }

    /**
     * @return array
     */
    public function getWidgetAttributes()
    {
        $fieldValue = $this->getField()->getValue();

        if (is_array($fieldValue))
        {
            $fieldValue = join(self::DELIMITER, $fieldValue);
        }

        $base = [
            'type'  => 'hidden',
            'id'    => $this->renderElementId(),
            'name'  => $this->renderElementName(),
            'value' => $fieldValue,
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
        return '<div {attrs-wrapper}><input {attrs-field}><i class="dropdown icon"></i>{placeholder}<div class="menu">{options}</div></div>';
    }

    /**
     * @return string
     */
    public function renderWidget()
    {
        $attrs = [
            'attrs-wrapper' => [
                'class' => ['ui fluid selection dropdown'],
            ],
            'attrs-field'   => $this->getWidgetAttributes(),
        ];

        if ($this->hasMultiple())
        {
            $attrs['attrs-wrapper']['class'][] = 'multiple';
        }

        if ($this->hasSearchable())
        {
            $attrs['attrs-wrapper']['class'][] = 'search';
        }

        return RenderHelper::placeholders(
            RenderHelper::attributes($this->getWidgetHtml(), $attrs),
            [
                'placeholder' => $this->getPlaceholder() ? '<div class="default text">' . $this->getPlaceholder() . '</div>' : false,
                'options'     => $this->renderOptions(),
            ]
        );
    }

    /**
     * @return string
     */
    public function getCode()
    {
        $allowAdditions = $this->getAllowAdditions() ? 'true' : 'false';

        return '$(\'#' . $this->renderElementId() . '\').parent().dropdown({ allowAdditions: ' . $allowAdditions . ', delimiter: "," })';
    }

    /**
     * @return bool
     */
    protected function hasOptions()
    {
        return $this->getField()->hasMeta('options');
    }

    /**
     * @return null|array
     */
    protected function getOptions()
    {
        return $this->getField()->getMeta('options');
    }

    /**
     * @return string
     * @throws FormException
     */
    private function renderOptions()
    {
        if ($this->hasOptions())
        {
            $renderedOptions = [];

            /** @noinspection HtmlUnknownAttribute */
            $html = '<div {attrs}>{label}</div>';

            foreach ($this->getOptions() as $option)
            {
                $attrs = [
                    'class'      => 'item',
                    'data-value' => $option['value'],
                ];

                if (empty($option['label']))
                {
                    $option['label'] = $option['value'];
                }

                $placeholders = [
                    'label' => $option['label'],
                ];

                $renderedOptions[] = RenderHelper::placeholders(
                    RenderHelper::attributes($html, ['attrs' => $attrs]),
                    $placeholders
                );
            }

            return join('', $renderedOptions);
        }

        if ($this->getAllowAdditions() === false)
        {
            throw new FormException('"' . $this->getField()->getId() . '" missing field options. Set via "Field::addMetas(new OptionsMeta())"');
        }
    }
}