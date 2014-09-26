<?php

namespace Simplon\Form\Elements\Password;

use Simplon\Form\Elements\TextSingleLine\TextSingleLineElement;

class PasswordElement extends TextSingleLineElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<div class=":hasError"><input type="password" class=":class" name=":id" id=":id" value=":value" placeholder=":placeholder"></div>';
}