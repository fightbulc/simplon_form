<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\Field;

/**
 * Class UrlRule
 * @package Simplon\Form\Data\Rules
 */
class UrlRule extends Rule
{
    /**
     * @var string
     */
    protected $errorMessage = 'Invalid URL address';

    /**
     * @param Field $field
     *
     * @throws RuleException
     */
    public function isValid(Field $field)
    {
        if (filter_var($field->getValue(), FILTER_VALIDATE_URL) === false)
        {
            throw new RuleException(
                $this->getErrorMessage()
            );
        }
    }
}