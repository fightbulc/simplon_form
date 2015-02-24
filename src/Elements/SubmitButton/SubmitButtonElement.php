<?php

namespace Simplon\Form\Elements\SubmitButton;

use Simplon\Form\Elements\CoreElement;

/**
 * SubmitButtonElement
 * @package Simplon\Form\Elements\SubmitButton
 * @author Tino Ehrich (tino@bigpun.me)
 */
class SubmitButtonElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<input type="submit" class=":class" value=":label">';
}