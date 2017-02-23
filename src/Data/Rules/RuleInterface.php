<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\FormField;

/**
 * @package Simplon\Form\Data\Rules
 */
interface RuleInterface
{
    /**
     * @param FormField $field
     *
     * @throws RuleException
     */
    public function apply(FormField $field);
}