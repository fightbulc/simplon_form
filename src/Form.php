<?php

namespace Simplon\Form;

use Simplon\Form\Elements\Hidden\HiddenElement;
use Simplon\Form\Elements\InterfaceElement;

class Form
{
    protected $tmpl;
    protected $id = 'simplon-form';
    protected $url = '';
    protected $method = 'GET';
    protected $acceptCharset = 'utf-8';
    protected $enabledCsrf = true;
    protected $csrfSalt = 'x45%da08*(';

    /** @var InterfaceElement */
    protected $submitElement;

    /** @var InterfaceElement */
    protected $cancelElement;

    /** @var InterfaceElement[] */
    protected $elements = [];
    protected $invalidElements = [];
    protected $generalErrorMessage = '<strong>Oh snap!</strong> At least one field requires your attention. Have a look at the error notes below.';

    protected $jsCode = [];
    protected $followUps = [];

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

    /**
     * @param $use
     *
     * @return Form
     */
    public function enableCsrf($use)
    {
        $this->enabledCsrf = $use !== false ? true : false;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function hasEnabledCsrf()
    {
        return $this->enabledCsrf;
    }

    /**
     * @param mixed $generalErrorMessage
     *
     * @return Form
     */
    public function setGeneralErrorMessage($generalErrorMessage)
    {
        $this->generalErrorMessage = $generalErrorMessage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGeneralErrorMessage()
    {
        return $this->generalErrorMessage;
    }

    /**
     * @return mixed
     */
    protected function renderGeneralErrorMessage()
    {
        $template = '<div class="alert alert-danger">:value</div>';

        return str_replace(':value', $this->generalErrorMessage, $template);
    }

    /**
     * @param \Simplon\Form\Elements\InterfaceElement $cancelElement
     *
     * @return $this
     */
    public function setCancelElement(InterfaceElement $cancelElement)
    {
        $this->cancelElement = $cancelElement;

        return $this;
    }

    /**
     * @return InterfaceElement
     */
    public function getCancelElement()
    {
        return $this->cancelElement;
    }

    /**
     * @param \Simplon\Form\Elements\InterfaceElement $submitElement
     *
     * @return $this
     */
    public function setSubmitElement(InterfaceElement $submitElement)
    {
        $this->submitElement = $submitElement;

        return $this;
    }

    /**
     * @return \Simplon\Form\Elements\InterfaceElement
     */
    public function getSubmitElement()
    {
        return $this->submitElement;
    }

    /**
     * @param $id
     *
     * @return Form
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getId()
    {
        return $this->id;
    }

    /**
     * @param $url
     *
     * @return Form
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $charset
     *
     * @return Form
     */
    public function setCharset($charset)
    {
        $this->acceptCharset = $charset;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getCharset()
    {
        return $this->acceptCharset;
    }

    /**
     * @param $method
     *
     * @return Form
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getMethod()
    {
        return $this->method;
    }

    /**
     * @param $elements
     *
     * @return Form
     */
    public function setElements($elements)
    {
        $this->elements = $elements;

        return $this;
    }

    /**
     * @param InterfaceElement $elementInstance
     *
     * @return $this
     */
    public function addElement(InterfaceElement $elementInstance)
    {
        $this->elements[] = $elementInstance;

        return $this;
    }

    /**
     * @return array|InterfaceElement[]
     */
    protected function getElements()
    {
        return $this->elements;
    }

    /**
     * @return array
     */
    public function getElementValues()
    {
        $values = [];
        $elements = $this->getElements();

        foreach ($elements as $elm)
        {
            $values[$elm->getId()] = $this->getRequestValue($elm->getId());
        }

        return $values;
    }

    /**
     * @param array $jsCode
     *
     * @return $this
     */
    protected function setJsCode(array $jsCode)
    {
        if (!empty($jsCode))
        {
            $this->jsCode[] = join(';', $jsCode) . ';';
        }

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getJsCode()
    {
        return $this->jsCode;
    }

    /**
     * @return mixed
     */
    protected function hasJsCode()
    {
        return count($this->getJsCode()) > 0 ? true : false;
    }

    /**
     * @param $followUps
     *
     * @return Form
     */
    public function setFollowUps($followUps)
    {
        $this->followUps = $followUps;

        return $this;
    }

    /**
     * @return array
     */
    protected function getFollowUps()
    {
        return $this->followUps;
    }

    /**
     * @param $templatePath
     *
     * @return string
     */
    protected function fetchTemplate($templatePath)
    {
        return file_get_contents($templatePath);
    }

    /**
     * @param $templatePath
     *
     * @return $this
     */
    public function setTemplate($templatePath)
    {
        $this->tmpl = $this->fetchTemplate($templatePath);

        return $this;
    }

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return (string)$this->tmpl;
    }

    /**
     * @param $key
     *
     * @return bool|mixed
     */
    protected function getRequestValue($key)
    {
        if (!isset($_REQUEST[$key]))
        {
            return false;
        }

        return $_REQUEST[$key];
    }

    /**
     * @param $salt
     *
     * @return Form
     */
    public function setCsrfSalt($salt)
    {
        $this->csrfSalt = $salt;

        return $this;
    }

    /**
     * @return string
     */
    protected function getCsrfSalt()
    {
        return $this->csrfSalt;
    }

    /**
     * @return string
     */
    protected function getCsrfValue()
    {
        return md5(session_id() . $this->getCsrfSalt());
    }

    /**
     * @param InterfaceElement $elementInstance
     *
     * @return $this
     */
    protected function addInvalidElement(InterfaceElement $elementInstance)
    {
        $this->invalidElements[] = $elementInstance;

        return $this;
    }

    /**
     * @return array
     */
    protected function getInvalidElements()
    {
        return $this->invalidElements;
    }

    /**
     * @return bool
     */
    protected function hasInvalidElements()
    {
        $elms = $this->getInvalidElements();

        return empty($elms) === false;
    }

    /**
     * @return bool
     */
    protected function isSubmitted()
    {
        return $this->getRequestValue('csrf') === $this->getCsrfValue();
    }

    /**
     * @param $elementId
     * @param array $placeholderValues
     */
    protected function replaceTemplatePlaceholder($elementId, array $placeholderValues)
    {
        foreach ($placeholderValues as $placeholder => $value)
        {
            $key = $elementId . ':' . $placeholder;

            if ($value !== false)
            {
                preg_match('|({{#' . $key . '}}.*?{{/' . $key . '}})\s*|smi', $this->tmpl, $matched);

                if ($matched)
                {
                    $container = str_replace('{{value}}', $value, $matched[1]);
                    $container = $this->cleanPlaceholders($container);
                    $this->tmpl = preg_replace('#' . preg_quote($matched[1], '#') . '\s*#', $container, $this->tmpl);
                }
            }
        }
    }

    /**
     * @return bool
     */
    protected function setCsrfElement()
    {
        if ($this->hasEnabledCsrf())
        {
            $csrfValue = $this->getCsrfValue();

            // create element
            $elementHiddenField = (new HiddenElement())
                ->setId('csrf')
                ->setValue($csrfValue);

            // set element
            $this->addElement($elementHiddenField);

            // set in template
            $this->tmpl = str_replace('{{#form:open}}', '{{#form:open}}{{#csrf:element}}{{value}}{{/csrf:element}}', $this->tmpl);

            return true;
        }

        return false;
    }

    /**
     * Set Form open/close tags
     */
    protected function setFormTags()
    {
        $formOpen = '<form :attributes>';
        $formClose = '</form>';

        // set attributes
        $attributes = [
            'role'           => 'form',
            'id'             => $this->getId(),
            'action'         => $this->getUrl(),
            'method'         => $this->getMethod(),
            'accept-charset' => $this->getCharset(),
            'enctype'        => 'multipart/form-data',
        ];

        // set values
        $renderedAttributes = [];

        foreach ($attributes as $key => $value)
        {
            $renderedAttributes[] = $key . '="' . $value . '"';
        }

        $formOpen = str_replace(':attributes', join(' ', $renderedAttributes), $formOpen);

        // set form open
        $this->tmpl = str_replace('{{#form:open}}', $formOpen, $this->getTemplate());

        // set form close
        $this->tmpl = str_replace('{{/form:open}}', $formClose, $this->getTemplate());

        // ----------------------------------

        // set form related elements
        $placeholders = [];

        // generic error indication
        if ($this->hasInvalidElements() === true)
        {
            $placeholders['hasError'] = $this->renderGeneralErrorMessage();
        }

        // form:submit
        if ($this->getSubmitElement())
        {
            $placeholders['submit'] = $this->getSubmitElement()->render()['element'];
        }

        // form:cancel
        if ($this->getCancelElement())
        {
            $placeholders['cancel'] = $this->getCancelElement()->render()['element'];
        }

        // form:js
        if ($this->hasJsCode())
        {
            $placeholders['js'] = '<script>$(function(){' . "\n" . join("\n", $this->getJsCode()) . "\n" . '});</script> ';
        }

        $this->replaceTemplatePlaceholder('form', $placeholders);
    }

    /**
     * @param $tmpl
     *
     * @return string
     */
    protected function cleanPlaceholders($tmpl)
    {
        return (string)preg_replace('#{{\#*/*[\w\d]+:[\w\d]+}}\s*#sm', '', $tmpl);
    }

    /**
     *
     */
    protected function cleanTemplate()
    {
        $this->tmpl = (string)preg_replace('#({{\#([\w\d]+:[\w\d]+)}}.*?{{/\\2}}|{{\#[\w\d]+:[\w\d]+}})\s*#smi', '', $this->tmpl);
    }

    /**
     * @return array
     */
    protected function getAllElementsValues()
    {
        $keyValuePairs = array();

        foreach ($this->getElements() as $elementClass)
        {
            $keyValuePairs[$elementClass->getId()] = $elementClass->getValue();
        }

        return $keyValuePairs;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if ($this->isSubmitted())
        {
            // iterate through all elements
            foreach ($this->getElements() as $element)
            {
                // fill element with submitted value
                $requestValue = $this->getRequestValue($element->getId());

                // set post value
                $element->setPostValue($requestValue);

                // run through element rules
                $element->validateRules();

                // if element is invalid
                if ($element->isValid() !== true)
                {
                    // cache invalid elements
                    $this->addInvalidElement($element);

                    // visual error indication
                    $element->setElementHtml(str_replace(':hasError', 'has-error', $element->getElementHtml()));
                }
                
                // element is valid
                elseif ($element->isValid() === true)
                {
                    // visual error indication
                    $element->setElementHtml(str_replace(':hasError', 'has-success', $element->getElementHtml()));
                }
            }

            // no validation errors
            if ($this->hasInvalidElements() === false)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function render()
    {
        // include CSRF field if enabled
        $this->setCsrfElement();

        // set elements
        foreach ($this->getElements() as $element)
        {
            if ($element->isValid() === false)
            {
                $this->replaceTemplatePlaceholder($element->getId(), ['error' => $element->renderErrorMessages()]);
            }

            $this->replaceTemplatePlaceholder($element->getId(), $element->render());

            $this->setJsCode($element->getJs());
        }

        // set form open/close tag
        $this->setFormTags();

        // clean left overs
        $this->cleanTemplate();

        // return finished template
        return $this->getTemplate();
    }

    /**
     * @param callable $closure
     *
     * @return $this
     */
    public function addFollowUp(\Closure $closure)
    {
        $this->followUps[] = $closure;

        return $this;
    }

    /**
     * @return bool
     */
    public function runFollowUps()
    {
        $followUps = $this->getFollowUps();

        if (!empty($followUps))
        {
            foreach ($followUps as $closure)
            {
                $closure($this->getElementValues());
            }
        }
    }
}
