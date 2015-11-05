<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\Field;

/**
 * Class FieldDependencyRule
 * @package Simplon\Form\Data\Rules
 */
class FieldDependencyRule extends Rule
{
    /**
     * @var Field
     */
    private $depField;

    /**
     * @var RuleInterface[]
     */
    private $depFieldRules;

    /**
     * @param Field $depField
     * @param RuleInterface[] $depFieldRules
     */
    public function __construct(Field $depField, array $depFieldRules)
    {
        $this->depField = $depField;
        $this->depFieldRules = $depFieldRules;
    }

    /**
     * @param Field $field
     */
    public function apply(Field $field)
    {
        $this->depField->setRules($this->depFieldRules);
    }
}