<?php

namespace Simplon\Form\Renderer;

/**
 * CoreRenderer
 * @package Simplon\Form\Renderer
 * @author Tino Ehrich (tino@bigpun.me)
 */
abstract class CoreRenderer implements CoreRendererInterface
{
    /**
     * @param $pathTemplate
     *
     * @return string
     * @throws \Exception
     */
    public static function loadTextFile($pathTemplate)
    {
        $template = file_get_contents($pathTemplate);

        if ($template === false)
        {
            throw new \Exception('Requested template does not exist: ' . $pathTemplate);
        }

        return (string)$template;
    }

    /**
     * @param $pathTemplate
     * @param array $data
     *
     * @return string
     */
    public static function loadNativeFile($pathTemplate, array $data)
    {
        ob_start();
        extract($data);
        require $pathTemplate . '.php';
        $template = ob_get_clean();

        return (string)$template;
    }
}