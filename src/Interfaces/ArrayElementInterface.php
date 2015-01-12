<?php

namespace Simplon\Form\Interfaces;

use Simplon\Form\Elements\CoreElementInterface;

/**
 * Interface ArrayElementInterface
 *
 * @author Tino Ehrich (tino@bigpun.me)
 */
interface ArrayElementInterface
{
    /**
     * @return CoreElementInterface[]
     */
    public function getElementValues();
}