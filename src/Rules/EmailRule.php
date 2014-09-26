<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Rules\Core\CoreRule;

class EmailRule extends CoreRule
{
    protected $errorMessage = '":label" is not an email address';

    /**
     * @param \Simplon\Form\Elements\CoreElementInterface $elementInstance
     *
     * @return bool
     */
    public function isValid(CoreElementInterface $elementInstance)
    {
        $value = $elementInstance->getValue();
        $validator = $this->getValidationEngine()->email();

        return $validator->validate($value);
    }
}