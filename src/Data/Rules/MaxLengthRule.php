<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\Field;

/**
 * Class MaxLengthRule
 * @package Simplon\Form\Data\Rules
 */
class MaxLengthRule extends Rule
{
    /**
     * @var string
     */
    protected $errorMessage = 'Allows at most {length} characters';

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
    public function apply(Field $field)
    {
        if (mb_strlen($field->getValue(), 'UTF-8') > $this->length)
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