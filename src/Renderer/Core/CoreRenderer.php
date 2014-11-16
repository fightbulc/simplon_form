<?php

namespace Simplon\Form\Renderer\Core;

use Simplon\Form\Form;

/**
 * CoreRenderer
 * @package Simplon\Form\Renderer\Core
 * @author Tino Ehrich (tino@bigpun.me)
 */
abstract class CoreRenderer implements CoreRendererInterface
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }
}