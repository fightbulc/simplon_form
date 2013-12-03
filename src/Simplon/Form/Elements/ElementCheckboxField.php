<?php

    namespace Simplon\Form\Elements;

    use Simplon\Form\Elements\Core\ElementCore;

    class ElementCheckboxField extends ElementCore
    {
        protected $_elementHtml = '<div id=":id" class="btn-group btn-group-justified" data-toggle="buttons">:items</div>';
        protected $_elementItemHtml = '<label class="btn btn-default:active"><input type="checkbox" name=":id[]" value=":value":checked> :label</label>';
        protected $_options = [];
        protected $_checked = [];

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
         * @return ElementCheckboxField
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
         * @param array $options
         *
         * @return $this
         */
        public function setPreselectedOption(array $options)
        {
            foreach ($options as $key)
            {
                $this->setOptionChecked($key, TRUE);
            }

            return $this;
        }

        // ######################################

        /**
         * @return $this
         */
        public function ignorePreselectedOptions()
        {
            $this->_checked = [];

            return $this;
        }

        // ######################################

        /**
         * @param $key
         * @param $checked
         *
         * @return $this
         */
        public function setOptionChecked($key, $checked)
        {
            $this->_checked[$key] = $checked === TRUE ? TRUE : FALSE;

            return $this;
        }

        // ######################################

        /**
         * @param $key
         *
         * @return bool
         */
        public function getOptionChecked($key)
        {
            if (isset($this->_checked[$key]))
            {
                return $this->_checked[$key];
            }

            return FALSE;
        }

        // ######################################

        /**
         * @return array
         */
        public function getChecked()
        {
            return $this->_checked;
        }

        // ######################################

        /**
         * @return bool
         */
        public function hasCheckedOptions()
        {
            return count($this->getChecked()) > 0 ? TRUE : FALSE;
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

                if ($this->getOptionChecked($key) === TRUE)
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