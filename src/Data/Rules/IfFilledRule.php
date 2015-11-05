<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\Field;

/**
 * Class IfFilledRule
 * @package Simplon\Form\Data\Rules
 */
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
     * @param Field $field
     */
    public function apply(Field $field)
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
    public function getFollowUpRules()
    {
        return $this->followUpRules;
    }

    /**
     * @param RuleInterface[] $rules
     *
     * @return IfFilledRule
     */
    public function setFollowUpRules(array $rules)
    {
        $this->followUpRules = $rules;

        return $this;
    }
}