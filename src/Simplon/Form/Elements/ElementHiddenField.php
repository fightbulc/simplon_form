<?php

    namespace Simplon\Form\Elements;

    use Simplon\Form\Elements\Core\ElementCore;

    class ElementHiddenField extends ElementCore
    {
        protected $_elementHtml = '<input type="hidden" name=":id" value=":value">';

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