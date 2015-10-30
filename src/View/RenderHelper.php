<?php

namespace Simplon\Form\View;

/**
 * Class RenderHelper
 * @package Simplon\Form\View
 */
class RenderHelper
{
    /**
     * @param string $html
     * @param array $attrs
     *
     * @return string
     */
    public static function attributes($html, array $attrs)
    {
        foreach ($attrs as $group => $elements)
        {
            $renderedAttrs = [];

            foreach ($elements as $key => $value)
            {
                if (is_array($value))
                {
                    $value = join(' ', $value);
                }

                $renderedAttrs[] = $key . '="' . $value . '"';
            }

            $html = preg_replace('/\{' . $group . '\}/ui', join(' ', $renderedAttrs), $html);
        }

        return $html;
    }

    /**
     * @param string $html
     * @param array $placeholders
     *
     * @return string
     */
    public static function placeholders($html, array $placeholders)
    {
        foreach ($placeholders as $key => $value)
        {
            $html = preg_replace('/\{' . $key . '\}/ui', $value, $html);
        }

        return $html;
    }

    /**
     * @param array $lines
     *
     * @return string
     */
    public static function codeLines(array $lines)
    {
        foreach ($lines as $index => $line)
        {
            $lines[$index] = trim($line, ';');
        }

        return join(";\n", $lines);
    }
}