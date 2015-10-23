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
     * @var bool
     */
    private $autoWide = false;

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
    public function addAutoColumns(ElementInterface $element)
    {
        $this->autoWide = true;
        $this->addElement($element);

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addOneColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('one'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addTwoColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('two'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addThreeColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('three'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addFourColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('four'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addFiveColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('five'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addSixColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('six'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addSevenColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('seven'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addEightColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('eight'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addNineColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('nine'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addTenColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('ten'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addElevenColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('eleven'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addTwelveColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('twelve'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addThirteenColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('thirteen'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addFourteenColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('fourteen'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addFifthteenColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('fifthteen'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    public function addSixthteenColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('sixthteen'));

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
        $class = ['field'];
        $renderedElements = [];

        foreach ($this->getElements() as $element)
        {
            $html = $element->renderElement();
            $renderedElements[] = $html;
        }

        if ($this->getElementsCount() > 1)
        {
            $class = ['fields'];

            if ($this->isAutoWide())
            {
                $class[] = $this->getElementWide($this->getElementsCount());
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
     * @param ElementInterface $element
     *
     * @return FormRow
     * @throws FormException
     */
    protected function addElement(ElementInterface $element)
    {
        $this->elements[$element->getField()->getId()] = $element;

        return $this;
    }

    /**
     * @return boolean
     */
    private function isAutoWide()
    {
        return $this->autoWide;
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
            case 1:
                $wide = 'one';
                break;

            case 2:
                $wide = 'two';
                break;

            case 3:
                $wide = 'three';
                break;

            case 4:
                $wide = 'four';
                break;

            case 5:
                $wide = 'five';
                break;

            case 6:
                $wide = 'six';
                break;

            case 7:
                $wide = 'seven';
                break;

            case 8:
                $wide = 'eight';
                break;

            case 9:
                $wide = 'nine';
                break;

            case 10:
                $wide = 'ten';
                break;

            case 11:
                $wide = 'eleven';
                break;

            case 12:
                $wide = 'twelve';
                break;

            case 13:
                $wide = 'thirteen';
                break;

            case 14:
                $wide = 'fourteen';
                break;

            case 15:
                $wide = 'fiftheen';
                break;

            default:
                $wide = 'sixthteen';
        }

        return $wide;
    }
}