<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\InterfaceElement;
use Simplon\Form\Rules\Core\CoreRule;

class CheckboxRangeCheckedRule extends CoreRule
{
    protected $errorMessage = '":label" must be between :minChecked - :maxChecked selections';

    /**
     * @param \Simplon\Form\Elements\InterfaceElement $elementInstance
     *
     * @return bool
     */
    public function isValid(InterfaceElement $elementInstance)
    {
        $value = $elementInstance->getPostValue();
        $elmCount = count($value);

        if (empty($value) || $elmCount < $this->getMinChecked() || $elmCount > $this->getMaxChecked())
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
     * @return CheckboxRangeCheckedRule
     */
    public function setMinChecked($minChecked)
    {
        $this->setConditions('minChecked', $minChecked);

        return $this;
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
     * @return CheckboxRangeCheckedRule
     */
    public function setMaxChecked($maxChecked)
    {
        $this->setConditions('maxChecked', $maxChecked);

        return $this;
    }
}