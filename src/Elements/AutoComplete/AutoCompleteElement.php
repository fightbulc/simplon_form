<?php

namespace Simplon\Form\Elements\AutoComplete;

use Simplon\Form\Elements\CoreElement;

class AutoCompleteElement extends CoreElement
{
    protected $elementHtml = '<div class=":hasError"><input type="text" class="form-control" name=":id" id=":id" value=":value" placeholder=":placeholder" autocomplete="off"></div>';
    protected $placeholder;
    protected $resultTemplate;
    protected $selectedTemplate;

    /**
     * @param mixed $resultTemplate
     *
     * @return AutoCompleteElement
     */
    public function setResultTemplate($resultTemplate)
    {
        $this->resultTemplate = $resultTemplate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResultTemplate()
    {
        return preg_replace('/\n+/', '', $this->resultTemplate);
    }

    /**
     * @param mixed $selectedTemplate
     *
     * @return AutoCompleteElement
     */
    public function setSelectedTemplate($selectedTemplate)
    {
        $this->selectedTemplate = $selectedTemplate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSelectedTemplate()
    {
        return preg_replace('/\n+/', '', $this->selectedTemplate);
    }

    /**
     * @param mixed $placeholder
     *
     * @return static
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @return mixed
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
     * @return array
     */
    public function render()
    {
        $this->addJs("console.log('loaded')");
        $this->addJs("var p = $('#{$this->getId()}').remoteComplete({resultTemplate:'{$this->getResultTemplate()}', selectedTemplate:'{$this->getSelectedTemplate()}'})");

        if (isset($POST['city_results']))
        {
            $data = $POST['city_results'][0];

            $this->addJs("p.init([JSON.parse('" . json_encode(json_decode($data, true)) . "')])");
        }

        return parent::render();
    }
}