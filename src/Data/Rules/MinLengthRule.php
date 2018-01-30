<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\FormField;
use Simplon\Form\Data\Rule;
use Simplon\Form\Data\RuleException;

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
     * @param int $length
     */
    public function __construct(int $length)
    {
        $this->length = $length;
    }

    /**
     * @param FormField $field
     *
     * @throws RuleException
     */
    public function apply(FormField $field)
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