<?php

    namespace Simplon\Form\Rules;

    use Simplon\Form\Elements\Core\ElementInterface;
    use Simplon\Form\Rules\Core\RuleCore;

    class RuleLengthExact extends RuleCore
    {
        protected $_errorMessage = '":label" needs to be exactly ":exactLength" characters long';
        protected $_keyExactLength = 'minLength';

        // ######################################

        /**
         * @param ElementInterface $elementInstance
         *
         * @return bool
         */
        public function isValid(ElementInterface $elementInstance)
        {
            $value = $elementInstance->getValue();

            if (strlen($value) === $this->_getExactLength())
            {
                return FALSE;
            }

            return TRUE;
        }

        // ######################################

        /**
         * @param mixed $exactLength
         *
         * @return RuleExactMatch
         */
        public function setExactLength($exactLength)
        {
            $this->_setConditions($this->_keyExactLength, $exactLength);

            return $this;
        }

        // ######################################

        /**
         * @return mixed
         */
        protected function _getExactLength()
        {
            return $this->_getConditionsByKey($this->_keyExactLength);
        }
    }