<?php

namespace Simplon\Form\Data\Filters;

use Simplon\Form\Data\FilterInterface;

class CaseUpperFilter implements FilterInterface
{
    /**
     * @param string $value
     *
     * @return mixed
     */
    public function apply($value)
    {
        if (function_exists('mb_convert_case'))
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

        return $value;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function convert(string $value): string
    {
        return mb_convert_case($value, MB_CASE_UPPER, 'UTF-8');
    }
}