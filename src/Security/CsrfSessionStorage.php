<?php

namespace Simplon\Form\Security;

/**
 * Class CsrfSessionStorage
 * @package Simplon\Form\Security
 */
class CsrfSessionStorage implements CsrfStorageInterface
{
    public function __construct()
    {
        if (empty(session_id()))
        {
            session_start();
        }
    }

    /**
     * @param string $key
     * @param array $data
     */
    public function write($key, array $data)
    {
        $_SESSION[$key] = $data;
    }

    /**
     * @param string $key
     *
     * @return array|null
     */
    public function read($key)
    {
        if (empty($_SESSION[$key]))
        {
            return null;
        }

        return $_SESSION[$key];
    }
}