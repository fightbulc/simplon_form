<?php

namespace Simplon\Form\Renderer\Core;

use Simplon\Form\Form;

/**
 * Interface CoreRendererInterface
 * @package Simplon\Form\Renderer\Core
 * @author Tino Ehrich (tino@bigpun.me)
 */
interface CoreRendererInterface
{
    /**
     * @param Form $form
     */
    public function __construct(Form $form);

    /**
     * @param $pathTemplate
     * @param array $customParams
     *
     * @return string
     */
    public function render($pathTemplate, array $customParams = []);
}