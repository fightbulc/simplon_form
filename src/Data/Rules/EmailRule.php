<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\FormField;
use Simplon\Form\Data\Rule;
use Simplon\Form\Data\RuleException;

/**
 * @package Simplon\Form\Data\Rules
 */
class EmailRule extends Rule
{
    /**
     * @var string
     */
    protected $errorMessage = 'Invalid email address';

    /**
     * @param FormField $field
     *
     * @throws RuleException
     */
    public function apply(FormField $field)
    {
        if (filter_var($field->getValue(), FILTER_VALIDATE_EMAIL) === false)
        {
            throw new RuleException(
                $this->getErrorMessage()
            );
        }
    }
}