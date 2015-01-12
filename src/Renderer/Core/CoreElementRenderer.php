<?php

namespace Simplon\Form\Renderer\Core;

use Simplon\Form\Elements\CoreElementInterface;

/**
 * CoreElementRenderer
 * @package Simplon\Form\Renderer\Core
 * @author Tino Ehrich (tino@bigpun.me)
 */
abstract class CoreElementRenderer implements CoreElementRendererInterface
{
    /**
     * @var CoreElementInterface[]
     */
    protected $elements;

    /**
     * @var string
     */
    protected $pathTemplate;

    /**
     * @param string $pathTemplate
     */
    public function __construct($pathTemplate)
    {
        $this->pathTemplate = $pathTemplate;
    }

    /**
     * @param array $elements
     *
     * @return static
     */
    public function setElements(array $elements)
    {
        $this->elements = $elements;

        return $this;
    }
}