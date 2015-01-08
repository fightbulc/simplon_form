<?php

namespace Simplon\Form\Elements\Hidden;

use Simplon\Form\Elements\CoreElement;

class HiddenElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<input type="hidden" name=":name" value=":value">';
}