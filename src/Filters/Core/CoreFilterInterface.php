<?php

namespace Simplon\Form\Filters\Core;

use Simplon\Form\Elements\CoreElementInterface;

/**
 * Interface CoreFilterInterface
 * @package Simplon\Form\Filters
 */
interface CoreFilterInterface
{
    /**
     * @param CoreElementInterface $elementInstance
     *
     * @return CoreElementInterface
     */
    public function processFilter(CoreElementInterface $elementInstance);

    /**
     * @param mixed $elementValue
     *
     * @return mixed
     */
    public function applyFilter($elementValue);
}