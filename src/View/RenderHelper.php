<?php

namespace Simplon\Form\View;

/**
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
    public static function attributes(string $html, array $attrs): string
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

            $html = str_replace('{' . $group . '}', join(' ', $renderedAttrs), $html);
        }

        return $html;
    }

    /**
     * @param string $html
     * @param array $placeholders
     *
     * @return string
     */
    public static function placeholders(string $html, array $placeholders): string
    {
        foreach ($placeholders as $key => $value)
        {
            $html = str_replace('{' . $key . '}', $value, $html);
        }

        return $html;
    }

    /**
     * @param array $lines
     *
     * @return string
     */
    public static function codeLines(array $lines): string
    {
        foreach ($lines as $index => $line)
        {
            $lines[$index] = trim($line, ';');
        }

        return join(";\n", $lines);
    }

    /**
     * @param array $options
     * @param bool $removeOutterWrappers
     *
     * @return string
     */
    public static function jsonEncode(array $options = [], bool $removeOutterWrappers = false): string
    {
        $json = json_encode($options, JSON_PRETTY_PRINT);

        if ($removeOutterWrappers)
        {
            $json = substr($json, 1, -1);
        }

        return $json;
    }
}