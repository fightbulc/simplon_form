<?php

namespace Simplon\Form;

use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Elements\Hidden\HiddenElement;

class Form
{
    /**
     * @var array
     */
    private $requestData = [];

    /**
     * @var string
     */
    private $tmpl;

    /**
     * @var string
     */
    private $id = 'simplon-form';

    /**
     * @var string
     */
    private $url = '';

    /**
     * @var string
     */
    private $method = 'POST';

    /**
     * @var string
     */
    private $acceptCharset = 'utf-8';

    /**
     * @var bool
     */
    private $enabledCsrf = true;

    /**
     * @var string
     */
    private $csrfSalt = ')UsZQjxm8ka}bwh7cYvnjT';

    /**
     * @var CoreElementInterface[]
     */
    private $elements = [];

    /**
     * @var CoreElementInterface[]
     */
    private $invalidElements = [];

    /**
     * @var string
     */
    private $generalErrorMessage = '<strong>Validation failed.</strong> Have a look at the error notes below.';

    /**
     * @var array
     */
    private $assetFiles = [];

    /**
     * @var array
     */
    private $assetInlines = [];

    /**
     * @var array
     */
    private $followUps = [];

    /**
     * @var null|bool
     */
    private $isValid = null;

    /**
     * @var string
     */
    private $urlRootAssets;

    /**
     * @param array $requestData
     */
    public function __construct($requestData = [])
    {
        // set base assets
        $this->addAssetFiles([
            'bootstrap-3.3.1/css/bootstrap.css',
            'default.css',
            'jquery-2.1.1/jquery-2.1.1.min.js',
            'bootstrap-3.3.1/js/bootstrap.min.js',
        ]);

        // start session for csrf
        if (!session_id())
        {
            session_start();
        }

        // set data
        $this->setRequestData($requestData);
    }

    /**
     * @param string $urlRootAssets
     *
     * @return Form
     */
    public function setUrlRootAssets($urlRootAssets)
    {
        $this->urlRootAssets = rtrim($urlRootAssets, '/');

        return $this;
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
    public function renderGeneralErrorMessage()
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
     * @return array|CoreElementInterface[]
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param CoreElementInterface $element
     *
     * @return $this
     */
    public function addElement(CoreElementInterface $element)
    {
        $this->elements[] = $element;

        // run element setup to initiate assets and whatnot
        $element->setup();

        // handle assets
        $this->addAssetFiles($element->getAssetFiles());
        $this->addAssetInlines($element->getAssetInlines());

        return $this;
    }

    /**
     * @param CoreElementInterface[] $elements
     *
     * @return Form
     */
    public function setElements(array $elements)
    {
        foreach ($elements as $element)
        {
            $this->addElement($element);
        }

        return $this;
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
     * @return bool
     */
    public function validateFields()
    {
        if ($this->isSubmitted() && $this->isValid === null)
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

                // mark elements validation state
                switch ($element->hasError())
                {
                    case false:
                        // visual error indication
                        $element->setElementHtml(str_replace(':hasError', 'has-error', $element->getElementHtml()));

                        // cache invalid elements
                        $this->addInvalidElement($element);
                        break;

                    case true:
                        // visual error indication
                        $element->setElementHtml(str_replace(':hasError', 'has-success', $element->getElementHtml()));
                        break;

                    default:
                }
            }

            // cache validation result
            $this->isValid = $this->hasInvalidElements() === false;
        }

        return $this->isValid;
    }

    /**
     * @return bool|null
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * @param $template
     *
     * @return string
     */
    public function addFormAndAssetsTags($template)
    {
        $form = "<form {{attributes}}>\n\n{{hiddenId}}\n\n{{template}}\n\n{{css}}\n\n{{js}}</form>";

        // set attributes
        $attributes = [
            'role'           => 'form',
            'id'             => $this->getId(),
            'action'         => $this->getUrl(),
            'method'         => $this->getMethod(),
            'accept-charset' => $this->getCharset(),
            'enctype'        => 'multipart/form-data',
            'class'          => 'simplon-form',
        ];

        // set values
        $renderedAttributes = [];

        foreach ($attributes as $key => $value)
        {
            $renderedAttributes[] = $key . '="' . $value . '"';
        }

        // render attributes
        $form = str_replace('{{attributes}}', join(' ', $renderedAttributes), $form);

        // render hidden form name
        $form = str_replace('{{hiddenId}}', '<input type="hidden" name="hide-' . $this->getId() . '" value="1">', $form);

        // render css asset
        $form = str_replace('{{css}}', $this->renderHeaderAssets(), $form);

        // render js asset
        $form = str_replace('{{js}}', $this->renderBodyAssets(), $form);

        // render template
        $form = str_replace('{{template}}', $template, $form);

        return $form;
    }

    /**
     * @return string
     */
    public function renderHeaderAssets()
    {
        return $this->renderAssets(['css'], '<link rel="stylesheet" href="{{url}}">');
    }

    /**
     * @return string
     */
    public function renderBodyAssets()
    {
        $content = $this->renderAssets(['js'], '<script src="{{url}}" type="text/javascript"></script>');

        if ($this->hasAssetInlines() === true)
        {
            $this->addAssetInlines(["$('#" . $this->getId() . "').fadeIn()"]);
            $domReadyFunction = "var DOMReady = function(a,b,c){b=document,c='addEventListener';b[c]?b[c]('DOMContentLoaded',a):window.attachEvent('onload',a)};";
            $domReadyCallback = "DOMReady(function () {" . join("\n;", $this->getAssetInlines()) . "; \n\n});";

            $content .= "\n\n<script type=\"text/javascript\">\n\n// SIMPLON FORM - INLINE HANDLINGS\n\n{$domReadyFunction}\n\n{$domReadyCallback}\n\n</script>\n\n";
        }

        return $content;
    }

    /**
     * @return bool
     */
    private function hasRequestData()
    {
        return empty($this->requestData) === false;
    }

    /**
     * @param array $requestData
     *
     * @return Form
     */
    private function setRequestData(array $requestData)
    {
        $this->requestData = $requestData;

        return $this;
    }

    /**
     * @return bool
     */
    private function hasEnabledCsrf()
    {
        return (bool)$this->enabledCsrf;
    }

    /**
     * @return string
     */
    private function getId()
    {
        return (string)$this->id;
    }

    /**
     * @return string
     */
    private function getUrl()
    {
        return (string)$this->url;
    }

    /**
     * @return string
     */
    private function getCharset()
    {
        return (string)$this->acceptCharset;
    }

    /**
     * @return string
     */
    private function getMethod()
    {
        return (string)$this->method;
    }

    /**
     * @return array
     */
    private function getFollowUps()
    {
        return $this->followUps;
    }

    /**
     * @return string
     */
    private function getCsrfSalt()
    {
        return $this->csrfSalt;
    }

    /**
     * @return string
     */
    private function getCsrfValue()
    {
        return md5(session_id() . $this->getCsrfSalt());
    }

    /**
     * @param CoreElementInterface $elementInstance
     *
     * @return $this
     */
    private function addInvalidElement(CoreElementInterface $elementInstance)
    {
        $this->invalidElements[] = $elementInstance;

        return $this;
    }

    /**
     * @return array
     */
    private function getInvalidElements()
    {
        return $this->invalidElements;
    }

    /**
     * @return bool
     */
    private function hasInvalidElements()
    {
        $elms = $this->getInvalidElements();

        return empty($elms) === false;
    }

    /**
     * @return bool
     */
    private function isSubmitted()
    {
        return
            $this->hasRequestData() === true && // any request data at all?
            (int)$this->getRequestData('hide-' . $this->getId()) === 1; // has this form been submitted?
    }

    /**
     * @return bool
     */
    private function setCsrfElement()
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
     * @param array $assetFiles
     *
     * @return $this
     */
    private function addAssetFiles(array $assetFiles)
    {
        foreach ($assetFiles as $file)
        {
            if (in_array($file, $this->assetFiles) === false)
            {
                $this->assetFiles[] = $file;
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    private function getAssetFiles()
    {
        return $this->assetFiles;
    }

    /**
     * @param array $assetInlines
     *
     * @return Form
     */
    private function addAssetInlines(array $assetInlines)
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
    private function getAssetInlines()
    {
        return $this->assetInlines;
    }

    /**
     * @return bool
     */
    private function hasAssetInlines()
    {
        return count($this->getAssetInlines()) > 0 ? true : false;
    }

    /**
     * @param array $types
     * @param $pattern
     *
     * @return string
     */
    private function renderAssets(array $types, $pattern)
    {
        $assets = [];

        foreach ($this->getAssetFiles() as $file)
        {
            $parts = explode('.', $file);
            $fileExtension = strtolower(array_pop($parts));

            if (in_array($fileExtension, $types) === true)
            {
                $url = $this->getUrlRootAssets() . '/simplon-form/' . trim($file, '/');
                $assets[] = str_replace('{{url}}', $url, $pattern);
            }
        }

        return join("\n\n", $assets);
    }

    /**
     * @return string
     */
    private function getUrlRootAssets()
    {
        return $this->urlRootAssets;
    }
}