<?php

namespace Simplon\Form\Renderer;

use Simplon\Form\Elements\CoreElementInterface;

/**
 * CoreRendererInterface
 * @package Simplon\Form\Renderer
 * @author Tino Ehrich (tino@bigpun.me)
 */
interface CoreRendererInterface
{
    /**
     * @param CoreElementInterface[] $formElements
     *
     * @return static
     */
    public function setFormElements(array $formElements);

    /**
     * @param string $pathTemplate
     *
     * @return string
     */
    public function render($pathTemplate);
}