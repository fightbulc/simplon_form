<?php

namespace Simplon\Form\Data\Filters;

class TrimLeftFilter extends TrimFilter
{
    /**
     * @param null|string $value
     *
     * @return string
     */
    protected function convert(?string $value): string
    {
        return ltrim($value, $this->trimChars);
    }
}