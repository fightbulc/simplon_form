<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Rules\Core\CoreRule;

class LengthExactRule extends CoreRule
{
    /**
     * @var string
     */
    protected $errorMessage = '":label" needs to be exactly ":exactLength" characters long';

    /**
     * @var string
     */
    protected $keyExactLength = 'minLength';

    /**
     * @param $exactLength
     */
    public function __construct($exactLength)
    {
        $this->setConditions($this->keyExactLength, $exactLength);
    }

    /**
     * @param CoreElementInterface $elementInstance
     *
     * @return bool
     */
    public function isValid(CoreElementInterface $elementInstance)
    {
        $value = $elementInstance->getValue();

        return mb_strlen($value, 'UTF-8') === $this->getExactLength();
    }

    /**
     * @return int
     */
    protected function getExactLength()
    {
        return $this->getConditionsByKey($this->keyExactLength);
    }
}