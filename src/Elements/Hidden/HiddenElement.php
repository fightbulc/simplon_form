<?php

namespace Simplon\Form\Elements\Hidden;

use Simplon\Form\Elements\CoreElement;

/**
 * HiddenElement
 * @package Simplon\Form\Elements\Hidden
 * @author Tino Ehrich (tino@bigpun.me)
 */
class HiddenElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<input type="hidden" name=":name" value=":value">';
}