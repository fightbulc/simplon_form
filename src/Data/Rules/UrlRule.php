<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\FormField;

/**
 * @package Simplon\Form\Data\Rules
 */
class UrlRule extends Rule
{
    /**
     * @var string
     */
    protected $errorMessage = 'Invalid URL address';

    /**
     * @param FormField $field
     *
     * @throws RuleException
     */
    public function apply(FormField $field)
    {
        if (filter_var($field->getValue(), FILTER_VALIDATE_URL) === false)
        {
            throw new RuleException(
                $this->getErrorMessage()
            );
        }
    }
}