<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\Field;

/**
 * Class EmailRule
 * @package Simplon\Form\Data\Rules
 */
class EmailRule extends Rule
{
    /**
     * @var string
     */
    protected $errorMessage = 'Invalid email address';

    /**
     * @param Field $field
     *
     * @throws RuleException
     */
    public function isValid(Field $field)
    {
        if (filter_var($field->getValue(), FILTER_VALIDATE_EMAIL) === false)
        {
            throw new RuleException(
                $this->getErrorMessage()
            );
        }
    }
}