<?php

namespace Simplon\Form\View\Elements;

class InputHiddenElement extends InputTextElement
{
    /**
     * @return array
     */
    public function getWidgetAttributes(): array
    {
        $base = [
            'type' => 'hidden',
        ];

        return array_merge(parent::getWidgetAttributes(), $base);
    }

    /**
     * @return null|string
     */
    public function renderLabel(): ?string
    {
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
    public function renderElement(): string
    {
        return $this->renderWidget();
    }
}