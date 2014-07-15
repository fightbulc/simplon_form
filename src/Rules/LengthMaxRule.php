<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\InterfaceElement;
use Simplon\Form\Rules\Core\CoreRule;

class LengthMaxRule extends CoreRule
{
    protected $errorMessage = '":label" has too many characters (max. allowed: :maxLength)';
    protected $keyMaxLength = 'maxLength';

    /**
     * @param \Simplon\Form\Elements\InterfaceElement $elementInstance
     *
     * @return bool
     */
    public function isValid(InterfaceElement $elementInstance)
    {
        $value = $elementInstance->getValue();

        if (strlen($value) > $this->getLength())
        {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $maxLength
     *
     * @return ExactMatchRule
     */
    public function setLength($maxLength)
    {
        $this->setConditions($this->keyMaxLength, $maxLength);

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getLength()
    {
        return $this->getConditionsByKey($this->keyMaxLength);
    }
}