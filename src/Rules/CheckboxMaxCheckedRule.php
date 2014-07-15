<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\InterfaceElement;
use Simplon\Form\Rules\Core\CoreRule;

class CheckboxMaxCheckedRule extends CoreRule
{
    protected $errorMessage = '":label" only allows :maxChecked selections';

    /**
     * @param \Simplon\Form\Elements\InterfaceElement $elementInstance
     *
     * @return bool
     */
    public function isValid(InterfaceElement $elementInstance)
    {
        $value = $elementInstance->getPostValue();

        if (empty($value) || count($value) > $this->getMaxChecked())
        {
            return false;
        }

        return true;
    }

    /**
     * @return int
     */
    public function getMaxChecked()
    {
        return (int)$this->getConditionsByKey('maxChecked');
    }

    /**
     * @param int $maxChecked
     *
     * @return CheckboxMaxCheckedRule
     */
    public function setMaxChecked($maxChecked)
    {
        $this->setConditions('maxChecked', $maxChecked);

        return $this;
    }
}