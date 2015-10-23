<?php

namespace Simplon\Form\Security;

/**
 * Interface CsrfStorageInterface
 * @package Simplon\Form\Security
 */
interface CsrfStorageInterface
{
    /**
     * @param string $key
     * @param array $data
     */
    public function write($key, array $data);

    /**
     * @param string $key
     *
     * @return array|null
     */
    public function read($key);
}