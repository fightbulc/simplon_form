<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\Field;

/**
 * Class MinLengthRule
 * @package Simplon\Form\Data\Rules
 */
class MinLengthRule extends Rule
{
    /**
     * @var string
     */
    protected $errorMessage = 'Requires at least {length} characters';

    /**
     * @var int
     */
    private $length;

    /**
     * @param $length
     */
    public function __construct($length)
    {
        $this->length = $length;
    }

    /**
     * @param Field $field
     *
     * @throws RuleException
     */
    public function isValid(Field $field)
    {
        if (mb_strlen($field->getValue(), 'UTF-8') < $this->length)
        {
            $this->setErrorMessage(
                $this->getErrorMessage(),
                ['length' => $this->length]
            );

            throw new RuleException(
                $this->getErrorMessage()
            );
        }
    }
}