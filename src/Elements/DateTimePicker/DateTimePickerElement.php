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
     * @var DateTimePickerElement
     */
    protected $rangeFromElement;

    /**
     * @var bool
     */
    protected $optionPickDate = true;

    /**
     * @var bool
     */
    protected $optionPickTime = true;

    /**
     * @var bool
     */
    protected $optionUseMinutes = true;

    /**
     * @var bool
     */
    protected $optionUseSeconds = true;

    /**
     * @var bool
     */
    protected $optionUseCurrent = true;

    /**
     * @var int
     */
    protected $optionMinuteStepping = 1;

    /**
     * @var string
     */
    protected $optionMinDate = '1/1/1990';

    /**
     * @var null|string
     */
    protected $optionMaxDate = null;

    /**
     * @var bool
     */
    protected $optionShowToday = true;

    /**
     * @var string
     */
    protected $optionLanguage = 'en';

    /**
     * @var \DateTime
     */
    protected $optionDefaultDate;

    /**
     * @var array
     */
    protected $optionDisabledDates = []; // an array of dates that cannot be selected

    /**
     * @var array
     */
    protected $optionEnabledDates = []; // an array of dates that can be selected

    /**
     * @var array
     */
    protected $optionIcons = [
        'time' => 'glyphicon glyphicon-time',
        'date' => 'glyphicon glyphicon-calendar',
        'up'   => 'glyphicon glyphicon-chevron-up',
        'down' => 'glyphicon glyphicon-chevron-down',
    ];

    /**
     * @var bool
     */
    protected $optionUseStrictValidation = false;

    /**
     * @var bool
     */
    protected $optionSideBySide = false;

    /**
     * @var array
     */
    protected $optionDaysOfWeekDisabled = [];

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
     * @param array $optionDaysOfWeekDisabled
     *
     * @return DateTimePickerElement
     */
    public function setOptionDaysOfWeekDisabled($optionDaysOfWeekDisabled)
    {
        $this->optionDaysOfWeekDisabled = $optionDaysOfWeekDisabled;

        return $this;
    }

    /**
     * @param array $optionDisabledDates
     *
     * @return DateTimePickerElement
     */
    public function setOptionDisabledDates($optionDisabledDates)
    {
        $this->optionDisabledDates = $optionDisabledDates;

        return $this;
    }

    /**
     * @param array $optionEnabledDates
     *
     * @return DateTimePickerElement
     */
    public function setOptionEnabledDates($optionEnabledDates)
    {
        $this->optionEnabledDates = $optionEnabledDates;

        return $this;
    }

    /**
     * @param array $optionIcons
     *
     * @return DateTimePickerElement
     */
    public function setOptionIcons($optionIcons)
    {
        $this->optionIcons = $optionIcons;

        return $this;
    }

    /**
     * @param string $optionLanguage
     *
     * @return DateTimePickerElement
     */
    public function setOptionLanguage($optionLanguage)
    {
        $this->optionLanguage = $optionLanguage;

        return $this;
    }

    /**
     * @param null|string $optionMaxDate
     *
     * @return DateTimePickerElement
     */
    public function setOptionMaxDate($optionMaxDate)
    {
        $this->optionMaxDate = $optionMaxDate;

        return $this;
    }

    /**
     * @param string $optionMinDate
     *
     * @return DateTimePickerElement
     */
    public function setOptionMinDate($optionMinDate)
    {
        $this->optionMinDate = $optionMinDate;

        return $this;
    }

    /**
     * @param int $optionMinuteStepping
     *
     * @return DateTimePickerElement
     */
    public function setOptionMinuteStepping($optionMinuteStepping)
    {
        $this->optionMinuteStepping = $optionMinuteStepping;

        return $this;
    }

    /**
     * @param boolean $optionPickDate
     *
     * @return DateTimePickerElement
     */
    public function setOptionPickDate($optionPickDate)
    {
        $this->optionPickDate = $optionPickDate;

        return $this;
    }

    /**
     * @param boolean $optionPickTime
     *
     * @return DateTimePickerElement
     */
    public function setOptionPickTime($optionPickTime)
    {
        $this->optionPickTime = $optionPickTime;

        // adjust time options
        $this
            ->setOptionUseMinutes($optionPickTime)
            ->setOptionUseSeconds($optionPickTime);

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
     * @param boolean $optionShowToday
     *
     * @return DateTimePickerElement
     */
    public function setOptionShowToday($optionShowToday)
    {
        $this->optionShowToday = $optionShowToday;

        return $this;
    }

    /**
     * @param boolean $optionSideBySide
     *
     * @return DateTimePickerElement
     */
    public function setOptionSideBySide($optionSideBySide)
    {
        $this->optionSideBySide = $optionSideBySide;

        return $this;
    }

    /**
     * @param boolean $optionUseCurrent
     *
     * @return DateTimePickerElement
     */
    public function setOptionUseCurrent($optionUseCurrent)
    {
        $this->optionUseCurrent = $optionUseCurrent;

        return $this;
    }

    /**
     * @param boolean $optionUseMinutes
     *
     * @return DateTimePickerElement
     */
    public function setOptionUseMinutes($optionUseMinutes)
    {
        $this->optionUseMinutes = $optionUseMinutes;

        return $this;
    }

    /**
     * @param boolean $optionUseSeconds
     *
     * @return DateTimePickerElement
     */
    public function setOptionUseSeconds($optionUseSeconds)
    {
        $this->optionUseSeconds = $optionUseSeconds;

        return $this;
    }

    /**
     * @param boolean $optionUseStrictValidation
     *
     * @return DateTimePickerElement
     */
    public function setOptionUseStrictValidation($optionUseStrictValidation)
    {
        $this->optionUseStrictValidation = $optionUseStrictValidation;

        return $this;
    }

    /**
     * @param \DateTime $optionDefaultDate
     *
     * @return DateTimePickerElement
     */
    public function setOptionDefaultDate(\DateTime $optionDefaultDate)
    {
        $this->optionDefaultDate = $optionDefaultDate;

        return $this;
    }

    /**
     * @return void
     */
    public function setup()
    {
        // required assets
        $this->addAssetFile('moment-js-2.8.3/moment-with-locales.min.js');
        $this->addAssetFile('bootstrap-datetimepicker-3.1.3/bootstrap-datetimepicker.min.css');
        $this->addAssetFile('bootstrap-datetimepicker-3.1.3/bootstrap-datetimepicker.min.js');

        // init field
        $json = $this->getOptionsAsJson();
        $json = preg_replace('/"new Date\((\d+), (\d+), (\d+)\)"/i', 'new Date(\\1, \\2, \\3)', $json);
        $this->addAssetInline("$('#{$this->getId()}').datetimepicker({$json})");

        if ($this->hasRangeFromElement() === true)
        {
            $fromId = $this->getRangeFromElement()->getId();
            $toId = $this->getId();

            // handle range conditions
            $this->addAssetInline("$('#{$fromId}').on('dp.change', function (e) { $('#{$toId}').data('DateTimePicker').setMinDate(e.date); })");
            $this->addAssetInline("$('#{$toId}').on('dp.change', function (e) { $('#{$fromId}').data('DateTimePicker').setMaxDate(e.date); })");
        }
    }

    /**
     * @return string
     */
    private function getOptionsAsJson()
    {
        $options = [
            'pickDate'          => $this->getOptionPickDate(),
            'pickTime'          => $this->getOptionPickTime(),
            'useMinutes'        => $this->getOptionUseMinutes(),
            'useSeconds'        => $this->getOptionUseSeconds(),
            'useCurrent'        => $this->getOptionUseCurrent(),
            'minuteStepping'    => $this->getOptionMinuteStepping(),
            'minDate'           => $this->getOptionMinDate(),
            'maxDate'           => $this->getOptionMaxDate(),
            'showToday'         => $this->getOptionShowToday(),
            'language'          => $this->getOptionLanguage(),
            'defaultDate'       => $this->getOptionDefaultDate(),
            'disabledDates'     => $this->getOptionDisabledDates(),
            'enabledDates'      => $this->getOptionEnabledDates(),
            'icons'             => $this->getOptionIcons(),
            'useStrict'         => $this->getOptionUseStrictValidation(),
            'sideBySide'        => $this->getOptionSideBySide(),
            'dayOfWeekDisabled' => $this->getOptionDaysOfWeekDisabled(),
        ];

        return json_encode($options);
    }

    /**
     * @return \DateTime|null
     */
    private function getOptionDefaultDate()
    {
        if ($this->optionDefaultDate instanceof \DateTime)
        {
            return 'new Date(' . $this->optionDefaultDate->format('Y') . ', ' . ($this->optionDefaultDate->format('m') - 1) . ', ' . $this->optionDefaultDate->format('d') . ')';
        }

        return null;
    }

    /**
     * @return array
     */
    private function getOptionDaysOfWeekDisabled()
    {
        return $this->optionDaysOfWeekDisabled;
    }

    /**
     * @return array
     */
    private function getOptionDisabledDates()
    {
        return $this->optionDisabledDates;
    }

    /**
     * @return array
     */
    private function getOptionEnabledDates()
    {
        return $this->optionEnabledDates;
    }

    /**
     * @return array
     */
    private function getOptionIcons()
    {
        return $this->optionIcons;
    }

    /**
     * @return string
     */
    private function getOptionLanguage()
    {
        return $this->optionLanguage;
    }

    /**
     * @return null|string
     */
    private function getOptionMaxDate()
    {
        return $this->optionMaxDate;
    }

    /**
     * @return string
     */
    private function getOptionMinDate()
    {
        return $this->optionMinDate;
    }

    /**
     * @return int
     */
    private function getOptionMinuteStepping()
    {
        return $this->optionMinuteStepping;
    }

    /**
     * @return boolean
     */
    private function getOptionPickDate()
    {
        return $this->optionPickDate;
    }

    /**
     * @return boolean
     */
    private function getOptionPickTime()
    {
        return $this->optionPickTime;
    }

    /**
     * @return boolean
     */
    private function getOptionShowToday()
    {
        return $this->optionShowToday;
    }

    /**
     * @return boolean
     */
    private function getOptionSideBySide()
    {
        return $this->optionSideBySide;
    }

    /**
     * @return boolean
     */
    private function getOptionUseCurrent()
    {
        return $this->optionUseCurrent;
    }

    /**
     * @return boolean
     */
    private function getOptionUseMinutes()
    {
        return $this->optionUseMinutes;
    }

    /**
     * @return boolean
     */
    private function getOptionUseSeconds()
    {
        return $this->optionUseSeconds;
    }

    /**
     * @return boolean
     */
    private function getOptionUseStrictValidation()
    {
        return $this->optionUseStrictValidation;
    }
}