<?php

namespace Simplon\Form\Elements\CheckboxMulti;

use Simplon\Form\Elements\CoreElement;

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
    protected $elementItemHtml = '<div><label for=":md5" class="checkbox"><input type="checkbox" id=":md5" name=":id[]" value=":value" :checked :attrs>:label</label></div>';

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
     * @param array|null $options
     *
     * @return $this
     */
    public function setPreselectedOption(array $options = null)
    {
        if ($options !== null)
        {
            foreach ($options as $key)
            {
                $this->setOptionChecked($key, true);
            }
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
     * @param array $value
     *
     * @return static
     */
    public function setValue($value)
    {
        $this->setPreselectedOption($value);

        return parent::setValue($value);
    }

    /**
     * @param null $postValue
     *
     * @return static
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
     * @param array $requestData
     *
     * @return static
     */
    public function setPostValueByRequestData(array $requestData)
    {
        // any checkbox option selected?
        if (isset($requestData[$this->id]))
        {
            return $this->setPostValue($requestData[$this->id]);
        }

        // if form has been submitted but no option has been selected
        if (empty($requestData) === false)
        {
            $this->setPostValue([]);
        }

        return $this;
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
            'label'       => $this->getLabel(),
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

            $md5 = md5($this->getId() . $key);

            $placeholder = [
                'id'      => $this->getId(),
                'value'   => $key,
                'label'   => $label,
                'active'  => $active,
                'checked' => $checked,
                'attrs'   => $this->getAttributesString(),
                'md5'     => $md5,
            ];

            $rendered[] = $this->replaceFieldPlaceholderMany($placeholder, $tmpl);
        }

        return join('', $rendered);
    }
}