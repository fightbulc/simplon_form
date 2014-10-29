<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\Checkbox\CheckboxElement;
use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Rules\Core\CoreRule;

class RequiredRule extends CoreRule
{
    /**
     * @var string
     */
    protected $errorMessage = '":label" is required';

    /**
     * @param \Simplon\Form\Elements\CoreElementInterface $elementInstance
     *
     * @return bool
     */
    public function isValid(CoreElementInterface $elementInstance)
    {
        // ----------------------------------
        // fields with inmutable values

        if ($elementInstance instanceof CheckboxElement)
        {
            if ($elementInstance->hasCheckedOptions() === false)
            {
                return false;
            }

            return true;
        }

        // ----------------------------------
        // fields with actual mutable values

        return $elementInstance->getValue() !== '';
    }
}