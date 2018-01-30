<?php

namespace Simplon\Form\Security;

interface CsrfStorageInterface
{
    /**
     * @param string $key
     * @param array $data
     *
     * @return void
     */
    public function write(string $key, array $data): void;

    /**
     * @param string $key
     *
     * @return array|null
     */
    public function read(string $key): ?array;
}