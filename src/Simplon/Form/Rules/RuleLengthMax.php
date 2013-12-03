<?php

    namespace Simplon\Form\Rules;

    use Simplon\Form\Elements\Core\ElementInterface;
    use Simplon\Form\Rules\Core\RuleCore;

    class RuleLengthMax extends RuleCore
    {
        protected $_errorMessage = '":label" has too many characters (max. allowed: :maxLength)';
        protected $_keyMaxLength = 'maxLength';

        // ######################################

        /**
         * @param ElementInterface $elementInstance
         *
         * @return bool
         */
        public function isValid(ElementInterface $elementInstance)
        {
            $value = $elementInstance->getValue();

            if (strlen($value) > $this->_getLength())
            {
                return FALSE;
            }

            return TRUE;
        }

        // ######################################

        /**
         * @param mixed $maxLength
         *
         * @return RuleExactMatch
         */
        public function setLength($maxLength)
        {
            $this->_setConditions($this->_keyMaxLength, $maxLength);

            return $this;
        }

        // ######################################

        /**
         * @return mixed
         */
        protected function _getLength()
        {
            return $this->_getConditionsByKey($this->_keyMaxLength);
        }
    }