<?php

namespace Simplon\Form\Elements\TextMultiLine;

use Simplon\Form\Elements\CoreElement;

/**
 * TextMultiLineElement
 * @package Simplon\Form\Elements\TextMultiLine
 * @author Tino Ehrich (tino@bigpun.me)
 */
class TextMultiLineElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<div class=":hasError"><textarea name=":name" class=":class" id=":id" placeholder=":placeholder">:value</textarea></div>';

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