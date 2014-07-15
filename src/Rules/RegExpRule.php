<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\InterfaceElement;
use Simplon\Form\Rules\Core\CoreRule;

class RegExpRule extends CoreRule
{
    protected $errorMessage = '":label" does not match ":regexp"';
    protected $keyMatch = 'regexp';

    /**
     * @param InterfaceElement $elementInstance
     *
     * @return bool
     */
    public function isValid(InterfaceElement $elementInstance)
    {
        $value = $elementInstance->getValue();

        if (preg_match($this->getRegExpValue(), $value) === 0)
        {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $matchValue
     *
     * @return ExactMatchRule
     */
    public function setRegExpValue($matchValue)
    {
        $this->setConditions($this->keyMatch, $matchValue);

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getRegExpValue()
    {
        return $this->getConditionsByKey($this->keyMatch);
    }
}