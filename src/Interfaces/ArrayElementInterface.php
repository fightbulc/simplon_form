<?php

namespace Simplon\Form\Interfaces;

use Simplon\Form\Utils\ArrayElementResults;

/**
 * Interface ArrayElementInterface
 *
 * @author Tino Ehrich (tino@bigpun.me)
 */
interface ArrayElementInterface
{
    /**
     * @return ArrayElementResults[]
     */
    public function getElementValues();
}