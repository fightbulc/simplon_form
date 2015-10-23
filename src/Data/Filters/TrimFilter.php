<?php

namespace Simplon\Form\Data\Filters;

/**
 * TrimFilter
 * @package Simplon\Form\Filters
 * @author Tino Ehrich (tino@bigpun.me)
 */
class TrimFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected $trimChars = null;

    /**
     * @param null $trimChars
     */
    public function __construct($trimChars = null)
    {
        $this->trimChars = $trimChars;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function apply($value)
    {
        if (isset($this->trimChars))
        {
            return trim($value, $this->trimChars);
        }

        return trim($value);
    }
}