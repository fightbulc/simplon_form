<?php

namespace Simplon\Form\Renderer\Core;

use Simplon\Form\Elements\CoreElementInterface;

/**
 * Interface CoreElementRendererInterface
 * @package Simplon\Form\Renderer\Core
 * @author Tino Ehrich (tino@bigpun.me)
 */
interface CoreElementRendererInterface
{
    /**
     * @param string $pathTemplate
     */
    public function __construct($pathTemplate);

    /**
     * @param CoreElementInterface[] $elements
     *
     * @return static
     */
    public function setElements(array $elements);

    /**
     * @param array $customParams
     *
     * @return string
     */
    public function render(array $customParams = []);
}