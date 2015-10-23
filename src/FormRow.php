<?php

namespace Simplon\Form;

use Simplon\Form\View\Elements\ElementInterface;
use Simplon\Form\View\RenderHelper;

/**
 * Class FormRow
 * @package Simplon\Form
 */
class FormRow
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var ElementInterface[]
     */
    private $elements;

    /**
     * @return string
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
     * @return FormRow
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addElement(ElementInterface $element)
    {
        if ($this->getElementsCount() === 4)
        {
            throw new FormException('Reached the max. amount of elements per row');
        }

        $this->elements[$element->getField()->getId()] = $element;

        return $this;
    }

    /**
     * @return ElementInterface[]
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param string $id
     *
     * @return ElementInterface
     * @throws FormException
     */
    public function getElement($id)
    {
        if (isset($this->elements[$id]))
        {
            return $this->elements[$id];
        }

        throw new FormException('Element with ID "' . $id . '" does not exist');
    }

    /**
     * @return string
     */
    public function render()
    {
        $elementHasWide = false;
        $renderedElements = [];

        foreach ($this->getElements() as $element)
        {
            $html = $element->renderElement();
            $renderedElements[] = $html;

            if ($elementHasWide === false && preg_match('/class=".*?wide.*?"/i', $html))
            {
                $elementHasWide = true;
            }
        }

        $elementCount = $this->getElementsCount();

        if ($elementCount === 1)
        {
            $class = ['field'];
        }
        else
        {
            $class = ['fields'];

            if ($elementHasWide === false)
            {
                $class[] = $this->getElementWide($elementCount);
                $class[] = 'wide';
            }
        }

        $html = RenderHelper::attributes(
            $this->getRowHtml(),
            [
                'attrs' => [
                    'class' => $class,
                ],
            ]
        );

        return RenderHelper::placeholders(
            $html,
            [
                'label'    => $this->renderLabel(),
                'elements' => join('', $renderedElements),
            ]
        );
    }

    /**
     * @return string
     */
    private function getRowHtml()
    {
        /** @noinspection HtmlUnknownAttribute */
        $html = '<div {attrs}>{elements}</div>';

        if ($this->hasLabel())
        {
            $html = '<div class="field">{label}' . $html . '</div>';
        }

        return $html;
    }

    /**
     * @return null|string
     */
    private function renderLabel()
    {
        if ($this->hasLabel())
        {
            return '<label>' . $this->getLabel() . '</label>';
        }

        return null;
    }

    /**
     * @return int
     */
    private function getElementsCount()
    {
        return count($this->getElements());
    }

    /**
     * @param int $count
     *
     * @return string
     */
    private function getElementWide($count)
    {
        switch ($count)
        {
            case 2:
                $wide = 'two';
                break;

            case 3:
                $wide = 'three';
                break;

            default:
                $wide = 'four';
        }

        return $wide;
    }
}