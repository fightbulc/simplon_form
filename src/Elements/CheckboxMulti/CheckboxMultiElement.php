<?php

namespace Simplon\Form\Elements\CheckboxMulti;

use Simplon\Form\Elements\CoreElement;
use Simplon\Form\Elements\CoreElementInterface;

/**
 * CheckboxMultiElement
 * @package Simplon\Form\Elements\CheckboxMulti
 * @author  Tino Ehrich (tino@bigpun.me)
 */
class CheckboxMultiElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<div id=":id">:items</div>';

    /**
     * @var string
     */
    protected $elementItemHtml = '<label class="checkbox"><input type="checkbox" name=":id[]" value=":value" :checked :attrs>:label</label>';

    /**
     * @var bool
     */
    protected $useOptionKeys = true;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $checked = [];

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
    protected function getUseOptionKeys()
    {
        return $this->useOptionKeys;
    }

    /**
     * @param bool $useKeys
     *
     * @return CheckboxMultiElement
     */
    public function setUseOptionKeys($useKeys)
    {
        $this->useOptionKeys = $useKeys === true;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return CheckboxMultiElement
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
     * @param array $options
     *
     * @return $this
     */
    public function setPreselectedOption(array $options)
    {
        foreach ($options as $key)
        {
            $this->setOptionChecked($key, true);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function ignorePreselectedOptions()
    {
        $this->checked = [];

        return $this;
    }

    /**
     * @param $key
     * @param $checked
     *
     * @return $this
     */
    public function setOptionChecked($key, $checked)
    {
        $this->checked[$key] = $checked === true ? true : false;

        return $this;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function getOptionChecked($key)
    {
        if (isset($this->checked[$key]))
        {
            return $this->checked[$key];
        }

        return false;
    }

    /**
     * @return array
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * @return bool
     */
    public function hasCheckedOption()
    {
        return count($this->getChecked()) > 0 ? true : false;
    }

    /**
     * @param null $postValue
     *
     * @return CoreElementInterface
     */
    public function setPostValue($postValue)
    {
        parent::setPostValue($postValue);

        // ignore default values
        $this->ignorePreselectedOptions();

        // handle post values
        if ($this->hasPostValue())
        {
            $this->setPreselectedOption($this->getPostValue());
        }
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

    /**
     * @return array
     */
    protected function getFieldPlaceholders()
    {
        return [
            'id'          => $this->getAttrId(),
            'name'        => $this->getName(),
            'class'       => $this->getClassString(),
            'description' => $this->getDescription(),
            'hasError'    => '',
        ];
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

            if ($this->getOptionChecked($key) === true)
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
}