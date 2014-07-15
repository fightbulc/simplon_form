<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\InterfaceElement;
use Simplon\Form\Rules\Core\CoreRule;

class MatchElementValueRule extends CoreRule
{
    protected $errorMessage = '":label" does not match field ":matchElementLabel"';
    protected $keyMatchElement = 'matchElement';

    /**
     * @param InterfaceElement $elementInstance
     *
     * @return bool
     */
    public function isValid(InterfaceElement $elementInstance)
    {
        $value = $elementInstance->getValue();

        $matchElementValue = $this->getMatchElement()
            ->getValue();

        if ($value !== $matchElementValue || $elementInstance->isValid() === false)
        {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    protected function getConditionPlaceholders()
    {
        return [
            'matchElementLabel' => $this->getMatchElement()
                ->getLabel(),
        ];
    }

    /**
     * @param InterfaceElement $elementInstance
     *
     * @return MatchElementValueRule
     */
    public function setMatchElement(InterfaceElement $elementInstance)
    {
        $this->setConditions($this->keyMatchElement, $elementInstance);

        return $this;
    }

    /**
     * @return \Simplon\Form\Elements\InterfaceElement
     */
    protected function getMatchElement()
    {
        return $this->getConditionsByKey($this->keyMatchElement);
    }
}