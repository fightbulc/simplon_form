<?php

    namespace Simplon\Form\Elements;

    use Simplon\Form\Elements\Core\ElementCore;

    class ElementSubmitButton extends ElementCore
    {
        protected $_elementHtml = '<input type="submit" class=":class" value=":label">';

        // ######################################

        /**
         * @return array
         */
        protected function _getFieldPlaceholders()
        {
            $coreFieldPlaceholders = parent::_getFieldPlaceholders();

            // add options
            $coreFieldPlaceholders['class'] = 'btn btn-primary';

            return $coreFieldPlaceholders;
        }

        // ######################################

        /**
         * @return array
         */
        public function render()
        {
            return [
                'element' => $this->parseFieldPlaceholders($this->getElementHtml()),
            ];
        }
    }