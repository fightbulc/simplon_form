<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Rules\Core\CoreRule;

class ExactMatchRule extends CoreRule
{
    /**
     * @var string
     */
    protected $errorMessage = '":label" does not match ":match" (case-insensitive)';

    /**
     * @var string
     */
    protected $keyMatch = 'match';

    /**
     * @param string $matchValue
     */
    public function __construct($matchValue)
    {
        $this->setConditions($this->keyMatch, $matchValue);
    }

    /**
     * @param CoreElementInterface $elementInstance
     *
     * @return bool
     */
    public function isValid(CoreElementInterface $elementInstance)
    {
        $value = $elementInstance->getValue();

        return strcasecmp($value, $this->getMatchValue()) === 0;
    }

    /**
     * @return string
     */
    protected function getMatchValue()
    {
        return (string)$this->getConditionsByKey($this->keyMatch);
    }
}