<?php

namespace Simplon\Form\Rules\Core;

use Respect\Validation\Validator;
use Simplon\Form\Elements\CoreElementInterface;

/**
 * CoreRule
 * @package Simplon\Form\Rules\Core
 * @author Tino Ehrich (tino@bigpun.me)
 */
abstract class CoreRule implements CoreRuleInterface
{
    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * @var string
     */
    protected $errorMessage = '":label" did not pass validation';

    /**
     * @return Validator
     */
    protected function getValidationEngine()
    {
        return new Validator();
    }

    /**
     * @param CoreElementInterface $elementInstance
     *
     * @return bool
     */
    public function isValid(CoreElementInterface $elementInstance)
    {
        return false;
    }

    /**
     * @param $key
     * @param $val
     *
     * @return CoreRuleInterface
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
     * @param CoreElementInterface $elementInstance
     *
     * @return string
     */
    public function renderErrorMessage(CoreElementInterface $elementInstance)
    {
        $parsedFieldPlaceholders = $elementInstance->parseFieldPlaceholders($this->getErrorMessage());

        return $this->parseConditionPlaceholders($parsedFieldPlaceholders);
    }
}