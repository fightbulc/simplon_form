<?php

namespace Simplon\Form\Elements\Checkbox;

use Simplon\Form\Elements\CoreElement;

class CheckboxElement extends CoreElement
{
    protected $elementHtml = '<div id=":id">:items</div>';
    protected $elementItemHtml = '<label class="checkbox"><input type="checkbox" name=":id[]" value=":value" data-toggle="checkbox":checked>:label</label>';
    protected $useOptionKeys = true;
    protected $options = [];
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
     * @return CheckboxElement
     */
    public function setUseOptionKeys($useKeys)
    {
        $this->useOptionKeys = $useKeys === true;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return CheckboxElement
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
    public function hasCheckedOptions()
    {
        return count($this->getChecked()) > 0 ? true : false;
    }

    /**
     * @param null $postValue
     *
     * @return CoreElement
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
                $active = ' active';
                $checked = ' checked';
            }

            $placeholder = [
                'id'      => $this->getId(),
                'value'   => $key,
                'label'   => $label,
                'active'  => $active,
                'checked' => $checked,
            ];

            $rendered[] = $this->replaceFieldPlaceholderMany($placeholder, $tmpl);
        }

        return join('', $rendered);
    }

    /**
     * @return string
     */
    protected function renderElementHtml()
    {
        // set JS
        $this->addJs("$('#" . $this->getId() . ":checkbox').checkbox()");

        // render elm
        $elementHtml = $this->replaceFieldPlaceholder('items', $this->renderElementItemHtml(), $this->getElementHtml());

        return $this->parseFieldPlaceholders($elementHtml);
    }
}