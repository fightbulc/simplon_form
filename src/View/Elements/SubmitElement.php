<?php

namespace Simplon\Form\View\Elements;

/**
 * Class SubmitElement
 * @package Simplon\Form\View\Elements
 */
class SubmitElement
{
    /**
     * @var string
     */
    private $label;

    /**
     * @param string $label
     */
    public function __construct($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function renderElement()
    {
        return '<div class="form-button input-submit"><input type="submit" name="form[submit]" value="' . $this->label . '"></div>';
    }
}