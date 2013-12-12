<?php

    namespace Simplon\Form;

    use Simplon\Form\Elements\Core\ElementInterface;
    use Simplon\Form\Elements\ElementCheckboxField;
    use Simplon\Form\Elements\ElementHiddenField;

    class Form
    {
        protected $_tmpl;
        protected $_id = 'simplon-form';
        protected $_url = '';
        protected $_method = 'GET';
        protected $_acceptCharset = 'utf-8';
        protected $_enabledCsrf = TRUE;
        protected $_csrfSalt = 'x45%da08*(';

        /** @var ElementInterface */
        protected $_submitElement;

        /** @var ElementInterface */
        protected $_cancelElement;

        /** @var ElementInterface[] */
        protected $_elements = [];
        protected $_invalidElements = [];
        protected $_generalErrorMessage = '<strong>Oh snap!</strong> At least one field requires your attention. Have a look at the error notes below.';

        protected $_jsCode = [];
        protected $_followUps = [];

        // ##########################################

        public function __construct()
        {
            // start session for csrf
            if (!session_id())
            {
                session_start();
            }

            // kick cookies
            $_REQUEST = array_merge($_GET, $_POST);
        }

        // ##########################################

        /**
         * @param $use
         *
         * @return Form
         */
        public function enableCsrf($use)
        {
            $this->_enabledCsrf = $use !== FALSE ? TRUE : FALSE;

            return $this;
        }

        // ##########################################

        /**
         * @return mixed
         */
        protected function _hasEnabledCsrf()
        {
            return $this->_enabledCsrf;
        }

        // ##########################################

        /**
         * @param mixed $generalErrorMessage
         *
         * @return Form
         */
        public function setGeneralErrorMessage($generalErrorMessage)
        {
            $this->_generalErrorMessage = $generalErrorMessage;

            return $this;
        }

        // ##########################################

        /**
         * @return mixed
         */
        public function getGeneralErrorMessage()
        {
            return $this->_generalErrorMessage;
        }

        // ##########################################

        /**
         * @return mixed
         */
        protected function _renderGeneralErrorMessage()
        {
            $template = '<div class="alert alert-danger">:message</div>';

            return str_replace(':message', $this->_generalErrorMessage, $template);
        }

        // ##########################################

        /**
         * @param ElementInterface $cancelElement
         *
         * @return $this
         */
        public function setCancelElement(ElementInterface $cancelElement)
        {
            $this->_cancelElement = $cancelElement;

            return $this;
        }

        // ##########################################

        /**
         * @return ElementInterface
         */
        public function getCancelElement()
        {
            return $this->_cancelElement;
        }

        // ##########################################

        /**
         * @param ElementInterface $submitElement
         *
         * @return $this
         */
        public function setSubmitElement(ElementInterface $submitElement)
        {
            $this->_submitElement = $submitElement;

            return $this;
        }

        // ##########################################

        /**
         * @return ElementInterface
         */
        public function getSubmitElement()
        {
            return $this->_submitElement;
        }

        // ##########################################

        /**
         * @param $id
         *
         * @return Form
         */
        public function setId($id)
        {
            $this->_id = $id;

            return $this;
        }

        // ##########################################

        /**
         * @return mixed
         */
        protected function _getId()
        {
            return $this->_id;
        }

        // ##########################################

        /**
         * @param $url
         *
         * @return Form
         */
        public function setUrl($url)
        {
            $this->_url = $url;

            return $this;
        }

        // ##########################################

        /**
         * @return mixed
         */
        protected function _getUrl()
        {
            return $this->_url;
        }

        // ##########################################

        /**
         * @param $charset
         *
         * @return Form
         */
        public function setCharset($charset)
        {
            $this->_acceptCharset = $charset;

            return $this;
        }

        // ##########################################

        /**
         * @return mixed
         */
        protected function _getCharset()
        {
            return $this->_acceptCharset;
        }

        // ##########################################

        /**
         * @param $method
         *
         * @return Form
         */
        public function setMethod($method)
        {
            $this->_method = $method;

            return $this;
        }

        // ##########################################

        /**
         * @return mixed
         */
        protected function _getMethod()
        {
            return $this->_method;
        }

        // ##########################################

        /**
         * @param $elements
         *
         * @return Form
         */
        public function setElements($elements)
        {
            $this->_elements = $elements;

            return $this;
        }

        // ##########################################

        /**
         * @param ElementInterface $elementInstance
         *
         * @return $this
         */
        public function addElement(ElementInterface $elementInstance)
        {
            $this->_elements[] = $elementInstance;

            return $this;
        }

        // ##########################################

        /**
         * @return array|Elements\Core\ElementInterface[]
         */
        protected function _getElements()
        {
            return $this->_elements;
        }

        // ##########################################

        /**
         * @return array
         */
        public function getElementValues()
        {
            $values = [];
            $elements = $this->_getElements();

            foreach ($elements as $elm)
            {
                $values[$elm->getId()] = $this->_getRequestValue($elm->getId());
            }

            return $values;
        }

        // ##########################################

        /**
         * @param array $jsCode
         *
         * @return $this
         */
        protected function _setJsCode(array $jsCode)
        {
            if (!empty($jsCode))
            {
                $this->_jsCode[] = join(';', $jsCode) . ';';
            }

            return $this;
        }

        // ##########################################

        /**
         * @return mixed
         */
        protected function _getJsCode()
        {
            return $this->_jsCode;
        }

        // ##########################################

        /**
         * @return mixed
         */
        protected function _hasJsCode()
        {
            return count($this->_getJsCode()) > 0 ? TRUE : FALSE;
        }

        // ##########################################

        /**
         * @param $followUps
         *
         * @return Form
         */
        public function setFollowUps($followUps)
        {
            $this->_followUps = $followUps;

            return $this;
        }

        // ##########################################

        /**
         * @return array
         */
        protected function _getFollowUps()
        {
            return $this->_followUps;
        }

        // ##########################################

        protected function _fetchTemplate($templatePath)
        {
            return join('', file($templatePath));
        }

        // ##########################################

        public function setTemplate($templatePath)
        {
            $this->_tmpl = $this->_fetchTemplate($templatePath);

            return $this;
        }

        // ##########################################

        protected function _getTemplate()
        {
            return $this->_tmpl;
        }

        // ##########################################

        protected function _getRequestValue($key)
        {
            if (!isset($_REQUEST[$key]))
            {
                return FALSE;
            }

            return $_REQUEST[$key];
        }

        // ##########################################

        /**
         * @param $salt
         *
         * @return Form
         */
        public function setCsrfSalt($salt)
        {
            $this->_csrfSalt = $salt;

            return $this;
        }

        // ##########################################

        /**
         * @return string
         */
        protected function _getCsrfSalt()
        {
            return $this->_csrfSalt;
        }

        // ##########################################

        /**
         * @return string
         */
        protected function _getCsrfValue()
        {
            return md5(session_id() . $this->_getCsrfSalt());
        }

        // ##########################################

        /**
         * @param ElementInterface $elementInstance
         *
         * @return $this
         */
        protected function _addInvalidElement(ElementInterface $elementInstance)
        {
            $this->_invalidElements[] = $elementInstance;

            return $this;
        }

        // ##########################################

        /**
         * @return array
         */
        protected function _getInvalidElements()
        {
            return $this->_invalidElements;
        }

        // ##########################################

        /**
         * @return bool
         */
        protected function _hasInvalidElements()
        {
            $elms = $this->_getInvalidElements();

            return empty($elms) ? FALSE : TRUE;
        }

        // ##########################################

        /**
         * @return bool
         */
        protected function _isSubmitted()
        {
            return $this->_getRequestValue('csrf') === $this->_getCsrfValue();
        }

        // ##########################################

        /**
         * @param $elementId
         * @param array $placeholderValues
         */
        protected function _replaceTemplatePlaceholder($elementId, array $placeholderValues)
        {
            foreach ($placeholderValues as $placeholder => $value)
            {
                $key = $elementId . ':' . $placeholder;

                if ($value !== FALSE)
                {
                    preg_match('#(<' . $key . '>.*?</' . $key . '>)\s*#smi', $this->_tmpl, $matched);

                    if ($matched)
                    {
                        $container = str_replace('<value>', $value, $matched[1]);
                        $container = $this->_cleanPlaceholders($container);
                        $this->_tmpl = preg_replace('#' . $matched[1] . '\s*#', $container, $this->_tmpl);
                    }
                }
            }
        }

        // ##########################################

        /**
         * @return bool
         */
        protected function _setCsrfElement()
        {
            if ($this->_hasEnabledCsrf())
            {
                $csrfValue = $this->_getCsrfValue();

                // create element
                $elementHiddenField = (new ElementHiddenField())
                    ->setId('csrf')
                    ->setValue($csrfValue);

                // set element
                $this->addElement($elementHiddenField);

                // set in template
                $this->_tmpl = str_replace('<form:open>', '<form:open><csrf:element><value></csrf:element>', $this->_tmpl);

                return TRUE;
            }

            return FALSE;
        }

        // ##########################################

        /**
         * Set Form open/close tags
         */
        protected function _setFormTags()
        {
            $formOpen = '<form :attributes>';
            $formClose = '</form>';

            // set attributes
            $attributes = [
                'role'           => 'form',
                'id'             => $this->_getId(),
                'action'         => $this->_getUrl(),
                'method'         => $this->_getMethod(),
                'accept-charset' => $this->_getCharset(),
                'enctype'        => 'multipart/form-data',
            ];

            // set values
            $_renderedAttributes = [];

            foreach ($attributes as $key => $value)
            {
                $_renderedAttributes[] = $key . '="' . $value . '"';
            }

            $formOpen = str_replace(':attributes', join(' ', $_renderedAttributes), $formOpen);

            // set form open
            $this->_tmpl = str_replace('<form:open>', $formOpen, $this->_tmpl);

            // set form close
            $this->_tmpl = str_replace('</form:open>', $formClose, $this->_tmpl);

            // ----------------------------------

            // set form related elements
            $placeholders = [];

            // generic error indication
            if ($this->_hasInvalidElements() === TRUE)
            {
                $placeholders['hasError'] = $this->_renderGeneralErrorMessage();
            }

            // form:submit
            if ($this->getSubmitElement())
            {
                $placeholders['submit'] = $this->getSubmitElement()
                    ->render()['element'];
            }

            // form:cancel
            if ($this->getCancelElement())
            {
                $placeholders['cancel'] = $this->getCancelElement()
                    ->render()['element'];
            }

            // form:js
            if ($this->_hasJsCode())
            {
                $placeholders['js'] = '<script>$(function(){' . "\n" . join("\n", $this->_getJsCode()) . "\n" . '});</script> ';
            }

            $this->_replaceTemplatePlaceholder('form', $placeholders);
        }

        // ##########################################

        /**
         * Remove placeholders from given template
         */
        protected function _cleanPlaceholders($tmpl)
        {
            return preg_replace('#</*[\w\d]+:[\w\d]+>\s*#sm', '', $tmpl);
        }

        // ##########################################

        /**
         * Remove all left placeholders from template
         */
        protected function _cleanTemplate()
        {
            $this->_tmpl = preg_replace('#(<([\w\d]+:[\w\d]+)>.*?</\\2>|<[\w\d]+:[\w\d]+>)\s*#smi', '', $this->_tmpl);
        }

        // ##########################################

        /**
         * @return array
         */
        protected function _getAllElementsValues()
        {
            $keyValuePairs = array();

            foreach ($this->_getElements() as $elementClass)
            {
                $keyValuePairs[$elementClass->getId()] = $elementClass->getValue();
            }

            return $keyValuePairs;
        }

        // ##########################################

        /**
         * @return bool
         */
        public function isValid()
        {
            if ($this->_isSubmitted())
            {
                // iterate through all elements
                foreach ($this->_getElements() as $element)
                {
                    // fill element with submitted value
                    $_requestValue = $this->_getRequestValue($element->getId());

                    // --------------------------

                    // ignore preselections after submitting
                    if ($element instanceof ElementCheckboxField)
                    {
                        $element->ignorePreselectedOptions();
                    }

                    // --------------------------

                    // set new value or check field
                    if ($_requestValue !== FALSE)
                    {
                        if ($element instanceof ElementCheckboxField)
                        {
                            if (is_array($_requestValue))
                            {
                                foreach ($_requestValue as $key)
                                {
                                    $element->setOptionChecked($key, TRUE);
                                }
                            }
                        }
                        else
                        {
                            $element->setValue($_requestValue);
                        }
                    }

                    // --------------------------

                    // run through element rules
                    $element->validateRules();

                    // is element valid
                    if ($element->isValid() !== TRUE)
                    {
                        // cache invalid elements
                        $this->_addInvalidElement($element);

                        // visual error indication
                        $element->setElementHtml(str_replace(':hasError', 'has-error', $element->getElementHtml()));
                    }
                }

                // no validation errors
                if ($this->_hasInvalidElements() === FALSE)
                {
                    return TRUE;
                }
            }

            return FALSE;
        }

        // ##########################################

        /**
         * @return string
         */
        public function render()
        {
            // include CSRF field if enabled
            $this->_setCsrfElement();

            // set elements
            foreach ($this->_getElements() as $element)
            {
                if ($element->isValid() === FALSE)
                {
                    $this->_replaceTemplatePlaceholder($element->getId(), ['error' => $element->renderErrorMessages()]);
                }

                $this->_replaceTemplatePlaceholder($element->getId(), $element->render());

                $this->_setJsCode($element->getJs());
            }

            // set form open/close tag
            $this->_setFormTags();

            // clean left overs
            $this->_cleanTemplate();

            // return finished template
            return (string)$this->_getTemplate();
        }

        // ##########################################

        /**
         * @param callable $closure
         *
         * @return $this
         */
        public function addFollowUp(\Closure $closure)
        {
            $this->_followUps[] = $closure;

            return $this;
        }

        // ##########################################

        /**
         * @return bool
         */
        public function runFollowUps()
        {
            $followUps = $this->_getFollowUps();

            if (!empty($followUps))
            {
                foreach ($followUps as $closure)
                {
                    $closure($this->getElementValues());
                }
            }
        }
    }
