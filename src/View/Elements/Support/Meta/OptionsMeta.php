<?php

namespace Simplon\Form\View\Elements\Support\Meta;

/**
 * Class OptionsMeta
 * @package Simplon\Form\View\Elements\Support\Meta
 */
class OptionsMeta implements MetaInterface
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @return string
     */
    public function getKey()
    {
        return 'options';
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $value
     * @param string $label
     *
     * @return static
     */
    public function add($value, $label = null)
    {
        $this->data[] = [
            'value' => $value,
            'label' => $label,
        ];

        return $this;
    }

    /**
     * @param array $options
     *
     * @return static
     */
    public function set(array $options)
    {
        $this->data = $options;

        return $this;
    }
}