<?php

namespace Simplon\Form\Filters\Core;

use Simplon\Form\Elements\CoreElementInterface;

/**
 * CoreFilter
 * @package Simplon\Form\Filters
 * @author Tino Ehrich (tino@bigpun.me)
 */
class CoreFilter implements CoreFilterInterface
{
    /**
     * @param CoreElementInterface $coreElementInterface
     *
     * @return CoreElementInterface
     */
    public function processFilter(CoreElementInterface $coreElementInterface)
    {
        // get value
        $value = $coreElementInterface->getValue();

        // run value through filter
        $value = $this->applyFilter($value);

        // set filtered value
        $coreElementInterface->setPostValue($value);

        return $coreElementInterface;
    }

    /**
     * @param mixed $elementValue
     *
     * @return mixed
     */
    public function applyFilter($elementValue)
    {
        return $elementValue;
    }
}