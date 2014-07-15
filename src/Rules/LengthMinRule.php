<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\InterfaceElement;
use Simplon\Form\Rules\Core\CoreRule;

class LengthMinRule extends CoreRule
{
    protected $errorMessage = '":label" has too less characters (min allowed: :minLength)';
    protected $keyMinLength = 'minLength';

    /**
     * @param \Simplon\Form\Elements\InterfaceElement $elementInstance
     *
     * @return bool
     */
    public function isValid(InterfaceElement $elementInstance)
    {
        $value = $elementInstance->getValue();

        if (strlen($value) < $this->getLength())
        {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $minLength
     *
     * @return ExactMatchRule
     */
    public function setLength($minLength)
    {
        $this->setConditions($this->keyMinLength, $minLength);

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getLength()
    {
        return $this->getConditionsByKey($this->keyMinLength);
    }
}