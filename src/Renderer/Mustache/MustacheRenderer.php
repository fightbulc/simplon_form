<?php

namespace Simplon\Form\Renderer\Mustache;

use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Renderer\CoreRenderer;

/**
 * MustacheRenderer
 * @package Simplon\Form\Renderer\Mustache
 * @author Tino Ehrich (tino@bigpun.me)
 */
class MustacheRenderer extends CoreRenderer
{
    /**
     * @var CoreElementInterface[]
     */
    private $formElements;

    /**
     * @var array
     */
    private $data;

    /**
     * @param CoreElementInterface[] $formElements
     *
     * @return MustacheRenderer
     */
    public function setFormElements(array $formElements)
    {
        $this->formElements = $formElements;

        return $this;
    }

    /**
     * @param string $pathTemplate
     *
     * @return string
     */
    public function render($pathTemplate)
    {
        $template = $this->loadTextFile($pathTemplate);
        $template = $this->parse($template, $this->getData());

        return $this->cleanTemplate($template);
    }

    /**
     * @return array
     */
    private function getData()
    {
        if ($this->data === null)
        {
            $this->data = [];

            foreach ($this->formElements as $element)
            {
                $templateElms = $element->render();

                foreach ($templateElms as $rk => $rv)
                {
                    $key = $element->getId() . ':' . $rk;

                    $this->data[$key] = [
                        'value' => $rv
                    ];
                }
            }
        }

        return $this->data;
    }

    /**
     * @param string $template
     * @param array $data
     *
     * @return string
     */
    private function parse($template, array $data)
    {
        foreach ($data as $key => $val)
        {
            if (is_array($val))
            {
                // find loops
                preg_match_all('|{{#' . $key . '}}(.*?){{/' . $key . '}}|sm', $template, $foreachPattern);

                // handle loops
                if (isset($foreachPattern[1][0]))
                {
                    foreach ($foreachPattern[1] as $patternId => $patternContext)
                    {
                        $loopContent = '';

                        // handle array objects
                        if (isset($val[0]))
                        {
                            foreach ($val as $loopVal)
                            {
                                $loopContent .= $this->parse($patternContext, $loopVal);
                            }
                        }

                        // normal array only
                        else
                        {
                            $loopContent = $this->parse($patternContext, $val);
                        }

                        // replace pattern context
                        $template = preg_replace(
                            '|' . preg_quote($foreachPattern[0][$patternId]) . '|s',
                            $loopContent,
                            $template,
                            1
                        );
                    }
                }
            }

            // ----------------------------------

            elseif (is_bool($val))
            {
                // determine true/false
                $conditionChar = $val === true ? '\#' : '\^';

                // find bools
                preg_match_all('|{{' . $conditionChar . $key . '}}(.*?){{/' . $key . '}}|s', $template, $boolPattern);

                // handle bools
                if (isset($boolPattern[1][0]))
                {
                    foreach ($boolPattern[1] as $patternId => $patternContext)
                    {
                        // parse and replace pattern context
                        $template = preg_replace(
                            '|' . preg_quote($boolPattern[0][$patternId]) . '|s',
                            $this->parse($patternContext, $this->getData()),
                            $template,
                            1
                        );
                    }
                }
            }

            // ----------------------------------

            elseif ($val instanceof \Closure)
            {
                // set closure return
                $template = str_replace('{{' . $key . '}}', $val(), $template);
            }

            // ----------------------------------

            else
            {
                // set vars
                $template = str_replace('{{' . $key . '}}', $val, $template);
            }
        }

        return (string)$template;
    }

    /**
     * @param $template
     *
     * @return string
     */
    private function cleanTemplate($template)
    {
        // remove left over wrappers
        $template = preg_replace('|{{.*?}}.*?{{/.*?}}\n*|s', '', $template);

        // remove left over variables
        $template = preg_replace('|{{.*?}}\n*|s', '', $template);

        return (string)$template;
    }
}