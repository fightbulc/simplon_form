<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\Field;

/**
 * Interface RuleInterface
 * @package Simplon\Form\Data\Rules
 */
interface RuleInterface
{
    /**
     * @param Field $field
     *
     * @throws RuleException
     */
    public function apply(Field $field);
}