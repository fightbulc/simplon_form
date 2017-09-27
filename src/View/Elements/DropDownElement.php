<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\FormError;
use Simplon\Form\View\Element;
use Simplon\Form\View\FormView;
use Simplon\Form\View\RenderHelper;

/**
 * @package Simplon\Form\View\Elements
 */
class DropDownElement extends Element
{
    const DELIMITER = ',';

    /**
     * @var string
     */
    private $placeholder = 'Choose';
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
    public function getPlaceholder(): string
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
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * @return static
     */
    public function enableMultiple()
    {
        $this->multiple = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * @return static
     */
    public function enableSearchable()
    {
        $this->searchable = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowedAdditions(): bool
    {
        return $this->allowAdditions;
    }

    /**
     * @return static
     */
    public function enableAdditions()
    {
        $this->allowAdditions = true;

        return $this;
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
                    'for'                 => $this->renderElementId(),
                    'data-label-optional' => FormView::getOptionalLabel(),
                    'data-label-required' => FormView::getRequiredLabel(),
                ],
            ];

            return RenderHelper::attributes($html, $attrs);
        }

        return null;
    }

    /**
     * @param string $text
     * @param bool $isDefault
     *
     * @return string
     */
    public function renderTextWidget(string $text, bool $isDefault = true): string
    {
        return '<div class="' . ($isDefault ? 'default' : null) . ' text">' . $text . '</div>';
    }

    /**
     * @return string
     */
    public function getWidgetHtml(): string
    {
        /** @noinspection HtmlUnknownAttribute */
        return '<div {attrs-wrapper}><input {attrs-field}><i class="dropdown icon"></i>{placeholder}<div class="menu">{options}</div></div>';
    }

    /**
     * @return string
     * @throws FormError
     */
    public function renderWidget(): string
    {
        $attrs = [
            'attrs-wrapper' => [
                'class' => ['ui fluid selection dropdown'],
            ],
            'attrs-field'   => $this->getWidgetAttributes(),
        ];

        if ($this->isMultiple())
        {
            $attrs['attrs-wrapper']['class'][] = 'multiple';
        }

        if ($this->isSearchable() || $this->isAllowedAdditions())
        {
            $attrs['attrs-wrapper']['class'][] = 'search';
        }

        return RenderHelper::placeholders(
            RenderHelper::attributes($this->getWidgetHtml(), $attrs),
            [
                'placeholder' => !empty($this->getPlaceholder()) ? $this->renderTextWidget($this->getPlaceholder()) : null,
                'options'     => $this->renderOptions(),
            ]
        );
    }

    /**
     * @return array
     */
    public function getWidgetAttributes(): array
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
     * @return null|string
     */
    public function getCode(): ?string
    {
        $selector = '$(\'#' . $this->renderElementId() . '\').parent()';

        $options = [
            'allowAdditions' => $this->isAllowedAdditions(),
            'forceSelection' => false,
            'keys'           => [
                'delimiter' => 13,
            ],
        ];

        return $selector . '.dropdown(' . RenderHelper::jsonEncode($options) . ')';
    }

    /**
     * @return bool
     */
    protected function hasOptions(): bool
    {
        return $this->getField()->hasMeta('options');
    }

    /**
     * @return null|array
     */
    protected function getOptions(): ?array
    {
        return $this->getField()->getMeta('options');
    }

    /**
     * @return null|string
     * @throws FormError
     */
    private function renderOptions(): ?string
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

        return null;
    }
}