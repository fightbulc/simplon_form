<?php

    namespace Simplon\Form\Elements;

    use Simplon\Form\Elements\Core\ElementCore;

    class ElementButton extends ElementCore
    {
        protected $_elementHtml = '<button class=":class">:label</button>';

        // ######################################

        /**
         * @return array
         */
        protected function _getFieldPlaceholders()
        {
            $coreFieldPlaceholders = parent::_getFieldPlaceholders();

            // add options
            $coreFieldPlaceholders['class'] = 'btn';

            return $coreFieldPlaceholders;
        }

        // ######################################

        /**
         * @return array
         */
        public function render()
        {
            return [
                'element' => $this->_renderElementHtml(),
            ];
        }
    }