<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\InterfaceElement;
use Simplon\Form\Rules\Core\CoreRule;

class LengthExactRule extends CoreRule
{
    protected $errorMessage = '":label" needs to be exactly ":exactLength" characters long';
    protected $keyExactLength = 'minLength';

    /**
     * @param InterfaceElement $elementInstance
     *
     * @return bool
     */
    public function isValid(InterfaceElement $elementInstance)
    {
        $value = $elementInstance->getValue();

        if (strlen($value) === $this->getExactLength())
        {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $exactLength
     *
     * @return ExactMatchRule
     */
    public function setExactLength($exactLength)
    {
        $this->setConditions($this->keyExactLength, $exactLength);

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getExactLength()
    {
        return $this->getConditionsByKey($this->keyExactLength);
    }
}