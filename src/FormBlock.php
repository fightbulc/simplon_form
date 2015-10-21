<?php

namespace Simplon\Form;

use Simplon\Form\View\Elements\ElementInterface;

/**
 * Class FormBlock
 * @package Simplon\Form
 */
class FormBlock
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var ElementInterface[]
     */
    private $elements;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
     * @param ElementInterface $element
     *
     * @return FormBlock
     */
    public function addElement(ElementInterface $element)
    {
        $this->elements[$element->getField()->getId()] = $element;

        return $this;
    }
}