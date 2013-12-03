<?php

    namespace Simplon\Form\Elements;

    use Simplon\Form\Elements\Core\ElementCore;

    class ElementSingleTextField extends ElementCore
    {
        protected $_elementHtml = '<div class=":hasError"><input type="text" class="form-control" name=":id" id=":id" value=":value" placeholder=":placeholder"></div>';
        protected $_placeholder;

        // ######################################

        /**
         * @param mixed $placeholder
         *
         * @return static
         */
        public function setPlaceholder($placeholder)
        {
            $this->_placeholder = $placeholder;

            return $this;
        }

        // ######################################

        /**
         * @return mixed
         */
        public function getPlaceholder()
        {
            return $this->_placeholder;
        }

        // ######################################

        /**
         * @return array
         */
        protected function _getFieldPlaceholders()
        {
            $placeholders = parent::_getFieldPlaceholders();
            $placeholders['placeholder'] = $this->getPlaceholder();

            return $placeholders;
        }


    }