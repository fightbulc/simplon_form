<?php

namespace Simplon\Form\Renderer\Core;

use Simplon\Form\Form;

/**
 * CoreFormFormRenderer
 * @package Simplon\Form\Renderer\Core
 * @author Tino Ehrich (tino@bigpun.me)
 */
abstract class CoreFormRenderer implements CoreFormRendererInterface
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