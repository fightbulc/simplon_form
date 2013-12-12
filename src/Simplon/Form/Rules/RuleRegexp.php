<?php

    namespace Simplon\Form\Rules;

    use Simplon\Form\Elements\Core\ElementInterface;
    use Simplon\Form\Rules\Core\RuleCore;

    class RuleRegExp extends RuleCore
    {
        protected $_errorMessage = '":label" does not match ":regexp"';
        protected $_keyMatch = 'regexp';

        // ######################################

        /**
         * @param ElementInterface $elementInstance
         *
         * @return bool
         */
        public function isValid(ElementInterface $elementInstance)
        {
            $value = $elementInstance->getValue();

            if (preg_match($this->_getRegExpValue(), $value) === 0)
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
        public function setRegExpValue($matchValue)
        {
            $this->_setConditions($this->_keyMatch, $matchValue);

            return $this;
        }

        // ######################################

        /**
         * @return mixed
         */
        protected function _getRegExpValue()
        {
            return $this->_getConditionsByKey($this->_keyMatch);
        }
    }