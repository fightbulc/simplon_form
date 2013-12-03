<?php

    namespace Simplon\Form\Rules;

    use Simplon\Form\Elements\Core\ElementInterface;
    use Simplon\Form\Rules\Core\RuleCore;

    class RuleLengthMin extends RuleCore
    {
        protected $_errorMessage = '":label" has too less characters (min allowed: :minLength)';
        protected $_keyMinLength = 'minLength';

        // ######################################

        /**
         * @param ElementInterface $elementInstance
         *
         * @return bool
         */
        public function isValid(ElementInterface $elementInstance)
        {
            $value = $elementInstance->getValue();

            if (strlen($value) < $this->_getLength())
            {
                return FALSE;
            }

            return TRUE;
        }

        // ######################################

        /**
         * @param mixed $minLength
         *
         * @return RuleExactMatch
         */
        public function setLength($minLength)
        {
            $this->_setConditions($this->_keyMinLength, $minLength);

            return $this;
        }

        // ######################################

        /**
         * @return mixed
         */
        protected function _getLength()
        {
            return $this->_getConditionsByKey($this->_keyMinLength);
        }
    }