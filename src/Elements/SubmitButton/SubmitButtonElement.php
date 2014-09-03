<?php

namespace Simplon\Form\Elements\SubmitButton;

use Simplon\Form\Elements\CoreElement;

class SubmitButtonElement extends CoreElement
{
    protected $elementHtml = '<input type="submit" class=":class" value=":label">';

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