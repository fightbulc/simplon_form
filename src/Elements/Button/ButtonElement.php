<?php

namespace Simplon\Form\Elements\Button;

use Simplon\Form\Elements\CoreElement;

class ButtonElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<button class=":class">:label</button>';

    /**
     * @return array
     */
    protected function getFieldPlaceholders()
    {
        $coreFieldPlaceholders = parent::getFieldPlaceholders();

        // add options
        $coreFieldPlaceholders['class'] = 'btn';

        return $coreFieldPlaceholders;
    }

    /**
     * @return array
     */
    public function render()
    {
        return [
            'element' => $this->renderElementHtml(),
        ];
    }
}