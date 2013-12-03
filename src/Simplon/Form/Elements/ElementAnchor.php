<?php

    namespace Simplon\Form\Elements;

    use Simplon\Form\Elements\Core\ElementCore;

    class ElementAnchor extends ElementCore
    {
        protected $_elementHtml = '<a href=":url">:label</a>';
        protected $_url;

        // ######################################

        /**
         * @return array
         */
        protected function _getFieldPlaceholders()
        {
            $coreFieldPlaceholders = parent::_getFieldPlaceholders();

            // add options
            $coreFieldPlaceholders['url'] = $this->getUrl();

            return $coreFieldPlaceholders;
        }

        // ######################################

        /**
         * @param mixed $url
         *
         * @return ElementAnchor
         */
        public function setUrl($url)
        {
            $this->_url = $url;

            return $this;
        }

        // ######################################

        /**
         * @return mixed
         */
        public function getUrl()
        {
            return $this->_url;
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