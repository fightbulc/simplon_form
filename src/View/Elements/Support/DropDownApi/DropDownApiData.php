<?php

namespace Simplon\Form\View\Elements\Support\DropDownApi;

use Simplon\Form\View\RenderHelper;

class DropDownApiData implements \Iterator
{
    /**
     * @var DropDownApiItemData[]
     */
    private $data;
    /**
     * @var int
     */
    private $index = 0;

    /**
     * @param string $encodedJson
     *
     * @return DropDownApiData
     */
    public function fromForm(string $encodedJson): self
    {
        $sets = explode(',', $encodedJson);

        foreach ($sets as $item)
        {
            $this->data[] = (new DropDownApiItemData())->fromForm($item);
        }

        return $this;
    }

    /**
     * @param DropDownApiItemData $item
     *
     * @return DropDownApiData
     */
    public function addFromStorage(DropDownApiItemData $item): self
    {
        $this->data[] = $item;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode(',', $this->data);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [];

        foreach ($this->data as $item)
        {
            $data[] = [
                'label'    => $item->getLabel(),
                'name'     => $item->getName(),
                'remoteID' => $item->getRemoteID(),
                'meta'     => $item->getMeta(),
            ];
        }

        return $data;
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        return RenderHelper::jsonEncode($this->toArray());
    }

    /**
     * @return bool
     */
    public function hasMultiple(): bool
    {
        return count($this->data) > 1;
    }

    /**
     * @return DropDownApiItemData
     */
    public function current(): DropDownApiItemData
    {
        return $this->data[$this->index];
    }

    /**
     * @return void
     */
    public function next(): void
    {
        $this->index++;
    }

    /**
     * @return string
     */
    public function key(): string
    {
        return $this->current()->getEncodedJson();
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return !empty($this->data[$this->index]);
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->index = 0;
    }
}