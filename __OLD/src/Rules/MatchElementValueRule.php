<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Rules\Core\CoreRule;

class MatchElementValueRule extends CoreRule
{
    /**
     * @var string
     */
    protected $errorMessage = '":label" does not match field ":matchElementLabel"';

    /**
     * @var string
     */
    protected $keyMatchElement = 'matchElement';

    /**
     * @param CoreElementInterface $elementInstance
     */
    public function __construct(CoreElementInterface $elementInstance)
    {
        $this->setConditions($this->keyMatchElement, $elementInstance);
    }

    /**
     * @param CoreElementInterface $elementInstance
     *
     * @return bool
     */
    public function isValid(CoreElementInterface $elementInstance)
    {
        $value = $elementInstance->getValue();
        $matchElementValue = $this->getMatchElement()->getValue();

        return strcasecmp($value, $matchElementValue) === 0;
    }

    /**
     * @return array
     */
    protected function getConditionPlaceholders()
    {
        return [
            'matchElementLabel' => $this->getMatchElement()->getLabel(),
        ];
    }

    /**
     * @return \Simplon\Form\Elements\CoreElementInterface
     */
    protected function getMatchElement()
    {
        return $this->getConditionsByKey($this->keyMatchElement);
    }
}