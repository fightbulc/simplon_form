<?php

namespace Simplon\Form\Elements\Password;

use Simplon\Form\Elements\TextSingleLine\TextSingleLineElement;

/**
 * PasswordElement
 * @package Simplon\Form\Elements\Password
 * @author Tino Ehrich (tino@bigpun.me)
 */
class PasswordElement extends TextSingleLineElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<div class=":hasError"><input type="password" class=":class" name=":name" id=":id" value=":value" placeholder=":placeholder"></div>';
}