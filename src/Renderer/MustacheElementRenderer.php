<?php

namespace Simplon\Form\Renderer;

use Simplon\Form\Renderer\Core\CoreElementRenderer;
use Simplon\Mustache\Mustache;

/**
 * MustacheElementRenderer
 * @package Simplon\Form\Renderer
 * @author Tino Ehrich (tino@bigpun.me)
 */
class MustacheElementRenderer extends CoreElementRenderer
{
    /**
     * @param array $customParams
     *
     * @return string
     * @throws \Simplon\Mustache\MustacheException
     */
    public function render(array $customParams = [])
    {
        $params = array_merge($this->getParams(), $customParams);
        $template = Mustache::renderByFile($this->pathTemplate, $params);

        return $template;
    }

    /**
     * @return array
     */
    private function getParams()
    {
        $params = [];

        foreach ($this->elements as $element)
        {
            // get elements
            $elementParts = [
                'label'       => $element->renderLabel(),
                'description' => $element->renderDescription(),
                'element'     => $element->renderElementHtml(),
            ];

            if ($element->hasError() === true)
            {
                $elementParts['error'] = $element->renderErrorMessages();
            }

            // prepare tags
            foreach ($elementParts as $key => $value)
            {
                $params['has' . ucFirst($element->getId()) . ':' . $key] = ['value' => $value];
            }
        }

        return $params;
    }
}