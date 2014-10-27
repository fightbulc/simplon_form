<?php

namespace Simplon\Form\Elements\Hidden;

use Simplon\Form\Elements\CoreElement;

class HiddenElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<input type="hidden" name=":id" value=":value">';

    /**
     * @return array
     */
    public function render()
    {
        $this->addAssetFile('');

        return [
            'element' => $this->parseFieldPlaceholders($this->getElementHtml()),
        ];
    }
}