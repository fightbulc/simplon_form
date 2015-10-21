<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\Field;

/**
 * Class EmailRule
 * @package Simplon\Form\Data\Rules
 */
class EmailRule implements RuleInterface
{
    protected $errorMessage = '":label" is not an email address';

    /**
     * @param Field $field
     *
     * @return bool
     */
    public function isValid(Field $field)
    {
        return filter_var($field->getValue(), FILTER_VALIDATE_EMAIL) === true;
    }
}