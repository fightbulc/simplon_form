<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\Data\Field;
use Simplon\Form\View\RenderHelper;

/**
 * Class Element
 * @package Simplon\Form\View\Elements
 */
abstract class Element implements ElementInterface
{
    /**
     * @var array
     */
    protected $attrs = [];

    /**
     * @var Field
     */
    protected $field;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $wide;

    /**
     * @param Field $field
     */
    public function __construct(Field $field)
    {
        $this->field = $field;
    }

    /**
     * @return Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return string|null
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return bool
     */
    public function hasLabel()
    {
        return empty($this->label) === false;
    }

    /**
     * @param string $label
     *
     * @return static
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getWide()
    {
        return $this->wide;
    }

    /**
     * @param string $wide
     *
     * @return Element
     */
    public function setWide($wide)
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
    public function addAttribute($name, $value)
    {
        $this->attrs[$name] = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function renderErrors()
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

            return RenderHelper::attributes($html, ['attrs' => ['class' => ['ui', 'list', 'error']]]);
        }

        return null;
    }

    /**
     * @return string
     */
    public function renderElement()
    {
        /** @noinspection HtmlUnknownAttribute */
        $html = '<div {attrs-wrapper}>{label}{widget}{errors}</div>';

        $class = ['field'];

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
     * @return string
     */
    protected function renderElementId()
    {
        return 'form-' . $this->getField()->getId();
    }

    /**
     * @return string
     */
    protected function renderElementName()
    {
        return 'form[' . $this->getField()->getId() . ']';
    }
}