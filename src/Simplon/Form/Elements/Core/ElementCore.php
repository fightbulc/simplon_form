<?php

    namespace Simplon\Form\Elements\Core;

    use Simplon\Form\Rules\Core\RuleCore;
    use Simplon\Form\Rules\Core\RuleInterface;

    class ElementCore implements ElementInterface
    {
        protected $_elementHtml = '<input type="text" class=":class" id=":id" name=":id" value=":value">';

        protected $_id;
        protected $_label;
        protected $_description;
        protected $_value;
        protected $_class = [];
        protected $_js = [];

        /** @var RuleInterface[] */
        protected $_rules = [];
        protected $_isValid = TRUE;
        protected $_errorMessages = [];
        protected $_errorContainerWrapper = 'ul';
        protected $_errorItemWrapper = 'li';

        // ######################################

        /**
         * @param $tag
         * @param $value
         * @param $string
         *
         * @return mixed
         */
        protected function _replaceFieldPlaceholder($tag, $value, $string)
        {
            return str_replace(":{$tag}", $value, $string);
        }

        // ######################################

        /**
         * @param array $pairs
         * @param $string
         *
         * @return mixed
         */
        protected function _replaceFieldPlaceholderMany(array $pairs, $string)
        {
            foreach ($pairs as $tag => $value)
            {
                $string = $this->_replaceFieldPlaceholder($tag, $value, $string);
            }

            return $string;
        }

        // ######################################

        /**
         * @return string
         */
        protected function _getErrorContainerWrapper()
        {
            return $this->_errorContainerWrapper;
        }

        // ######################################

        /**
         * @return string
         */
        protected function _getErrorItemWrapper()
        {
            return $this->_errorItemWrapper;
        }

        // ######################################

        /**
         * @param $elementHtml
         *
         * @return $this
         */
        public function setElementHtml($elementHtml)
        {
            $this->_elementHtml = $elementHtml;

            return $this;
        }

        // ######################################

        /**
         * @return string
         */
        public function getElementHtml()
        {
            return $this->_elementHtml;
        }

        // ######################################

        /**
         * @return string
         */
        protected function _renderElementHtml()
        {
            return $this->parseFieldPlaceholders($this->getElementHtml());
        }

        // ######################################

        /**
         * @param mixed $description
         *
         * @return static
         */
        public function setDescription($description)
        {
            $this->_description = $description;

            return $this;
        }

        // ######################################

        /**
         * @return mixed
         */
        public function getDescription()
        {
            return $this->_description;
        }

        // ######################################

        /**
         * @return mixed|null
         */
        protected function _renderDescription()
        {
            $description = $this->getDescription();
            $template = '<span class="help-block">:description</span>';

            if (empty($description))
            {
                return NULL;
            }

            return $this->parseFieldPlaceholders($template);
        }

        // ######################################

        /**
         * @param mixed $id
         *
         * @return static
         */
        public function setId($id)
        {
            $this->_id = $id;

            return $this;
        }

        // ######################################

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->_id;
        }

        // ######################################

        /**
         * @param mixed $label
         *
         * @return static
         */
        public function setLabel($label)
        {
            $this->_label = $label;

            return $this;
        }

        // ######################################

        /**
         * @return mixed
         */
        public function getLabel()
        {
            return $this->_label;
        }

        // ######################################

        /**
         * @return mixed
         */
        protected function _renderLabel()
        {
            $template = '<label for=":id">:label</label>';

            return $this->parseFieldPlaceholders($template);
        }

        // ######################################

        /**
         * @param mixed $value
         *
         * @return static
         */
        public function setValue($value)
        {
            $this->_value = $value;

            return $this;
        }

        // ######################################

        /**
         * @return mixed
         */
        public function getValue()
        {
            return $this->_value;
        }

        // ######################################

        /**
         * @param mixed $value
         *
         * @return static
         */
        public function addClass($value)
        {
            $this->_class[] = $value;

            return $this;
        }

        // ######################################

        /**
         * @return string
         */
        public function getClassString()
        {
            return join(' ', $this->_class);
        }

        // ######################################

        /**
         * @param array $rules
         *
         * @return static
         */
        public function setRules(array $rules)
        {
            $this->_rules = $rules;

            return $this;
        }

        // ######################################

        /**
         * @param RuleCore $rule
         *
         * @return static
         */
        public function addRule(RuleCore $rule)
        {
            $this->_rules[] = $rule;

            return $this;
        }

        // ######################################

        /**
         * @return array|RuleInterface[]
         */
        public function getRules()
        {
            return $this->_rules;
        }

        // ######################################

        /**
         * @return bool|null
         */
        public function validateRules()
        {
            $rules = $this->getRules();

            if (!empty($rules))
            {
                foreach ($rules as $ruleInstance)
                {
                    $isValid = $ruleInstance->isValid($this);

                    if ($isValid === FALSE)
                    {
                        $this->_addErrorMessage($ruleInstance->renderErrorMessage($this));
                    }
                }

                return TRUE;
            }

            return NULL;
        }

        // ######################################

        /**
         * @param $message
         */
        protected function _addErrorMessage($message)
        {
            $this->_errorMessages[] = "<{$this->_getErrorItemWrapper()}>{$message}</{$this->_getErrorItemWrapper()}>";
        }

        // ######################################

        /**
         * @return array
         */
        public function getErrorMessages()
        {
            return $this->_errorMessages;
        }

        // ######################################

        /**
         * @return string
         */
        public function renderErrorMessages()
        {
            $placeholders = [
                'containerWrapper'    => $this->_getErrorContainerWrapper(),
                'errorMessagesString' => join('', $this->getErrorMessages()),
            ];

            $template = '<:containerWrapper class="text-danger list-unstyled">:errorMessagesString</:containerWrapper>';

            return $this->_replaceFieldPlaceholderMany($placeholders, $template);
        }

        // ######################################

        /**
         * @return bool
         */
        public function isValid()
        {
            $errorMessages = $this->getErrorMessages();

            return empty($errorMessages) ? TRUE : FALSE;
        }

        // ######################################

        /**
         * @return array
         */
        protected function _getFieldPlaceholders()
        {
            return [
                'id'          => $this->getId(),
                'label'       => $this->getLabel(),
                'value'       => $this->getValue(),
                'class'       => $this->getClassString(),
                'description' => $this->getDescription(),
            ];
        }

        // ######################################

        /**
         * @param $stringWithPlaceholders
         *
         * @return mixed
         */
        public function parseFieldPlaceholders($stringWithPlaceholders)
        {
            return $this->_replaceFieldPlaceholderMany($this->_getFieldPlaceholders(), $stringWithPlaceholders);
        }

        // ######################################

        /**
         * @param $js
         *
         * @return $this
         */
        public function addJs($js)
        {
            $this->_js[] = $js;

            return $this;
        }

        // ######################################

        /**
         * @return array
         */
        public function getJs()
        {
            return $this->_js;
        }

        // ######################################

        /**
         * @return array
         */
        public function render()
        {
            return [
                'label'       => $this->_renderLabel(),
                'description' => $this->_renderDescription(),
                'element'     => $this->_renderElementHtml(),
            ];
        }
    }