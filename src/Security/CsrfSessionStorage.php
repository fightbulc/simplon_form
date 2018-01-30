<?php

namespace Simplon\Form\Security;

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
     *
     * @return void
     */
    public function write(string $key, array $data): void
    {
        $_SESSION[$key] = $data;
    }

    /**
     * @param string $key
     *
     * @return array|null
     */
    public function read(string $key): ?array
    {
        if (empty($_SESSION[$key]))
        {
            return null;
        }

        return $_SESSION[$key];
    }
}