<?php

    namespace Simplon\Form\Elements;

    use Simplon\Form\Elements\Core\ElementCore;

    class ElementDropDownField extends ElementCore
    {
        protected $_elementHtml = '<div class=":hasError"><select name=":id" class="form-control" id=":id">:options</select></div>';

        protected $_options = [];
        protected $_placeholder = NULL;
        protected $_topSplitKeys = [];
        protected $_bottomSplitKeys = [];
        protected $_labelsEqualsKeys = FALSE;
        protected $_sortByLabel = FALSE;
        protected $_sortByLabelDirection = 'asc';

        // ######################################

        /**
         * @return string
         */
        public function getElementOptionsHtml()
        {
            return '<option value=":value":selected>:label</option>';
        }

        // ######################################

        /**
         * @param $value
         * @param $label
         * @param $selectedValue
         *
         * @return mixed
         */
        protected function _renderElementOptionsHtml($value, $label, $selectedValue = FALSE)
        {
            $_tmpl = $this->getElementOptionsHtml();
            $_tmpl = $this->_replaceFieldPlaceholder('value', $value, $_tmpl);
            $_tmpl = $this->_replaceFieldPlaceholder('label', $label, $_tmpl);
            $_tmpl = $this->_replaceFieldPlaceholder('selected', $selectedValue === TRUE ? ' selected' : NULL, $_tmpl);

            return $_tmpl;
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
            if ($this->getLabelsEqualsKeys() === TRUE)
            {
                return $this->_setLabelsEqualsKeys($this->_options);
            }

            return $this->_options;
        }

        // ######################################

        /**
         * @param $label
         *
         * @return $this
         */
        public function setPlaceholder($label)
        {
            $this->_placeholder = $label;

            return $this;
        }

        // ######################################

        /**
         * @return null|string
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
            $coreFieldPlaceholders = parent::_getFieldPlaceholders();

            // add options
            $coreFieldPlaceholders['options'] = $this->_getRenderedOptions();

            return $coreFieldPlaceholders;
        }

        // ######################################

        /**
         * @param $optionSortByLabel
         * @param string $direction
         *
         * @return $this
         */
        public function setSortByLabel($optionSortByLabel, $direction = 'asc')
        {
            $this->_sortByLabel = $optionSortByLabel === TRUE ? TRUE : FALSE;

            if (in_array($direction, ['asc', 'desc']))
            {
                $this->_sortByLabelDirection = $direction;
            }

            return $this;
        }

        // ######################################

        /**
         * @return boolean
         */
        public function getSortByLabel()
        {
            return $this->_sortByLabel;
        }

        // ######################################

        /**
         * @return boolean
         */
        protected function _getSortByLabelDirection()
        {
            return $this->_sortByLabelDirection;
        }

        // ######################################

        /**
         * @param array $optionBottomSplitKeys
         *
         * @return ElementDropDownField
         */
        public function setBottomSplitKeys($optionBottomSplitKeys)
        {
            $this->_bottomSplitKeys = $optionBottomSplitKeys;

            return $this;
        }

        // ######################################

        /**
         * @return array
         */
        public function getBottomSplitKeys()
        {
            return $this->_bottomSplitKeys;
        }

        // ######################################

        /**
         * @param array $optionTopSplitKeys
         *
         * @return ElementDropDownField
         */
        public function setTopSplitKeys($optionTopSplitKeys)
        {
            $this->_topSplitKeys = $optionTopSplitKeys;

            return $this;
        }

        // ######################################

        /**
         * @return array
         */
        public function getTopSplitKeys()
        {
            return $this->_topSplitKeys;
        }

        // ######################################

        /**
         * @param boolean $optionValueEqualsLabel
         *
         * @return ElementDropDownField
         */
        public function setLabelsEqualsKeys($optionValueEqualsLabel)
        {
            $this->_labelsEqualsKeys = $optionValueEqualsLabel === TRUE ? TRUE : FALSE;

            return $this;
        }

        // ######################################

        /**
         * @return boolean
         */
        public function getLabelsEqualsKeys()
        {
            return $this->_labelsEqualsKeys;
        }

        // ######################################

        /**
         * @param $options
         *
         * @return array
         */
        protected function _setLabelsEqualsKeys($options)
        {
            $_newOptions = [];

            foreach ($options as $label)
            {
                $_newOptions[$label] = $label;
            }

            return $_newOptions;
        }

        // ######################################

        /**
         * @param array $renderedOptions
         *
         * @return array
         */
        protected function _renderPlaceholder(array $renderedOptions)
        {
            $optionLabel = $this->getPlaceholder();
            $currentSelectedValue = $this->getValue();

            if ($optionLabel !== NULL)
            {
                $isSelected = FALSE;

                if (empty($currentSelectedValue))
                {
                    $isSelected = TRUE;
                }

                array_unshift($renderedOptions, $this->_renderElementOptionsHtml('', $optionLabel, $isSelected));
            }

            return $renderedOptions;
        }

        // ######################################

        /**
         * @param array $options
         *
         * @return array
         */
        protected function _sortOptionsByLabel(array $options)
        {
            if ($this->getSortByLabel() === TRUE)
            {
                if ($this->_getSortByLabelDirection() === 'asc')
                {
                    asort($options);
                }
                else
                {
                    arsort($options);
                }
            }

            return $options;
        }

        // ######################################

        /**
         * @return string
         */
        protected function _getRenderedOptions()
        {
            $currentSelectedValue = $this->getValue();

            $renderedOptions = [];
            $options = $this->getOptions();

            // ----------------------------------

            // top split
            $topSplitKeys = $this->getTopSplitKeys();

            if (!empty($topSplitKeys))
            {
                // key comparision required
                $topSplitKeys = array_flip($topSplitKeys);

                // extract lists
                $topSplitOptions = array_intersect_key($options, $topSplitKeys);
                $options = array_diff_key($options, $topSplitKeys);

                // sort
                $topSplitOptions = $this->_sortOptionsByLabel($topSplitOptions);

                // render options
                if (!empty($topSplitOptions))
                {
                    foreach ($topSplitOptions as $value => $label)
                    {
                        $isSelected = FALSE;

                        if (!empty($currentSelectedValue) && $value == $currentSelectedValue)
                        {
                            $isSelected = TRUE;
                        }

                        $renderedOptions[] = $this->_renderElementOptionsHtml($value, $label, $isSelected);
                    }

                    $renderedOptions[] = $this->_renderElementOptionsHtml('-1', '----------');
                }
            }

            // ----------------------------------

            // sort
            $options = $this->_sortOptionsByLabel($options);

            // render options
            foreach ($options as $value => $label)
            {
                $isSelected = FALSE;

                if (!empty($currentSelectedValue) && $value == $currentSelectedValue)
                {
                    $isSelected = TRUE;
                }

                $renderedOptions[] = $this->_renderElementOptionsHtml($value, $label, $isSelected);
            }

            // ----------------------------------

            // bottom split
            $bottomSplitKeys = $this->getBottomSplitKeys();

            if (!empty($bottomSplitKeys))
            {
                // key comparision required
                $bottomSplitKeys = array_flip($bottomSplitKeys);

                // extract lists
                $bottomSplitOptions = array_intersect_key($options, $bottomSplitKeys);
                $options = array_diff_key($options, $bottomSplitKeys);

                // sort
                $bottomSplitOptions = $this->_sortOptionsByLabel($bottomSplitOptions);

                // render options
                if (!empty($bottomSplitOptions))
                {
                    $renderedOptions[] = $this->_renderElementOptionsHtml('-1', '----------');

                    foreach ($bottomSplitOptions as $value => $label)
                    {
                        $isSelected = FALSE;

                        if (!empty($currentSelectedValue) && $value == $currentSelectedValue)
                        {
                            $isSelected = TRUE;
                        }

                        $renderedOptions[] = $this->_renderElementOptionsHtml($value, $label, $isSelected);
                    }
                }
            }

            // ----------------------------------

            // render default label if defined
            $renderedOptions = $this->_renderPlaceholder($renderedOptions);

            return join("\n", $renderedOptions);
        }
    }