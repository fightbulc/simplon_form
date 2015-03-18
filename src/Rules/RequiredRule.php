<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\CheckboxMulti\CheckboxMultiElement;
use Simplon\Form\Elements\CheckboxSingle\CheckboxSingleElement;
use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Rules\Core\CoreRule;

class RequiredRule extends CoreRule
{
    /**
     * @var string
     */
    protected $errorMessage = '":label" is required';

    /**
     * @param CoreElementInterface $elementInstance
     *
     * @return bool
     */
    public function isValid(CoreElementInterface $elementInstance)
    {
        // ----------------------------------
        // fields with inmutable values

        $isCheckbox =
            $elementInstance instanceof CheckboxSingleElement
            || $elementInstance instanceof CheckboxMultiElement;

        if ($isCheckbox === true)
        {
            if ($elementInstance->hasCheckedOption() === false)
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