<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\Checkbox\CheckboxElement;
use Simplon\Form\Elements\InterfaceElement;
use Simplon\Form\Rules\Core\CoreRule;

class RequiredRule extends CoreRule
{
    protected $errorMessage = '":label" is required';

    /**
     * @param \Simplon\Form\Elements\InterfaceElement $elementInstance
     *
     * @return bool
     */
    public function isValid(InterfaceElement $elementInstance)
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

        $value = $elementInstance->getValue();

        if ($value === '')
        {
            return false;
        }

        return true;
    }
}