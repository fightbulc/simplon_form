<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Rules\Core\CoreRule;

class RegExpRule extends CoreRule
{
    /**
     * @var string
     */
    protected $errorMessage = '":label" does not match ":regexp"';

    /**
     * @var string
     */
    protected $keyMatch = 'regexp';

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

        return preg_match($this->getRegExpValue(), $value) !== 0;
    }

    /**
     * @return mixed
     */
    protected function getRegExpValue()
    {
        return $this->getConditionsByKey($this->keyMatch);
    }
}