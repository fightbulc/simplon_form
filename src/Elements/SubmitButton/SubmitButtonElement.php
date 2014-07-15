<?php

namespace Simplon\Form\Elements\SubmitButton;

use Simplon\Form\Elements\CoreElement;

class SubmitButtonElement extends CoreElement
{
    protected $elementHtml = '<input type="submit" class=":class" value=":label">';

    /**
     * @return array
     */
    protected function getFieldPlaceholders()
    {
        $coreFieldPlaceholders = parent::getFieldPlaceholders();

        // add options
        $coreFieldPlaceholders['class'] = 'btn btn-success btn-wide btn-embossed';

        return $coreFieldPlaceholders;
    }

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