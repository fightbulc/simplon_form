<?php

namespace Simplon\Form\Elements\TextSingleLine;

use Simplon\Form\Elements\CoreElement;

/**
 * TextSingleLineElement
 * @package Simplon\Form\Elements\TextSingleLine
 * @author Tino Ehrich (tino@bigpun.me)
 */
class TextSingleLineElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<div class=":hasError"><input type="text" class=":class" name=":name" id=":id" value=":value" placeholder=":placeholder"></div>';

    /**
     * @var array
     */
    protected $class = [];

    /**
     * @var string
     */
    protected $placeholder;

    /**
     * @param string $placeholder
     *
     * @return static
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @return array
     */
    protected function getFieldPlaceholders()
    {
        $placeholders = parent::getFieldPlaceholders();
        $placeholders['placeholder'] = $this->getPlaceholder();

        return $placeholders;
    }
}