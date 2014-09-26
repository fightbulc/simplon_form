<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Rules\Core\CoreRule;

class CheckboxMinCheckedRule extends CoreRule
{
    /**
     * @var string
     */
    protected $errorMessage = '":label" requires at least :minChecked selections';

    /**
     * @var string
     */
    protected $keyMin = 'minChecked';

    /**
     * @param $minChecked
     */
    public function __construct($minChecked)
    {
        $this->setConditions($this->keyMin, $minChecked);
    }

    /**
     * @param \Simplon\Form\Elements\CoreElementInterface $elementInstance
     *
     * @return bool
     */
    public function isValid(CoreElementInterface $elementInstance)
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
    protected function getMinChecked()
    {
        return (int)$this->getConditionsByKey($this->keyMin);
    }
}