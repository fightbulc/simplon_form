<?php

namespace Simplon\Form\Elements\ArrayContainer;

use Simplon\Form\Elements\CoreElement;
use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Interfaces\ArrayElementInterface;
use Simplon\Form\Renderer\Core\CoreElementRendererInterface;

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
    protected $elementValues;

    /**
     * @var array
     */
    protected $arrayKeys;

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
        $this->elements[$element->getId()] = $element;

        return $this;
    }

    /**
     * @param CoreElementInterface[] $elements
     *
     * @return ArrayContainer
     */
    public function setElements(array $elements)
    {
        foreach ($elements as $elm)
        {
            $this->addElement($elm);
        }

        return $this;
    }

    /**
     * @param array $arrayKeys
     *
     * @return ArrayContainer
     */
    public function setArrayKeys(array $arrayKeys)
    {
        $this->arrayKeys = $arrayKeys;

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

        foreach ($this->getArrayKeys() as $key)
        {
            $html[] = $this
                ->getRenderer()
                ->setElements($this->getLoopElements()['byKey'][$key])
                ->render(['key' => $key]);
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
            $elementValues = [];

            if (isset($requestData[$element->getId()]))
            {
                foreach ($requestData[$element->getId()] as $key => $val)
                {
                    /** @var CoreElementInterface $elm */
                    $elm = $this->getLoopElements()['byId'][$element->getId()][$key];

                    // apply value to virtual field
                    $elm->setPostValue($val);

                    // cache values for applying it to the original field
                    $elementValues[$key] = $val;
                }

                // apply to original field
                $element->setPostValue($elementValues);
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

        foreach ($this->getArrayKeys() as $key)
        {
            /** @var CoreElementInterface $element */
            foreach ($this->getLoopElements()['byKey'][$key] as $element)
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
     * @param array $elementValues
     *
     * @return ArrayContainer
     */
    public function setElementValues(array $elementValues)
    {
        $this->elementValues = $elementValues;

        return $this;
    }

    /**
     * @param array $resultContainer
     *
     * @return array
     */
    public function getElementValues(array $resultContainer = [])
    {
        foreach ($this->getArrayKeys() as $key)
        {
            /** @var CoreElementInterface $elmVirtual */
            foreach ($this->getLoopElements()['byKey'][$key] as $elmVirtual)
            {
                $resultContainer[$elmVirtual->getId()][$key] = $elmVirtual->getValue();
            }
        }

        return $resultContainer;
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
    private function getArrayKeys()
    {
        return $this->arrayKeys;
    }

    /**
     * @return array
     */
    private function getLoopElements()
    {
        if ($this->loopElements === null)
        {
            foreach ($this->getArrayKeys() as $key)
            {
                foreach ($this->elements as $element)
                {
                    $elm = clone $element;

                    $elm->setName(
                        $elm->getName() . '[' . $key . ']'
                    );

                    $elm->setLabel(
                        str_replace('{{key}}', $key, $elm->getLabel())
                    );

                    if (isset($this->elementValues[$elm->getId()][$key]))
                    {
                        $elm->setPostValue($this->elementValues[$elm->getId()][$key]);
                    }

                    $this->loopElements['byId'][$element->getId()][$key] = $elm;
                    $this->loopElements['byKey'][$key][$element->getId()] = $elm;
                }
            }
        }

        return $this->loopElements;
    }
}