<?php

namespace Simplon\Form\View\Elements\Support\DropDownApi;

/**
 * @package Simplon\Form\View\Elements\Support\DropDownApi
 */
class DropDownApiResults implements \Iterator
{
    /**
     * @var DropDownApiResultItem[]
     */
    private $data;
    /**
     * @var int
     */
    private $index = 0;

    /**
     * @param string $encodeJson
     */
    public function __construct(string $encodeJson)
    {
        $sets = explode(',', $encodeJson);

        foreach ($sets as $item)
        {
            $this->data[] = new DropDownApiResultItem($item);
        }
    }

    /**
     * @return bool
     */
    public function hasMultiple(): bool
    {
        return count($this->data) > 1;
    }

    /**
     * @return DropDownApiResultItem
     */
    public function current(): DropDownApiResultItem
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
        return $this->current()->getRaw();
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