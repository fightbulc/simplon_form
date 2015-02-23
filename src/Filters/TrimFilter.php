<?php

namespace Simplon\Form\Filters;

use Simplon\Form\Filters\Core\CoreFilter;

/**
 * TrimFilter
 * @package Simplon\Form\Filters
 * @author Tino Ehrich (tino@bigpun.me)
 */
class TrimFilter extends CoreFilter
{
    protected $trimChars = null;

    /**
     * @param null $trimChars
     */
    public function __construct($trimChars = null)
    {
        $this->trimChars = $trimChars;
    }

    /**
     * @param string $elementValue
     *
     * @return string
     */
    public function applyFilter($elementValue)
    {
        if ($this->trimChars !== null)
        {
            return trim($elementValue, $this->trimChars);
        }

        return trim($elementValue);
    }
}