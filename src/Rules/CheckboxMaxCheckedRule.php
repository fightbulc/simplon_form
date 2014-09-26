<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Rules\Core\CoreRule;

class CheckboxMaxCheckedRule extends CoreRule
{
    /**
     * @var string
     */
    protected $errorMessage = '":label" only allows :maxChecked selections';

    /**
     * @var string
     */
    protected $keyMax = 'maxChecked';

    /**
     * @param $maxChecked
     */
    public function __construct($maxChecked)
    {
        $this->setConditions($this->keyMax, $maxChecked);
    }

    /**
     * @param \Simplon\Form\Elements\CoreElementInterface $elementInstance
     *
     * @return bool
     */
    public function isValid(CoreElementInterface $elementInstance)
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
        return (int)$this->getConditionsByKey($this->keyMax);
    }
}