<?php

namespace Simplon\Form\Interfaces;

/**
 * Interface ArrayElementInterface
 *
 * @author Tino Ehrich (tino@bigpun.me)
 */
interface ArrayElementInterface
{
    /**
     * @param array $resultContainer
     *
     * @return array
     */
    public function getElementValues(array $resultContainer = []);
}