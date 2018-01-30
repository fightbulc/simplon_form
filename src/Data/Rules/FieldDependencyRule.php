<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\FormField;
use Simplon\Form\Data\Rule;
use Simplon\Form\Data\RuleInterface;

class FieldDependencyRule extends Rule
{
    /**
     * @var FormField
     */
    private $depField;
    /**
     * @var RuleInterface[]
     */
    private $depFieldRules;

    /**
     * @param FormField $depField
     * @param RuleInterface[] $depFieldRules
     */
    public function __construct(FormField $depField, array $depFieldRules)
    {
        $this->depField = $depField;
        $this->depFieldRules = $depFieldRules;
    }

    /**
     * @param FormField $field
     */
    public function apply(FormField $field)
    {
        $this->depField->setRules($this->depFieldRules);
    }
}