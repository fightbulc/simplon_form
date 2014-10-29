<?php

namespace Simplon\Form\Elements\Select;

use Simplon\Form\Elements\CoreElement;

class SelectElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<div class=":hasError"><select name=":id" id=":id" class=":class">:options</select></div>';

    /**
     * @var array
     */
    protected $class = ['form-control'];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var null
     */
    protected $placeholder = null;

    /**
     * @var array
     */
    protected $topSplitKeys = [];

    /**
     * @var array
     */
    protected $bottomSplitKeys = [];

    /**
     * @var bool
     */
    protected $useOptionKeys = true;

    /**
     * @var bool
     */
    protected $sortByLabel = false;

    /**
     * @var string
     */
    protected $sortByLabelDirection = 'asc';

    /**
     * @return string
     */
    public function getElementOptionsHtml()
    {
        return '<option value=":value":selected>:label</option>';
    }

    /**
     * @param $value
     * @param $label
     * @param $selectedValue
     *
     * @return string
     */
    protected function renderElementOptionsHtml($value, $label, $selectedValue = false)
    {
        $tmpl = $this->getElementOptionsHtml();
        $tmpl = $this->replaceFieldPlaceholder('value', $value, $tmpl);
        $tmpl = $this->replaceFieldPlaceholder('label', $label, $tmpl);
        $tmpl = $this->replaceFieldPlaceholder('selected', $selectedValue === true ? ' selected' : null, $tmpl);

        return $tmpl;
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
     * @param $label
     *
     * @return $this
     */
    public function setPlaceholder($label)
    {
        $this->placeholder = $label;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @return array
     */
    protected function getFieldPlaceholders()
    {
        $coreFieldPlaceholders = parent::getFieldPlaceholders();

        // add options
        $coreFieldPlaceholders['options'] = $this->getRenderedOptions();

        return $coreFieldPlaceholders;
    }

    /**
     * @param $optionSortByLabel
     * @param string $direction
     *
     * @return $this
     */
    public function setSortByLabel($optionSortByLabel, $direction = 'asc')
    {
        $this->sortByLabel = $optionSortByLabel === true ? true : false;

        if (in_array($direction, ['asc', 'desc']))
        {
            $this->sortByLabelDirection = $direction;
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function getSortByLabel()
    {
        return $this->sortByLabel;
    }

    /**
     * @return boolean
     */
    protected function getSortByLabelDirection()
    {
        return $this->sortByLabelDirection;
    }

    /**
     * @param array $optionBottomSplitKeys
     *
     * @return SelectElement
     */
    public function setBottomSplitKeys($optionBottomSplitKeys)
    {
        $this->bottomSplitKeys = $optionBottomSplitKeys;

        return $this;
    }

    /**
     * @return array
     */
    public function getBottomSplitKeys()
    {
        return $this->bottomSplitKeys;
    }

    /**
     * @param array $optionTopSplitKeys
     *
     * @return SelectElement
     */
    public function setTopSplitKeys($optionTopSplitKeys)
    {
        $this->topSplitKeys = $optionTopSplitKeys;

        return $this;
    }

    /**
     * @return array
     */
    public function getTopSplitKeys()
    {
        return $this->topSplitKeys;
    }

    /**
     * @param boolean $useKeys
     *
     * @return SelectElement
     */
    public function setUseOptionKeys($useKeys)
    {
        $this->useOptionKeys = $useKeys === true;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getUseOptionKeys()
    {
        return $this->useOptionKeys;
    }

    /**
     * @param array $renderedOptions
     *
     * @return array
     */
    protected function renderPlaceholder(array $renderedOptions)
    {
        $optionLabel = $this->getPlaceholder();
        $currentSelectedValue = $this->getValue();

        if ($optionLabel !== null)
        {
            $isSelected = false;

            if ($currentSelectedValue === '')
            {
                $isSelected = true;
            }

            array_unshift($renderedOptions, $this->renderElementOptionsHtml('', $optionLabel, $isSelected));
        }

        return $renderedOptions;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function sortOptionsByLabel(array $options)
    {
        if ($this->getSortByLabel() === true)
        {
            if ($this->getSortByLabelDirection() === 'asc')
            {
                asort($options);
            }
            else
            {
                arsort($options);
            }
        }

        return $options;
    }

    /**
     * @return string
     */
    protected function getRenderedOptions()
    {
        $renderedOptions = [
            'top'    => [],
            'mid'    => [],
            'bottom' => [],
        ];

        $options = $this->getOptions();
        $topSplitKeys = $this->getTopSplitKeys();
        $bottomSplitKeys = $this->getBottomSplitKeys();
        $currentSelectedValue = (string)$this->getValue();

        // ----------------------------------

        // top split

        if (empty($topSplitKeys) === false)
        {
            // key comparision required
            $topSplitKeys = array_flip($topSplitKeys);

            // extract lists
            $topSplitOptions = array_intersect_key($options, $topSplitKeys);

            $options = array_diff_key($options, $topSplitKeys);

            // sort
            $topSplitOptions = $this->sortOptionsByLabel($topSplitOptions);

            // render options
            if (empty($topSplitOptions) === false)
            {
                foreach ($topSplitOptions as $value => $label)
                {
                    $isSelected = false;

                    if ($currentSelectedValue !== '' && (string)$value === $currentSelectedValue)
                    {
                        $isSelected = true;
                    }

                    $renderedOptions['top'][] = $this->renderElementOptionsHtml($value, $label, $isSelected);
                }
            }
        }

        // ----------------------------------

        // mid split

        // sort
        $options = $this->sortOptionsByLabel($options);

        // render options
        foreach ($options as $value => $label)
        {
            if (in_array($value, $bottomSplitKeys) === false)
            {
                $isSelected = false;

                if ($currentSelectedValue !== '' && (string)$value === $currentSelectedValue)
                {
                    $isSelected = true;
                }

                $renderedOptions['mid'][] = $this->renderElementOptionsHtml($value, $label, $isSelected);
            }
        }

        // ----------------------------------

        // bottom split

        if (!empty($bottomSplitKeys))
        {
            // key comparision required
            $bottomSplitKeys = array_flip($bottomSplitKeys);

            // extract lists
            $bottomSplitOptions = array_intersect_key($options, $bottomSplitKeys);

            // sort
            $bottomSplitOptions = $this->sortOptionsByLabel($bottomSplitOptions);

            // render options
            if (!empty($bottomSplitOptions))
            {
                foreach ($bottomSplitOptions as $value => $label)
                {
                    $isSelected = false;

                    if ($currentSelectedValue !== '' && (string)$value === $currentSelectedValue)
                    {
                        $isSelected = true;
                    }

                    $renderedOptions['bottom'][] = $this->renderElementOptionsHtml($value, $label, $isSelected);
                }
            }
        }

        // ----------------------------------

        // glue contents

        if (empty($renderedOptions['top']) === false && (empty($renderedOptions['mid']) === false || empty($renderedOptions['bottom']) === false))
        {
            $renderedOptions['top'][] = $this->renderElementOptionsHtml('-1', '----------');
        }

        if (empty($renderedOptions['mid']) === false && empty($renderedOptions['bottom']) === false)
        {
            $renderedOptions['mid'][] = $this->renderElementOptionsHtml('-1', '----------');
        }

        // ----------------------------------

        // render default label if defined
        $renderedOptions = $this->renderPlaceholder(
            array_merge($renderedOptions['top'], $renderedOptions['mid'], $renderedOptions['bottom'])
        );

        return join("\n", $renderedOptions);
    }

    /**
     * @return string
     */
    public function renderErrorMessages()
    {
        $messages = parent::renderErrorMessages();

        $messages = str_replace('rule-error-messages', 'rule-error-messages rule-error-messages-select', $messages);

        return $messages;
    }

    /**
     * @return string
     */
    protected function renderElementHtml()
    {
        return parent::renderElementHtml();
    }
}
