<?php

namespace Simplon\Form\Elements\TextSingleLine;

use Simplon\Form\Elements\CoreElement;

class TextSingleLineElement extends CoreElement
{
    protected $elementHtml = '<div class=":hasError"><input type="text" class=":class" name=":id" id=":id" value=":value" placeholder=":placeholder"></div>';
    protected $class = ['form-control input-lg'];
    protected $placeholder;

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


}