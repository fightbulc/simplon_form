<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\FormField;
use Simplon\Form\Data\Rule;
use Simplon\Form\Data\RuleException;
use Simplon\Form\Data\RuleInterface;

class IfFilledRule extends Rule
{
    /**
     * @var RuleInterface[]
     */
    private $followUpRules;

    /**
     * @param RuleInterface[] $followUpRules
     */
    public function __construct(array $followUpRules)
    {
        $this->followUpRules = $followUpRules;
    }

    /**
     * @param FormField $field
     *
     * @throws RuleException
     */
    public function apply(FormField $field)
    {
        if ($field->getValue() !== '')
        {
            foreach ($this->followUpRules as $rule)
            {
                $rule->apply($field);
            }
        }
    }

    /**
     * @return RuleInterface[]
     */
    public function getFollowUpRules(): array
    {
        return $this->followUpRules;
    }

    /**
     * @param RuleInterface[] $rules
     *
     * @return IfFilledRule
     */
    public function setFollowUpRules(array $rules): IfFilledRule
    {
        $this->followUpRules = $rules;

        return $this;
    }
}