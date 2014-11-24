<?php

namespace Simplon\Form\Renderer;

use Simplon\Form\Renderer\Core\CoreRenderer;
use Simplon\Phtml\Phtml;

/**
 * PhtmlRenderer
 * @package Simplon\Form\Renderer
 * @author Tino Ehrich (tino@bigpun.me)
 */
class PhtmlRenderer extends CoreRenderer
{
    /**
     * @param string $pathTemplate
     * @param array $customParams
     *
     * @return string
     * @throws \Simplon\Mustache\MustacheException
     */
    public function render($pathTemplate, array $customParams = [])
    {
        $params = array_merge($this->getParams(), $customParams);
        $template = Phtml::render($pathTemplate, $params);
        $template = $this->form->addFormAndAssetsTags($template);

        return $template;
    }

    /**
     * @return array
     */
    private function getParams()
    {
        $params = [
            'hasError'     => false,
            'errorMessage' => $this->form->renderGeneralErrorMessage(),
        ];

        // add elements
        foreach ($this->form->getElements() as $element)
        {
            $params['elements'][$element->getId()] = $element;
        }

        // add general error message
        if ($this->form->isValid() === false)
        {
            $params['hasError'] = true;
        }

        return $params;
    }
}