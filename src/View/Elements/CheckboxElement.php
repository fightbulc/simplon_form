<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\FormError;
use Simplon\Form\View\RenderHelper;

/**
 * @package Simplon\Form\View\Elements
 */
class CheckboxElement extends Element
{
    const FORMAT_INLINE = 'inline';
    const FORMAT_GROUPED = 'grouped';

    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_RADIO = 'radio';

    /**
     * @var string
     */
    private $format = self::FORMAT_INLINE;

    /**
     * @return string
     */
    public function getElementType(): string
    {
        return self::TYPE_CHECKBOX;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return CheckboxElement
     */
    public function setFormat(string $format): self
    {
        if (in_array($format, [self::FORMAT_GROUPED, self::FORMAT_INLINE]))
        {
            $this->format = $format;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getWidgetAttributes(): array
    {
        $base = [
            'id'   => $this->renderElementId(),
            'name' => $this->renderElementName(),
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
        return '<div {attrs-wrapper}>{options}</div>';
    }

    /**
     * @return string
     * @throws FormError
     */
    public function renderWidget(): string
    {
        $attrs = [
            'attrs-wrapper' => [
                'class' => ['ui fields', $this->getFormat()],
                'id'    => $this->renderElementId(),
            ],
        ];

        return RenderHelper::placeholders(
            RenderHelper::attributes($this->getWidgetHtml(), $attrs),
            ['options' => $this->renderOptions()]
        );
    }

    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        return '$(\'#' . $this->renderElementId() . ' .ui.checkbox\').checkbox()';
    }

    /**
     * @return bool
     */
    private function hasOptions(): bool
    {
        return $this->getField()->hasMeta('options');
    }

    /**
     * @return null|array
     */
    private function getOptions(): ?array
    {
        return $this->getField()->getMeta('options');
    }

    /**
     * @return string
     * @throws FormError
     */
    private function renderOptions(): string
    {
        if ($this->hasOptions())
        {
            $fieldValue = [];
            $renderedOptions = [];

            /** @noinspection HtmlUnknownAttribute */
            $html = '<div class="field"><div {attr-option-wrapper}>{field}{label}</div></div>';

            if ($this->getField()->getValue())
            {
                if (is_array($this->getField()->getValue()) === false)
                {
                    $fieldValue = explode(',', $this->getField()->getValue());
                }
                else
                {
                    $fieldValue = $this->getField()->getValue();
                }
            }

            foreach ($this->getOptions() as $option)
            {
                if (empty($option['label']))
                {
                    $option['label'] = $option['value'];
                }

                $checked = null;

                if (in_array($option['value'], $fieldValue))
                {
                    $checked = ' checked="checked"';
                }

                $elementName = $this->renderElementName();

                $attrsOptionWrapper = [
                    'attr-option-wrapper' => [
                        'class' => ['ui', 'checkbox'],
                    ],
                ];

                if ($this->getElementType() === self::TYPE_CHECKBOX)
                {
                    $elementName .= '[]';
                }

                if ($this->getElementType() === self::TYPE_RADIO)
                {
                    $attrsOptionWrapper['attr-option-wrapper']['class'][] = 'radio';
                }

                $renderedOptions[] = RenderHelper::placeholders(
                    RenderHelper::attributes($html, $attrsOptionWrapper),
                    [
                        'field' => '<input type="' . $this->getElementType() . '" name="' . $elementName . '" value="' . $option['value'] . '" class="hidden"' . $checked . '>',
                        'label' => '<label>' . $option['label'] . '</label>',
                    ]
                );
            }

            return join('', $renderedOptions);
        }

        throw new FormError('"' . $this->getField()->getId() . '" missing field options. Set via "FormField::addMeta(new OptionsMeta())"');
    }
}