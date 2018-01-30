<?php

namespace Simplon\Form\Data\Filters;

class TrimRightFilter extends TrimFilter
{
    /**
     * @param null|string $value
     *
     * @return string
     */
    protected function convert(?string $value): string
    {
        return rtrim($value, $this->trimChars);
    }
}