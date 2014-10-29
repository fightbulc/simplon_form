<?php

namespace Simplon\Form;

use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Elements\Hidden\HiddenElement;

class Form
{
    /**
     * @var array
     */
    protected $requestData = [];

    /**
     * @var string
     */
    protected $tmpl;

    /**
     * @var string
     */
    protected $id = 'simplon-form';

    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var string
     */
    protected $method = 'POST';

    /**
     * @var string
     */
    protected $acceptCharset = 'utf-8';

    /**
     * @var bool
     */
    protected $enabledCsrf = true;

    /**
     * @var string
     */
    protected $csrfSalt = ')UsZQjxm8ka}bwh7cYvnjT';

    /**
     * @var CoreElementInterface[]
     */
    protected $elements = [];

    /**
     * @var CoreElementInterface[]
     */
    protected $invalidElements = [];

    /**
     * @var string
     */
    protected $generalErrorMessage = '<strong>Validation failed.</strong> Have a look at the error notes below.';

    /**
     * @var array
     */
    protected $assetFiles = [];

    /**
     * @var array
     */
    protected $assetInlines = [];

    /**
     * @var array
     */
    protected $followUps = [];

    /**
     * @var null|bool
     */
    protected $validationResult = null;

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
     * @param null|string $key
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
     * @param bool $use
     *
     * @return Form
     */
    public function enableCsrf($use)
    {
        $this->enabledCsrf = $use !== false ? true : false;

        return $this;
    }

    /**
     * @return bool
     */
    protected function hasEnabledCsrf()
    {
        return (bool)$this->enabledCsrf;
    }

    /**
     * @param string $generalErrorMessage
     *
     * @return Form
     */
    public function setGeneralErrorMessage($generalErrorMessage)
    {
        $this->generalErrorMessage = $generalErrorMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getGeneralErrorMessage()
    {
        return (string)$this->generalErrorMessage;
    }

    /**
     * @return string
     */
    protected function renderGeneralErrorMessage()
    {
        $template = '<div class="alert alert-danger">:value</div>';

        return (string)str_replace(':value', $this->generalErrorMessage, $template);
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
     * @return string
     */
    protected function getId()
    {
        return (string)$this->id;
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
     * @return string
     */
    protected function getUrl()
    {
        return (string)$this->url;
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
     * @return string
     */
    protected function getCharset()
    {
        return (string)$this->acceptCharset;
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
     * @return string
     */
    protected function getMethod()
    {
        return (string)$this->method;
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
     * @param CoreElementInterface $elementInstance
     *
     * @return $this
     */
    public function addElement(CoreElementInterface $elementInstance)
    {
        $this->elements[] = $elementInstance;

        return $this;
    }

    /**
     * @return array|CoreElementInterface[]
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

        foreach ($elements as $element)
        {
            $values[$element->getId()] = $element->getValue();
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
     * @param array $assetInlines
     *
     * @return Form
     */
    protected function setAssetInlines(array $assetInlines)
    {
        foreach ($assetInlines as $inline)
        {
            $this->assetInlines[] = $inline;
        }

        return $this;
    }

    /**
     * @return array
     */
    protected function getAssetInlines()
    {
        return $this->assetInlines;
    }

    /**
     * @return bool
     */
    protected function hasAssetInlines()
    {
        return count($this->getAssetInlines()) > 0 ? true : false;
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
     * @param CoreElementInterface $elementInstance
     *
     * @return $this
     */
    protected function addInvalidElement(CoreElementInterface $elementInstance)
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
        return
            $this->hasRequestData() === true && // any request data at all?
            (int)$this->getRequestData('hide-' . $this->getId()) === 1; // has this form been submitted?
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
                preg_match('|({{#' . $key . '}}.*?{{/' . $key . '}})\s*|sm', $this->tmpl, $matched);

                if ($matched)
                {
                    $container = str_replace('{{value}}', $value, $matched[1]);
                    $container = $this->cleanPlaceholders($container);
                    $this->tmpl = preg_replace('#' . preg_quote($matched[1], '#') . '\s*#', $container, $this->tmpl);
                }
            }
        }

        // remove unused element containers
        $this->tmpl = preg_replace('/{{\#' . $elementId . ':.*?}}.*?{{\/' . $elementId . ':.*?}}/smu', '', $this->tmpl);
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
                ->setId('hide-csrf')
                ->setValue($csrfValue);

            // set element
            $this->addElement($elementHiddenField);

            // set in template
            $this->tmpl = str_replace('{{#form:open}}', '{{#form:open}}{{#hide-csrf:element}}{{value}}{{/hide-csrf:element}}', $this->tmpl);

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

        // set hidden form name
        $formOpen .= '<input type="hidden" name="hide-' . $this->getId() . '" value="1">';

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
        // TODO: do it better
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

        if ($this->hasAssetInlines())
        {
            $this->tmpl = str_replace('{{form:assets:inline}}', "<script>document.addEventListener('DOMContentLoaded', function() { setTimeout(function() { " . join(";\n", $this->getAssetInlines()) . " }, 500); });</script>", $this->tmpl);
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
        return (string)preg_replace('|{{(?!lang:).*?}}\s*|smu', '', $tmpl);
    }

    /**
     *
     */
    protected function cleanTemplate()
    {
        $this->tmpl = (string)preg_replace('#({{(?!lang:).*?}}|:hasError)\s*#smu', '', $this->tmpl);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if ($this->isSubmitted() && $this->validationResult === null)
        {
            // iterate through all elements
            foreach ($this->getElements() as $element)
            {
                // fill element with submitted value
                $requestValue = $this->getRequestData($element->getId());

                // set post value
                $element->setPostValue($requestValue);

                // run through element rules
                $element->processFilters();

                // run through element rules
                $element->processRules();

                // if element is invalid
                if ($element->isValid() === false)
                {
                    // visual error indication
                    $element->setElementHtml(str_replace(':hasError', 'has-error', $element->getElementHtml()));

                    // cache invalid elements
                    $this->addInvalidElement($element);
                }

                // element is valid
                elseif ($element->isValid() === true)
                {
                    // visual error indication
                    $element->setElementHtml(str_replace(':hasError', 'has-success', $element->getElementHtml()));
                }
            }

            // cache validation result
            $this->validationResult = $this->hasInvalidElements() === false;
        }

        return $this->validationResult;
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
            $elementParts = $element->render();

            if ($element->isValid() === false)
            {
                $elementParts['error'] = $element->renderErrorMessages();
            }

            $this->replaceTemplatePlaceholder($element->getId(), $elementParts);

            $this->setAssetFiles($element->getAssetFiles());
            $this->setAssetInlines($element->getAssetInlines());
        }

        // set form open/close tag
        $this->setFormTags();

        // clean left overs
        $this->cleanTemplate();

        // return finished template
        return $this->getTemplate();
    }
}