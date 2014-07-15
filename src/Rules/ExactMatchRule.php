<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\InterfaceElement;
use Simplon\Form\Rules\Core\CoreRule;

class ExactMatchRule extends CoreRule
{
    protected $errorMessage = '":label" does not match ":match" (case-insensitive)';
    protected $keyMatch = 'match';

    /**
     * @param InterfaceElement $elementInstance
     *
     * @return bool
     */
    public function isValid(InterfaceElement $elementInstance)
    {
        $value = $elementInstance->getValue();

        if (strcasecmp($value, $this->getMatchValue()) !== 0)
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
    public function setMatchValue($matchValue)
    {
        $this->setConditions($this->keyMatch, $matchValue);

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getMatchValue()
    {
        return $this->getConditionsByKey($this->keyMatch);
    }
}