<?php

namespace Simplon\Form\View\Elements\Support\DropDownApi;

/**
 * @package Simplon\Form\View\Elements\Support\DropDownApi
 */
class DropDownApiResultItem
{
    /**
     * @var string
     */
    private $raw;
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $remoteId;

    /**
     * @param string $json
     */
    public function __construct(string $json)
    {
        $this->raw = $json;

        if (substr($json, 0, 1) === '%')
        {
            $json = urldecode($json);
        }

        $data = json_decode($json, true);
        $this->remoteId = $data['remote_id'];
        $this->label = $data['label'];
    }

    /**
     * @return string
     */
    public function getRaw(): string
    {
        return $this->raw;
    }

    /**
     * @return string
     */
    public function getRemoteId(): string
    {
        return $this->remoteId;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
}