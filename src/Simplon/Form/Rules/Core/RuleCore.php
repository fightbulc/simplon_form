<?php

    namespace Simplon\Form\Rules\Core;

    use Simplon\Form\Elements\Core\ElementInterface;

    class RuleCore implements RuleInterface
    {
        protected $_conditions = [];
        protected $_errorMessage = '":label" did not pass validation';

        // ######################################

        /**
         * @param ElementInterface $elementInstance
         *
         * @return bool
         */
        public function isValid(ElementInterface $elementInstance)
        {
            return FALSE;
        }

        // ######################################

        /**
         * @param $key
         * @param $val
         *
         * @return RuleCore
         */
        protected function _setConditions($key, $val)
        {
            $this->_conditions[$key] = $val;

            return $this;
        }

        // ######################################

        /**
         * @param $key
         *
         * @return bool
         */
        protected function _getConditionsByKey($key)
        {
            if (isset($this->_conditions[$key]))
            {
                return $this->_conditions[$key];
            }

            return FALSE;
        }

        // ######################################

        /**
         * @return array
         */
        protected function _getConditions()
        {
            return $this->_conditions;
        }

        // ######################################

        /**
         * @return array
         */
        protected function _getConditionPlaceholders()
        {
            return $this->_getConditions();
        }

        // ######################################

        /**
         * @param $stringWithPlaceholders
         *
         * @return mixed
         */
        protected function _parseConditionPlaceholders($stringWithPlaceholders)
        {
            $placeholders = $this->_getConditionPlaceholders();

            foreach ($placeholders as $tag => $value)
            {
                $stringWithPlaceholders = str_replace(":{$tag}", $value, $stringWithPlaceholders);
            }

            return $stringWithPlaceholders;
        }

        // ######################################

        /**
         * @param $errorMessage
         *
         * @return $this
         */
        public function setErrorMessage($errorMessage)
        {
            $this->_errorMessage = $errorMessage;

            return $this;
        }

        // ######################################

        /**
         * @return string
         */
        public function getErrorMessage()
        {
            return $this->_errorMessage;
        }

        // ######################################

        /**
         * @param ElementInterface $elementInstance
         *
         * @return string
         */
        public function renderErrorMessage(ElementInterface $elementInstance)
        {
            $parsedFieldPlaceholders = $elementInstance->parseFieldPlaceholders($this->getErrorMessage());

            return $this->_parseConditionPlaceholders($parsedFieldPlaceholders);
        }
    }