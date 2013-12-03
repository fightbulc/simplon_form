<?php

    namespace Simplon\Form\Rules;

    use Simplon\Form\Elements\Core\ElementInterface;
    use Simplon\Form\Rules\Core\RuleCore;

    class RuleMatchElementValue extends RuleCore
    {
        protected $_errorMessage = '":label" does not match field ":matchElementLabel"';
        protected $_keyMatchElement = 'matchElement';

        // ######################################

        /**
         * @param ElementInterface $elementInstance
         *
         * @return bool
         */
        public function isValid(ElementInterface $elementInstance)
        {
            $value = $elementInstance->getValue();

            $matchElementValue = $this->_getMatchElement()
                ->getValue();

            if ($value !== $matchElementValue || $elementInstance->isValid() === FALSE)
            {
                return FALSE;
            }

            return TRUE;
        }

        // ######################################

        /**
         * @return array
         */
        protected function _getConditionPlaceholders()
        {
            return [
                'matchElementLabel' => $this->_getMatchElement()
                    ->getLabel(),
            ];
        }

        // ######################################

        /**
         * @param ElementInterface $elementInstance
         *
         * @return RuleMatchElementValue
         */
        public function setMatchElement(ElementInterface $elementInstance)
        {
            $this->_setConditions($this->_keyMatchElement, $elementInstance);

            return $this;
        }

        // ######################################

        /**
         * @return ElementInterface
         */
        protected function _getMatchElement()
        {
            return $this->_getConditionsByKey($this->_keyMatchElement);
        }
    }