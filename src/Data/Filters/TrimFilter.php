<?php

namespace Simplon\Form\Data\Filters;

use Simplon\Form\Data\FilterInterface;

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
    protected $trimChars;

    /**
     * @param string $trimChars
     */
    public function __construct($trimChars = " \t\n\r\0\x0B")
    {
        $this->trimChars = $trimChars;
    }

    /**
     * @param string $chars
     *
     * @return TrimFilter
     */
    public function addChars(string $chars): self
    {
        $this->trimChars .= $chars;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function apply($value)
    {
        if (is_array($value))
        {
            foreach ($value as $k => $v)
            {
                $value[$k] = $this->convert($v);
            }

            return $value;
        }

        return $this->convert($value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function convert($value)
    {
        return trim($value, $this->trimChars);
    }
}