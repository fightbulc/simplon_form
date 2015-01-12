<?php

namespace Simplon\Form\Renderer;

use Simplon\Form\Renderer\Core\CoreFormRenderer;
use Simplon\Mustache\Mustache;

/**
 * MustacheFormRenderer
 * @package Simplon\Form\Renderer
 * @author Tino Ehrich (tino@bigpun.me)
 */
class MustacheFormRenderer extends CoreFormRenderer
{
    /**
     * @param string $pathTemplate
     * @param array $customParams
     * @param array $customParsers
     *
     * @return string
     * @throws \Simplon\Mustache\MustacheException
     */
    public function render($pathTemplate, array $customParams = [], array $customParsers = [])
    {
        $params = array_merge($this->getParams(), $customParams);
        $template = Mustache::renderByFile($pathTemplate, $params, $customParsers);
        $template = $this->form->addFormAndAssetsTags($template);

        return $template;
    }

    /**
     * @return array
     */
    private function getParams()
    {
        $params = [];

        foreach ($this->form->getElements() as $element)
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

        // add general error message
        if ($this->form->isValid() === false)
        {
            $params['hasError'] = ['value' => $this->form->renderGeneralErrorMessage()];
        }

        return $params;
    }
}