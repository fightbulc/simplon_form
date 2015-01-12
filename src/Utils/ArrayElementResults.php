<?php

namespace Simplon\Form\Utils;

use Simplon\Form\Elements\CoreElementInterface;

/**
 * ArrayContainerResults
 *
 * @author Tino Ehrich (tino@bigpun.me)
 */
class ArrayElementResults
{
    /**
     * @var CoreElementInterface
     */
    protected $element;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $value;

    /**
     * @return CoreElementInterface
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @param CoreElementInterface $element
     *
     * @return ArrayElementResults
     */
    public function setElement(CoreElementInterface $element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return ArrayElementResults
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return ArrayElementResults
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return ArrayElementResults
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}