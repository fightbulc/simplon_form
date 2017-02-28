<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\Data\FormField;
use Simplon\Form\View\RenderHelper;

/**
 * @package Simplon\Form\View\Elements
 */
abstract class Element implements ElementInterface
{
    /**
     * @var string
     */
    protected $language = 'en';
    /**
     * @var FormField
     */
    protected $field;
    /**
     * @var array
     */
    protected $attrs = [];
    /**
     * @var null|string
     */
    protected $label;
    /**
     * @var null|string
     */
    protected $description;
    /**
     * @var null|string
     */
    protected $descriptionColor;
    /**
     * @var null|string
     */
    protected $wide;

    /**
     * @param FormField $field
     */
    public function __construct(FormField $field)
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     *
     * @return Element
     */
    public function setLanguage(string $language): Element
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return FormField
     */
    public function getField(): FormField
    {
        return $this->field;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @return bool
     */
    public function hasLabel(): bool
    {
        return empty($this->label) === false;
    }

    /**
     * @param string $label
     *
     * @return static
     */
    public function setLabel(string $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function hasDescription(): bool
    {
        return empty($this->description) === false;
    }

    /**
     * @param string $description
     * @param string $color
     *
     * @return static
     */
    public function setDescription(string $description, string $color = 'grey')
    {
        $this->description = $description;
        $this->descriptionColor = $color;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getWide(): ?string
    {
        return $this->wide;
    }

    /**
     * @param string $wide
     *
     * @return Element
     */
    public function setWide(string $wide): self
    {
        $this->wide = $wide;

        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return static
     */
    public function addAttribute(string $name, $value)
    {
        $this->attrs[$name] = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function renderErrors(): ?string
    {
        if ($this->getField()->hasErrors())
        {
            $errors = [];

            foreach ($this->getField()->getErrors() as $error)
            {
                $errors[] = '<div class="item">' . $error . '</div>';
            }

            /** @noinspection HtmlUnknownAttribute */
            $html = '<div {attrs}>' . join('', $errors) . '</div>';

            return RenderHelper::attributes($html, [
                'attrs' => [
                    'class' => ['ui', 'list', 'error'],
                ],
            ]);
        }

        return null;
    }

    /**
     * @param null|string $spacer
     *
     * @return null|string
     */
    public function renderDescription(?string $spacer = null): ?string
    {
        if ($this->hasDescription())
        {
            /** @noinspection HtmlUnknownAttribute */
            $html = $spacer . '<i {attrs}></i>';

            $attrs = [
                'attrs' => [
                    'class'        => ['field-description', $this->descriptionColor, 'info', 'circle', 'icon'],
                    'data-content' => $this->getDescription(),
                ],
            ];

            return RenderHelper::attributes($html, $attrs);
        }

        return null;
    }

    /**
     * @return string
     */
    public function renderElement(): string
    {
        /** @noinspection HtmlUnknownAttribute */
        $html = '<div {attrs-wrapper}>{label}{widget}{errors}</div>';

        $class = ['field'];

        if ($this->getField()->hasRules())
        {
            $class[] = 'required';
        }

        if ($this->getWide())
        {
            $class[] = $this->getWide();
            $class[] = 'wide';
        }

        $attrs = [
            'attrs-wrapper' => [
                'class' => $class,
            ],
        ];

        if ($this->getField()->hasErrors())
        {
            $attrs['attrs-wrapper']['class'][] = 'error';
        }

        return RenderHelper::placeholders(
            RenderHelper::attributes($html, $attrs),
            [
                'label'  => $this->renderLabel(),
                'widget' => $this->renderWidget(),
                'errors' => $this->renderErrors(),
            ]
        );
    }

    /**
     * @return array|null
     */
    public function getAssets(): ?array
    {
        return null;
    }

    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    protected function renderElementId(): string
    {
        return 'form-' . $this->getField()->getId();
    }

    /**
     * @return string
     */
    protected function renderElementName(): string
    {
        return 'form[' . $this->getField()->getId() . ']';
    }
}