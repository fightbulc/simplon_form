<?php

namespace Simplon\Form\Elements\Radio;

use Simplon\Form\Elements\CoreElement;

/**
 * RadioElement
 * @package Simplon\Form\Elements\Radio
 * @author  Tino Ehrich (tino@bigpun.me)
 */
class RadioElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<div id=":id">:items</div>';

    /**
     * @var string
     */
    protected $elementItemHtml = '<label class="radio"><input type="radio" name=":name" id=":id_:value" value=":value" data-toggle="radio" :checked :attrs> :label</label>';

    /**
     * @var bool
     */
    protected $useOptionKeys = true;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @return string
     */
    public function getElementItemHtml()
    {
        return $this->elementItemHtml;
    }

    /**
     * @return bool
     */
    public function getUseOptionKeys()
    {
        return $this->useOptionKeys;
    }

    /**
     * @param bool $useKeys
     *
     * @return RadioElement
     */
    public function setUseOptionKeys($useKeys)
    {
        $this->useOptionKeys = $useKeys === true;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        // use values as keys
        if ($this->getUseOptionKeys() === false)
        {
            $newOptions = [];

            foreach ($options as $option)
            {
                $newOptions[$option] = $option;
            }

            $options = $newOptions;
        }

        $this->options = $options;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $key
     *
     * @return $this
     */
    public function setPreselectedOption($key)
    {
        $this->setValue($key);

        return $this;
    }

    /**
     * @return string
     */
    protected function renderElementItemHtml()
    {
        $rendered = [];
        $options = $this->getOptions();

        foreach ($options as $key => $label)
        {
            $tmpl = $this->getElementItemHtml();

            $active = null;
            $checked = null;

            if ($this->getValue() === $key)
            {
                $active = 'active';
                $checked = 'checked';
            }

            $placeholder = [
                'id'      => $this->getId(),
                'value'   => $key,
                'label'   => $label,
                'active'  => $active,
                'checked' => $checked,
                'attrs'   => $this->getAttributesString(),
            ];

            $rendered[] = $this->replaceFieldPlaceholderMany($placeholder, $tmpl);
        }

        return join('', $rendered);
    }

    /**
     * @return string
     */
    public function renderElementHtml()
    {
        // render elm
        $elementHtml = $this->replaceFieldPlaceholder('items', $this->renderElementItemHtml(), $this->getElementHtml());

        return $this->parseFieldPlaceholders($elementHtml);
    }
}