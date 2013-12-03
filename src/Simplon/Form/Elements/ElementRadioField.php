<?php

    namespace Simplon\Form\Elements;

    use Simplon\Form\Elements\Core\ElementCore;

    class ElementRadioField extends ElementCore
    {
        protected $_elementHtml = '<div id=":id" class="btn-group btn-group-justified" data-toggle="buttons">:items</div>';
        protected $_elementItemHtml = '<label class="btn btn-default:active"><input type="radio" name=":id" id=":id_:value" value=":value":checked> :label</label>';
        protected $_options = [];

        // ######################################

        /**
         * @return string
         */
        public function getElementItemHtml()
        {
            return $this->_elementItemHtml;
        }

        // ######################################

        /**
         * @param array $options
         *
         * @return $this
         */
        public function setOptions(array $options)
        {
            $this->_options = $options;

            return $this;
        }

        // ######################################

        /**
         * @return array
         */
        public function getOptions()
        {
            return $this->_options;
        }

        // ######################################

        /**
         * @param $key
         *
         * @return $this
         */
        public function setPreselectedOption($key)
        {
            $this->setValue($key);

            return $this;
        }

        // ######################################

        /**
         * @return string
         */
        protected function _renderElementItemHtml()
        {
            $rendered = [];
            $options = $this->getOptions();

            foreach ($options as $key => $label)
            {
                $tmpl = $this->getElementItemHtml();

                $active = NULL;
                $checked = NULL;

                if ($this->getValue() === $key)
                {
                    $active = ' active';
                    $checked = ' checked';
                }

                $placeholder = [
                    'id'      => $this->getId(),
                    'value'   => $key,
                    'label'   => $label,
                    'active'  => $active,
                    'checked' => $checked,
                ];

                $rendered[] = $this->_replaceFieldPlaceholderMany($placeholder, $tmpl);
            }

            return join('', $rendered);
        }

        // ######################################

        /**
         * @return string
         */
        protected function _renderElementHtml()
        {
            // set JS
            $this->addJs("$('#{$this->getId()}').button()");

            // render elm
            $elementHtml = $this->_replaceFieldPlaceholder('items', $this->_renderElementItemHtml(), $this->getElementHtml());

            return $this->parseFieldPlaceholders($elementHtml);
        }
    }