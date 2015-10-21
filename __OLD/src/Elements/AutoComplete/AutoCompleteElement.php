<?php

namespace Simplon\Form\Elements\AutoComplete;

use Simplon\Form\Elements\CoreElement;

/**
 * AutoCompleteElement
 * @package Simplon\Form\Elements\AutoComplete
 * @author Tino Ehrich (tino@bigpun.me)
 */
class AutoCompleteElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<div class=":hasError"><input type="text" class="form-control" name=":name" id=":id" value=":value" placeholder=":placeholder" autocomplete="off"></div>';

    /**
     * @var string
     */
    protected $placeholder;

    /**
     * @var string
     */
    protected $resultTemplate;

    /**
     * @var string
     */
    protected $selectedTemplate;

    /**
     * @param string $resultTemplate
     *
     * @return AutoCompleteElement
     */
    public function setResultTemplate($resultTemplate)
    {
        $this->resultTemplate = $resultTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getResultTemplate()
    {
        return (string)preg_replace('/\n+/', '', $this->resultTemplate);
    }

    /**
     * @param string $selectedTemplate
     *
     * @return AutoCompleteElement
     */
    public function setSelectedTemplate($selectedTemplate)
    {
        $this->selectedTemplate = $selectedTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getSelectedTemplate()
    {
        return (string)preg_replace('/\n+/', '', $this->selectedTemplate);
    }

    /**
     * @param string $placeholder
     *
     * @return static
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @return array
     */
    protected function getFieldPlaceholders()
    {
        $placeholders = parent::getFieldPlaceholders();
        $placeholders['placeholder'] = $this->getPlaceholder();

        return $placeholders;
    }

    /**
     * @return void
     */
    public function setup()
    {
        $this->addAssetFile("vendor/jquery.remote-complete/jquery.remote-complete.css");
        $this->addAssetFile("vendor/jquery.remote-complete/jquery.remote-complete.js");
        $this->addAssetFile("vendor/jquery.remote-complete/hogan-2.0.0.min.js");

        if (isset($_POST['city_results']))
        {
            $data = $_POST['city_results'][0];

            $this->addAssetFile("p.init([JSON.parse('" . json_encode(json_decode($data, true)) . "')])");
        }
    }
}