<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\FormField;
use Simplon\Form\Data\Rule;
use Simplon\Form\Data\RuleException;

class WithinOptionsRule extends Rule
{
    /**
     * @var string
     */
    protected $errorMessage = 'Given option is invalid';

    /**
     * @param FormField $field
     *
     * @throws RuleException
     */
    public function apply(FormField $field)
    {
        $options = $field->getMeta('options');
        $values = explode(',', $field->getValue());
        $invalidValue = false;

        if ($field->getValue() && $options)
        {
            $possibleValues = [];

            foreach ($options as $option)
            {
                $possibleValues[] = $option['value'];
            }

            foreach ($values as $value)
            {
                if (in_array($value, $possibleValues) === false)
                {
                    $invalidValue = true;
                }
            }
        }

        if ($invalidValue)
        {
            throw new RuleException(
                $this->getErrorMessage()
            );
        }
    }
}