<?php

namespace Simplon\Form\Elements\DateTimePicker;

use Simplon\Form\Elements\TextSingleLine\TextSingleLineElement;

/**
 * DateTimePickerElement
 * @package Simplon\Form\Elements\DateTimePicker
 * @author Tino Ehrich (tino@bigpun.me)
 */
class DateTimePickerElement extends TextSingleLineElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<div class=":hasError input-group date"><input type="text" class=":class" name=":id" id=":id" value=":value" placeholder=":placeholder"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var DateTimePickerElement
     */
    protected $rangeFromElement;

    /**
     * @return DateTimePickerElement
     */
    protected function getRangeFromElement()
    {
        return $this->rangeFromElement;
    }

    /**
     * @return bool
     */
    protected function hasRangeFromElement()
    {
        return $this->getRangeFromElement() instanceof DateTimePickerElement;
    }

    /**
     * @param DateTimePickerElement $rangeFromElement
     *
     * @return DateTimePickerElement
     */
    public function setRangeFromElement(DateTimePickerElement $rangeFromElement)
    {
        $this->rangeFromElement = $rangeFromElement;

        return $this;
    }

    /**
     * @return string
     */
    protected function getOptionsAsJson()
    {
        return json_encode($this->options);
    }

    /**
     * @param string $key
     * @param mixed $val
     *
     * @return DateTimePickerElement
     */
    public function addOption($key, $val)
    {
        $this->options[$key] = $val;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return DateTimePickerElement
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array
     */
    public function render()
    {
        $this->addAssetFile("vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css");
        $this->addAssetFile("vendor/moment-js/moment-with-locales.min.js");
        $this->addAssetFile("vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js");
        $this->addAssetInline("$('#{$this->getId()}').datetimepicker({$this->getOptionsAsJson()})");

        if ($this->hasRangeFromElement() === true)
        {
            $fromId = $this->getRangeFromElement()->getId();
            $toId = $this->getId();

            $this->addAssetInline("$('#{$fromId}').on('dp.change', function (e) { $('#{$toId}').data('DateTimePicker').setMinDate(e.date); })");
            $this->addAssetInline("$('#{$toId}').on('dp.change', function (e) { $('#{$fromId}').data('DateTimePicker').setMaxDate(e.date); })");
        }

        return parent::render();
    }
}