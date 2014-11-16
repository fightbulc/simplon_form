<?php

namespace Simplon\Form\Elements\SubmitButton;

use Simplon\Form\Elements\CoreElement;

class SubmitButtonElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<input type="submit" class=":class" value=":label">';
}