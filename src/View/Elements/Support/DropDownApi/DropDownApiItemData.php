<?php

namespace Simplon\Form\View\Elements\Support\DropDownApi;

use Simplon\Form\View\RenderHelper;

/**
 * @package Simplon\Form\View\Elements\Support\DropDownApi
 */
class DropDownApiItemData
{
    /**
     * @var string
     */
    private $encodedJson;
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $remoteID;
    /**
     * @var array
     */
    private $meta = [];

    /**
     * @param string $encodedJson
     *
     * @return DropDownApiItemData
     */
    public function fromForm(string $encodedJson): self
    {
        $this->encodedJson = $encodedJson;

        if (substr($encodedJson, 0, 1) === '%')
        {
            $encodedJson = urldecode($encodedJson);
        }

        $data = json_decode($encodedJson, true);
        $this->label = $data['label'];
        $this->name = $data['name'];
        $this->remoteID = $data['remoteID'];
        $this->meta = $data['meta'];

        return $this;
    }

    /**
     * @param string $label
     * @param string $name
     * @param string $remoteID
     * @param array $meta
     *
     * @return DropDownApiItemData
     */
    public function fromStorage(string $label, string $name, string $remoteID, array $meta = []): self
    {
        $this->label = $label;
        $this->name = $name;
        $this->remoteID = $remoteID;
        $this->meta = $meta;

        $this->encodedJson = rawurlencode(
            RenderHelper::jsonEncode([
                'label'    => $label,
                'name'     => $name,
                'remoteID' => $remoteID,
                'meta'     => $meta,
            ])
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getEncodedJson(): string
    {
        return $this->encodedJson;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRemoteID(): string
    {
        return $this->remoteID;
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->encodedJson;
    }
}