<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\InterfaceElement;
use Simplon\Form\Rules\Core\CoreRule;

class CheckboxMinCheckedRule extends CoreRule
{
    protected $errorMessage = '":label" requires at least :minChecked selections';

    /**
     * @param \Simplon\Form\Elements\InterfaceElement $elementInstance
     *
     * @return bool
     */
    public function isValid(InterfaceElement $elementInstance)
    {
        $value = $elementInstance->getPostValue();

        if (empty($value) || count($value) < $this->getMinChecked())
        {
            return false;
        }

        return true;
    }

    /**
     * @return int
     */
    public function getMinChecked()
    {
        return (int)$this->getConditionsByKey('minChecked');
    }

    /**
     * @param int $minChecked
     *
     * @return CheckboxMinCheckedRule
     */
    public function setMinChecked($minChecked)
    {
        $this->setConditions('minChecked', $minChecked);

        return $this;
    }
}