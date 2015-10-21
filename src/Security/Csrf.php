<?php

namespace Simplon\Form\Security;

/**
 * Class Csrf
 * @package Simplon\Form\Security
 */
class Csrf
{
    /**
     * @var CsrfStorageInterface
     */
    private $storage;

    /**
     * @var string
     */
    private $storageKey;

    /**
     * @var string
     */
    private $nameToken;

    /**
     * @var string
     */
    private $valueToken;

    /**
     * @var array
     */
    private $storedData;

    /**
     * @param CsrfStorageInterface $storage
     * @param string $storageKey
     */
    public function __construct(CsrfStorageInterface $storage, $storageKey = 'CSRF')
    {
        $this->storage = $storage;
        $this->storageKey = $storageKey;
        $this->storedData = $this->read();

        $this->nameToken = $this->renderNameToken();
        $this->valueToken = $this->renderValueToken();

        $this->reset();
    }

    /**
     * @return string
     */
    public function renderElement()
    {
        return '<input type="hidden" name="form[' . $this->getNameToken() . ']" value="' . $this->getValueToken() . '">';
    }

    /**
     * @param array $requestData
     *
     * @return bool|null
     */
    public function isValid(array $requestData)
    {
        $stored = $this->getStored();

        if (empty($stored))
        {
            return null;
        }

        return isset($requestData[$stored['name']]) && $requestData[$stored['name']] === $stored['value'];
    }

    /**
     * @return Csrf
     */
    private function reset()
    {
        $this->getStorage()->write(
            $this->getStorageKey(),
            [
                'name'  => $this->getNameToken(),
                'value' => $this->getValueToken(),
            ]
        );

        return $this;
    }

    /**
     * @return array|null
     */
    private function read()
    {
        return $this->getStorage()->read($this->getStorageKey());
    }

    /**
     * @return string
     */
    private function renderNameToken()
    {
        return $this->renderRandomToken();
    }

    /**
     * @return string
     */
    private function renderValueToken()
    {
        return $this->renderRandomToken();
    }

    /**
     * @param int $length
     *
     * @return string
     */
    private function renderRandomToken($length = 32)
    {
        $randomString = '';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // generate token
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    /**
     * @return CsrfStorageInterface
     */
    private function getStorage()
    {
        return $this->storage;
    }

    /**
     * @return string
     */
    private function getStorageKey()
    {
        return $this->storageKey;
    }

    /**
     * @return string
     */
    private function getNameToken()
    {
        return $this->nameToken;
    }

    /**
     * @return string
     */
    private function getValueToken()
    {
        return $this->valueToken;
    }

    /**
     * @return array
     */
    private function getStored()
    {
        return $this->storedData;
    }
}