<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\FormField;

/**
 * Class RequiredRule
 * @package Simplon\Form\Data\Rules
 */
class RequiredRule extends Rule
{
    /**
     * @var string
     */
    protected $errorMessage = 'Field is required';

    /**
     * @param FormField $field
     *
     * @throws RuleException
     */
    public function apply(FormField $field)
    {
        if ($field->getValue() === '' || $field->getValue() === null)
        {
            throw new RuleException(
                $this->getErrorMessage()
            );
        }
    }
}