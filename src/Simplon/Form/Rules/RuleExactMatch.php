<?php

    namespace Simplon\Form\Rules;

    use Simplon\Form\Elements\Core\ElementInterface;
    use Simplon\Form\Rules\Core\RuleCore;

    class RuleExactMatch extends RuleCore
    {
        protected $_errorMessage = '":label" does not match ":match" (case-insensitive)';
        protected $_keyMatch = 'match';

        // ######################################

        /**
         * @param ElementInterface $elementInstance
         *
         * @return bool
         */
        public function isValid(ElementInterface $elementInstance)
        {
            $value = $elementInstance->getValue();

            if (strcasecmp($value, $this->_getMatchValue()) != 0)
            {
                return FALSE;
            }

            return TRUE;
        }

        // ######################################

        /**
         * @param mixed $matchValue
         *
         * @return RuleExactMatch
         */
        public function setMatchValue($matchValue)
        {
            $this->_setConditions($this->_keyMatch, $matchValue);

            return $this;
        }

        // ######################################

        /**
         * @return mixed
         */
        protected function _getMatchValue()
        {
            return $this->_getConditionsByKey($this->_keyMatch);
        }
    }