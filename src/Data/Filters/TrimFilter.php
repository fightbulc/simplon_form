<?php

namespace Simplon\Form\Data\Filters;

use Simplon\Form\Data\FilterInterface;

class TrimFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected $trimChars;

    /**
     * @param string $trimChars
     */
    public function __construct(string $trimChars = " \t\n\r\0\x0B")
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
     * @return mixed
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
     * @param null|string $value
     *
     * @return string
     */
    private function convert(?string $value): string
    {
        return trim($value, $this->trimChars);
    }
}