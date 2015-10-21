<?php

namespace Simplon\Form\View\Elements;

/**
 * Class CancelElement
 * @package Simplon\Form\View\Elements
 */
class CancelElement
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $url;

    /**
     * @param string $label
     * @param string $url
     */
    public function __construct($label, $url)
    {
        $this->label = $label;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function renderElement()
    {
        return '<div class="form-button cancel-submit"><a href="' . $this->url . '">' . $this->label . '</a></div>';
    }
}