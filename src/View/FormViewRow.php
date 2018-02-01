<?php

namespace Simplon\Form\View;

use Simplon\Form\FormError;

class FormViewRow
{
    /**
     * @var null|string
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
     * @return null|string
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
     * @return FormViewRow
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function autoColumns(ElementInterface $element): self
    {
        $this->autoWide = true;
        $this->addElement($element);

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function oneColumn(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('one'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function twoColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('two'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function threeColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('three'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function fourColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('four'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function fiveColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('five'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function sixColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('six'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function sevenColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('seven'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function eightColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('eight'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function nineColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('nine'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function tenColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('ten'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function elevenColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('eleven'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function twelveColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('twelve'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function thirteenColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('thirteen'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function fourteenColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('fourteen'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function fifthteenColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('fifthteen'));

        return $this;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormViewRow
     */
    public function sixthteenColumns(ElementInterface $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addElement($element->setWide('sixthteen'));

        return $this;
    }

    /**
     * @return ElementInterface[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @param string $id
     *
     * @return ElementInterface
     * @throws FormError
     */
    public function getElement(string $id): ElementInterface
    {
        if (isset($this->elements[$id]))
        {
            return $this->elements[$id];
        }

        throw new FormError('Element with ID "' . $id . '" does not exist');
    }

    /**
     * @return string
     */
    public function render(): string
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
     * @return FormViewRow
     */
    protected function addElement(ElementInterface $element): self
    {
        $this->elements[$element->getField()->getId()] = $element;

        return $this;
    }

    /**
     * @return boolean
     */
    private function isAutoWide(): bool
    {
        return $this->autoWide;
    }

    /**
     * @return string
     */
    private function getRowHtml(): string
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
    private function renderLabel(): ?string
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
    private function getElementsCount(): int
    {
        return count($this->getElements());
    }

    /**
     * @param int $count
     *
     * @return string
     */
    private function getElementWide($count): string
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