<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Rules\Core\CoreRule;

class LengthMinRule extends CoreRule
{
    protected $errorMessage = '":label" has too less characters (min allowed: :minLength)';

    /**
     * @var string
     */
    protected $keyLength = 'minLength';

    /**
     * @param $length
     */
    public function __construct($length)
    {
        $this->setConditions($this->keyLength, $length);
    }

    /**
     * @param CoreElementInterface $elementInstance
     *
     * @return bool
     */
    public function isValid(CoreElementInterface $elementInstance)
    {
        $value = $elementInstance->getValue();

        return mb_strlen($value, 'UTF-8') >= $this->getLength();
    }

    /**
     * @return int
     */
    protected function getLength()
    {
        return $this->getConditionsByKey($this->keyLength);
    }
}