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
     * @var string
     */
    protected $negateResult = 'negate';

    /**
     * @param string $matchValue
     * @param bool   $negateResult
     */
    public function __construct($matchValue, $negateResult = false)
    {
        $this->setConditions($this->keyMatch, $matchValue);
        $this->setConditions($this->negateResult, $negateResult === true);
    }

    /**
     * @param CoreElementInterface $elementInstance
     *
     * @return bool
     */
    public function isValid(CoreElementInterface $elementInstance)
    {
        $value  = $elementInstance->getValue();
        $result = preg_match($this->getRegExpValue(), $value) !== 0;

        if ($this->getNegateResult() === true) {
            return !$result;
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function getRegExpValue()
    {
        return $this->getConditionsByKey($this->keyMatch);
    }

    /**
     * @return bool
     */
    protected function getNegateResult()
    {
        return $this->getConditionsByKey($this->negateResult);
    }
}