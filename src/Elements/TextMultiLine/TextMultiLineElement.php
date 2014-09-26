<?php

namespace Simplon\Form\Elements\TextMultiLine;

use Simplon\Form\Elements\CoreElement;

class TextMultiLineElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<div class=":hasError"><textarea name=":id" class="form-control" id=":id">:value</textarea></div>';
}