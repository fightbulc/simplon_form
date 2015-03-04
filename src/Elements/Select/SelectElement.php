<?php

namespace Simplon\Form\Elements\Select;

use Simplon\Form\Elements\CoreElement;

/**
 * SelectElement
 * @package Simplon\Form\Elements\Select
 * @author Tino Ehrich (tino@bigpun.me)
 */
class SelectElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<div class=":hasError"><select name=":name" id=":id" class=":class" :attrs>:options</select></div>';

    /**
     * @var array
     */
    protected $class = [];

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

            $assignKey = function (array $options, $key)
            {
                $options[$key] = $key;

                return $options;
            };

            // handle drop downs with optgroups
            if ($this->hasOptGroup($options) === true)
            {
                $newOptions = [
                    'optgroups' => [],
                ];

                foreach ($options['optgroups'] as $optGroupKey => $optGroupValues)
                {
                    $newOptions['optgroups'][$optGroupKey] = [
                        'label'   => $optGroupValues['label'],
                        'options' => [],
                    ];

                    foreach ($optGroupValues['options'] as $optGroupOptionValue)
                    {
                        $newOptions['optgroups'][$optGroupKey]['options'] = $assignKey(
                            $newOptions['optgroups'][$optGroupKey]['options'],
                            $optGroupOptionValue
                        );
                    }
                }

                $options = $newOptions;
            }

            // handle normal drop downs
            else
            {
                foreach ($options as $option)
                {
                    $newOptions = $assignKey($newOptions, $option);
                }

                $options = $newOptions;
            }
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
     * @return string
     */
    public function renderErrorMessages()
    {
        $messages = parent::renderErrorMessages();

        $messages = str_replace('rule-error-messages', 'rule-error-messages rule-error-messages-select', $messages);

        return $messages;
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    protected function hasOptGroup(array $options)
    {
        return isset($options['optgroups']) === true;
    }

    /**
     * @return array
     */
    protected function getFieldPlaceholders()
    {
        $coreFieldPlaceholders = parent::getFieldPlaceholders();

        // add options
        $coreFieldPlaceholders['options'] = $this->getRenderedOptions($this->getOptions());

        return $coreFieldPlaceholders;
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

            // reset placeholder
            $this->setPlaceholder(null);
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
     * @param array $options
     *
     * @return string
     */
    protected function getRenderedOptions(array $options)
    {
        $currentSelectedValue = $this->getValue();
        $renderedOptions = [];

        // render with optgroups
        if ($this->hasOptGroup($options) === true)
        {
            // render placeholder
            $renderedOptions = $this->renderPlaceholder($renderedOptions);

            foreach ($options['optgroups'] as $optgroup)
            {
                // call yourself to render optgroup options
                $renderedOptions[] = '<optgroup label="' . $optgroup['label'] . '">' . $this->getRenderedOptions($optgroup['options']) . '</optgroup>';
            }

            return join("\n", $renderedOptions);
        }

        // --------------------------------------

        // top split
        $topSplitKeys = $this->getTopSplitKeys();

        if (!empty($topSplitKeys))
        {
            // key comparision required
            $topSplitKeys = array_flip($topSplitKeys);

            // extract lists
            $topSplitOptions = array_intersect_key($options, $topSplitKeys);

            $options = array_diff_key($options, $topSplitKeys);

            // sort
            $topSplitOptions = $this->sortOptionsByLabel($topSplitOptions);

            // render options
            if (!empty($topSplitOptions))
            {
                foreach ($topSplitOptions as $value => $label)
                {
                    $isSelected = false;

                    if ($currentSelectedValue !== '' && $value === $currentSelectedValue)
                    {
                        $isSelected = true;
                    }

                    $renderedOptions[] = $this->renderElementOptionsHtml($value, $label, $isSelected);
                }

                $renderedOptions[] = $this->renderElementOptionsHtml('-1', '----------');
            }
        }

        // --------------------------------------

        // sort
        $options = $this->sortOptionsByLabel($options);

        // render options
        foreach ($options as $value => $label)
        {
            $isSelected = false;

            if ($currentSelectedValue !== '' && $value === $currentSelectedValue)
            {
                $isSelected = true;
            }

            $renderedOptions[] = $this->renderElementOptionsHtml($value, $label, $isSelected);
        }

        // --------------------------------------

        // bottom split
        $bottomSplitKeys = $this->getBottomSplitKeys();

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
                $renderedOptions[] = $this->renderElementOptionsHtml('-1', '----------');

                foreach ($bottomSplitOptions as $value => $label)
                {
                    $isSelected = false;

                    if ($currentSelectedValue !== '' && $value === $currentSelectedValue)
                    {
                        $isSelected = true;
                    }

                    $renderedOptions[] = $this->renderElementOptionsHtml($value, $label, $isSelected);
                }
            }
        }

        // --------------------------------------

        // render default label if defined
        $renderedOptions = $this->renderPlaceholder($renderedOptions);

        return join("\n", $renderedOptions);
    }
}