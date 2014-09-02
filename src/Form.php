<?php

namespace Simplon\Form;

use Simplon\Form\Elements\Hidden\HiddenElement;
use Simplon\Form\Elements\InterfaceElement;

class Form
{
    /** @var  array */
    protected $requestData;

    protected $tmpl;
    protected $id = 'simplon-form';
    protected $url = '';
    protected $method = 'POST';
    protected $acceptCharset = 'utf-8';
    protected $enabledCsrf = true;
    protected $csrfSalt = 'x45%da08*(';

    /** @var InterfaceElement[] */
    protected $elements = [];
    protected $invalidElements = [];
    protected $generalErrorMessage = '<strong>Oh snap!</strong> At least one field requires your attention. Have a look at the error notes below.';

    protected $assetFiles = [];
    protected $followUps = [];

    /**
     * @param array $requestData
     */
    public function __construct($requestData = [])
    {
        // start session for csrf
        if (!session_id())
        {
            session_start();
        }

        // set data
        $this->setRequestData($requestData);
    }

    /**
     * @param null $key
     *
     * @return array|bool
     */
    public function getRequestData($key = null)
    {
        if ($key !== null)
        {
            if (isset($this->requestData[$key]) === true)
            {
                return $this->requestData[$key];
            }

            return false;
        }

        return (array)$this->requestData;
    }

    /**
     * @return bool
     */
    protected function hasRequestData()
    {
        return empty($this->requestData) === false;
    }

    /**
     * @param array $requestData
     *
     * @return Form
     */
    public function setRequestData(array $requestData)
    {
        $this->requestData = $requestData;

        return $this;
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
            $values[$elm->getId()] = $this->getRequestData($elm->getId());
        }

        return $values;
    }

    /**
     * @param array $assetFiles
     *
     * @return $this
     */
    protected function setAssetFiles(array $assetFiles)
    {
        foreach ($assetFiles as $file)
        {
            $this->assetFiles[] = $file;
        }

        return $this;
    }

    /**
     * @return array
     */
    protected function getAssetFiles()
    {
        return $this->assetFiles;
    }

    /**
     * @return bool
     */
    protected function hasAssetFiles()
    {
        return count($this->getAssetFiles()) > 0 ? true : false;
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
     * @param $id
     * @param callable $closure
     *
     * @return Form
     */
    public function addFollowUp($id, \Closure $closure)
    {
        $this->followUps[$id] = $closure;

        return $this;
    }

    /**
     * @return array
     */
    public function runFollowUps()
    {
        $responses = [];
        $followUps = $this->getFollowUps();

        if (!empty($followUps))
        {
            foreach ($followUps as $id => $closure)
            {
                $responses[$id] = $closure($this->getElementValues());
            }
        }

        return $responses;
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
        return $this->hasRequestData() === true;
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

        // set form js
        // TODO do it better
        if ($this->hasAssetFiles())
        {
            $jsFiles = [];

            foreach ($this->getAssetFiles() as $file)
            {
                if (strpos($file, '.js') !== false)
                {
                    $jsFiles[] = '"' . $file . '"';
                }
                else
                {
                    $renderedAssets[] = '<link rel="stylesheet" href="' . $file . '">';
                }
            }

            $renderedAssets[] = '<script>!function(e,t,r){function n(){for(;d[0]&&"loaded"==d[0][f];)c=d.shift(),c[o]=!i.parentNode.insertAfter(c,i)}for(var s,a,c,d=[],i=e.scripts[0],o="onreadystatechange",f="readyState";s=r.shift();)a=e.createElement(t),"async"in i?(a.async=!1,e.body.appendChild(a)):i[f]?(d.push(a),a[o]=n):e.write("<"+t+\' src="\'+s+\'" defer></\'+t+">"),a.src=s}(document, "script", [' . join(',', $jsFiles) . '])</script>';
            $this->tmpl = str_replace('{{#form:open}}', "{{#form:open}}\n" . join("\n", $renderedAssets) . "\n", $this->tmpl);
        }

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

        $this->replaceTemplatePlaceholder('form', $placeholders);
    }

    /**
     * @param $tmpl
     *
     * @return string
     */
    protected function cleanPlaceholders($tmpl)
    {
        return (string)preg_replace('|{{.*?}}\s*|sm', '', $tmpl);
    }

    /**
     *
     */
    protected function cleanTemplate()
    {
        $this->tmpl = (string)preg_replace('#({{.*?}})\s*#smi', '', $this->tmpl);
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
                $requestValue = $this->getRequestData($element->getId());

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
     * @param null $pathTemplate
     *
     * @return string
     */
    public function render($pathTemplate = null)
    {
        // set template
        if ($pathTemplate !== null)
        {
            $this->setTemplate($pathTemplate);
        }

        // include CSRF field if enabled
        $this->setCsrfElement();

        // set elements
        foreach ($this->getElements() as $element)
        {
            if ($element->isValid() === false)
            {
                $this->replaceTemplatePlaceholder(
                    $element->getId(),
                    [
                        'error' => $element->renderErrorMessages()
                    ]
                );
            }

            $this->replaceTemplatePlaceholder($element->getId(), $element->render());

            $this->setAssetFiles($element->getAssetFiles());
        }

        // set form open/close tag
        $this->setFormTags();

        // clean left overs
        $this->cleanTemplate();

        // return finished template
        return $this->getTemplate();
    }
}
