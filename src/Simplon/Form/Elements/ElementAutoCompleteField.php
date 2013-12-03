<?php

    namespace Simplon\Form\Elements;

    use Simplon\Form\Elements\Core\ElementCore;

    class ElementAutoCompleteField extends ElementCore
    {
        protected $_elementHtml = '<div class=":hasError"><input type="text" class="form-control" name=":id" id=":id" value=":value" placeholder=":placeholder" autocomplete="off"></div>';
        protected $_placeholder;
        protected $_resultTemplate;
        protected $_selectedTemplate;

        // ######################################

        /**
         * @param mixed $resultTemplate
         *
         * @return ElementAutoCompleteField
         */
        public function setResultTemplate($resultTemplate)
        {
            $this->_resultTemplate = $resultTemplate;

            return $this;
        }

        // ######################################

        /**
         * @return mixed
         */
        public function getResultTemplate()
        {
            return preg_replace('/\n+/', '', $this->_resultTemplate);
        }

        // ######################################

        /**
         * @param mixed $selectedTemplate
         *
         * @return ElementAutoCompleteField
         */
        public function setSelectedTemplate($selectedTemplate)
        {
            $this->_selectedTemplate = $selectedTemplate;

            return $this;
        }

        // ######################################

        /**
         * @return mixed
         */
        public function getSelectedTemplate()
        {
            return preg_replace('/\n+/', '', $this->_selectedTemplate);
        }

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

        // ######################################

        /**
         * @return array
         */
        public function render()
        {
            $this->addJs("console.log('loaded')");
            $this->addJs("var p = $('#{$this->getId()}').remoteComplete({resultTemplate:'{$this->getResultTemplate()}', selectedTemplate:'{$this->getSelectedTemplate()}'})");

            if (isset($_POST['city_results']))
            {
                $data = $_POST['city_results'][0];

                $this->addJs("p.init([JSON.parse('" . json_encode(json_decode($data, TRUE)) . "')])");
            }

            return parent::render();
        }


    }