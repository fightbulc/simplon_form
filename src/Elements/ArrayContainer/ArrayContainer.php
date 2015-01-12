<?php

namespace Simplon\Form\Elements\ArrayContainer;

use Simplon\Form\Elements\CoreElement;
use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Interfaces\ArrayElementInterface;
use Simplon\Form\Renderer\Core\CoreElementRendererInterface;
use Simplon\Form\Utils\ArrayElementResults;

/**
 * ArrayElement
 * @package Simplon\Form\Elements\ArrayField
 * @author Tino Ehrich (tino@bigpun.me)
 */
class ArrayContainer extends CoreElement implements ArrayElementInterface
{
    /**
     * @var CoreElementInterface[]
     */
    protected $elements;

    /**
     * @var array
     */
    protected $loopElements;

    /**
     * @var array
     */
    protected $values;

    /**
     * @var CoreElementRendererInterface
     */
    protected $renderer;

    /**
     * @param CoreElementInterface $element
     *
     * @return ArrayContainer
     */
    public function addElement(CoreElementInterface $element)
    {
        $this->elements[] = $element;

        return $this;
    }

    /**
     * @param CoreElementInterface[] $elements
     *
     * @return ArrayContainer
     */
    public function setElements(array $elements)
    {
        $this->elements = $elements;

        return $this;
    }

    /**
     * @param array $values
     *
     * @return ArrayContainer
     */
    public function setValues(array $values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @param CoreElementRendererInterface $renderer
     *
     * @return ArrayContainer
     */
    public function setRenderer(CoreElementRendererInterface $renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @return string
     */
    public function renderElementHtml()
    {
        $html = [];

        foreach ($this->getValues() as $value)
        {
            $html[] = $this
                ->getRenderer()
                ->setElements($this->getLoopElements()['byVal'][$value])
                ->render();
        }

        return join('', $html);
    }

    /**
     * @param array $requestData
     *
     * @return ArrayContainer
     */
    public function setPostValueByRequestData(array $requestData)
    {
        foreach ($this->getElements() as $element)
        {
            if (isset($requestData[$element->getId()]))
            {
                foreach ($requestData[$element->getId()] as $key => $val)
                {
                    /** @var CoreElementInterface $elm */
                    $elm = $this->getLoopElements()['byId'][$element->getId()][$key];
                    $elm->setPostValue($val);
                }
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $isValid = true;

        foreach ($this->getValues() as $value)
        {
            /** @var CoreElementInterface $element */
            foreach ($this->getLoopElements()['byVal'][$value] as $element)
            {
                if ($element->isValid() === false)
                {
                    $isValid = false;
                }
            }
        }

        return $isValid;
    }

    /**
     * @return ArrayElementResults[]
     */
    public function getElementValues()
    {
        $arrayElementResultsMany = [];

        foreach ($this->getValues() as $value)
        {
            /** @var CoreElementInterface $element */
            foreach ($this->getLoopElements()['byVal'][$value] as $element)
            {
                $arrayElementResultsMany[] = (new ArrayElementResults())
                    ->setId($element->getId())
                    ->setKey($value)
                    ->setValue($element->getValue())
                    ->setElement($element);
            }
        }

        return $arrayElementResultsMany;
    }

    /**
     * @return CoreElementInterface[]
     */
    private function getElements()
    {
        return $this->elements;
    }

    /**
     * @return CoreElementRendererInterface
     */
    private function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @return array
     */
    private function getValues()
    {
        return $this->values;
    }

    /**
     * @return array
     */
    private function getLoopElements()
    {
        if ($this->loopElements === null)
        {
            foreach ($this->getValues() as $value)
            {
                foreach ($this->elements as $element)
                {
                    $elm = clone $element;

                    $elm->setName(
                        $elm->getName() . '[' . $value . ']'
                    );

                    $elm->setLabel(
                        str_replace('{{key}}', $value, $elm->getLabel())
                    );

                    $this->loopElements['byId'][$element->getId()][$value] = $elm;
                    $this->loopElements['byVal'][$value][$element->getId()] = $elm;
                }
            }
        }

        return $this->loopElements;
    }
}