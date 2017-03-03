<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\View\RenderHelper;

/**
 * @package Simplon\Form\View\Elements
 */
class SubmitElement
{
    /**
     * @var string
     */
    private $label;
    /**
     * @var array
     */
    private $class = ['ui primary button'];

    /**
     * @param string $label
     * @param array $addToClass
     */
    public function __construct(string $label, array $addToClass = [])
    {
        $this->label = $label;
        $this->class = array_merge($this->class, $addToClass);
    }

    /**
     * @return string
     */
    public function renderElement(): string
    {
        /** @noinspection HtmlUnknownAttribute */
        $html = '<button {attrs}>{label}</button>';

        $attrs = [
            'attrs' => [
                'class' => $this->class,
                'type'  => 'submit',
            ],
        ];

        return RenderHelper::placeholders(
            RenderHelper::attributes($html, $attrs),
            ['label' => $this->label]
        );
    }
}