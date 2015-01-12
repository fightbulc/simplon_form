<?php

namespace Simplon\Form\Renderer;

use Simplon\Form\Renderer\Core\CoreElementRenderer;
use Simplon\Phtml\Phtml;

/**
 * PhtmlElementRenderer
 * @package Simplon\Form\Renderer
 * @author Tino Ehrich (tino@bigpun.me)
 */
class PhtmlElementRenderer extends CoreElementRenderer
{
    /**
     * @param array $customParams
     *
     * @return string
     * @throws \Simplon\Phtml\PhtmlException
     */
    public function render(array $customParams = [])
    {
        $params = array_merge($this->getParams(), $customParams);
        $template = Phtml::render($this->pathTemplate, $params);

        return $template;
    }

    /**
     * @return array
     */
    private function getParams()
    {
        $params = [];

        // add elements
        foreach ($this->elements as $element)
        {
            $params['elements'][$element->getId()] = $element;
        }

        return $params;
    }
}