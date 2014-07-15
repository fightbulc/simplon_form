<?php

namespace Simplon\Form\Rules\Core;

use Simplon\Form\Elements\InterfaceElement;

class CoreRule implements InterfaceRule
{
    protected $conditions = [];
    protected $errorMessage = '":label" did not pass validation';

    /**
     * @param InterfaceElement $elementInstance
     *
     * @return bool
     */
    public function isValid(InterfaceElement $elementInstance)
    {
        return false;
    }

    /**
     * @param $key
     * @param $val
     *
     * @return CoreRule
     */
    protected function setConditions($key, $val)
    {
        $this->conditions[$key] = $val;

        return $this;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    protected function getConditionsByKey($key)
    {
        if (isset($this->conditions[$key]))
        {
            return $this->conditions[$key];
        }

        return false;
    }

    /**
     * @return array
     */
    protected function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @return array
     */
    protected function getConditionPlaceholders()
    {
        return $this->getConditions();
    }

    /**
     * @param $stringWithPlaceholders
     *
     * @return mixed
     */
    protected function parseConditionPlaceholders($stringWithPlaceholders)
    {
        $placeholders = $this->getConditionPlaceholders();

        foreach ($placeholders as $tag => $value)
        {
            $stringWithPlaceholders = str_replace(":{$tag}", $value, $stringWithPlaceholders);
        }

        return $stringWithPlaceholders;
    }

    /**
     * @param $errorMessage
     *
     * @return $this
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param InterfaceElement $elementInstance
     *
     * @return string
     */
    public function renderErrorMessage(InterfaceElement $elementInstance)
    {
        $parsedFieldPlaceholders = $elementInstance->parseFieldPlaceholders($this->getErrorMessage());

        return $this->parseConditionPlaceholders($parsedFieldPlaceholders);
    }
}