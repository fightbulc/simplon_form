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
    protected $elementInlineHtml = '<div id=":id" class="rd-inline"><input type="hidden" name=":name" id=":id_value" value=":value"></div>';

    /**
     * @var \DateTime
     */
    protected $minDate;

    /**
     * @var \DateTime
     */
    protected $maxDate;

    /**
     * @var bool
     */
    protected $hasDate = true;

    /**
     * @var bool
     */
    protected $hasTime = true;

    /**
     * @var int
     */
    protected $numberOfVisibleMonths = 1;

    /**
     * @var int
     */
    protected $weeksStartingDay = 1;

    /**
     * @var string
     */
    protected $dateValidatorCode = 'return true;';

    /**
     * @var string
     */
    protected $formatDate = 'YYYY-MM-DD';

    /**
     * @var string
     */
    protected $formatTime = 'HH:mm';

    /**
     * @var bool
     */
    protected $useInline = false;

    /**
     * @var DateTimePickerElement
     */
    protected $rangeToElement;

    /**
     * @var DateTimePickerElement
     */
    protected $rangeFromElement;

    /**
     * @param boolean $hasDate
     *
     * @return DateTimePickerElement
     */
    public function setHasDate($hasDate)
    {
        $this->hasDate = $hasDate === true;

        return $this;
    }

    /**
     * @param boolean $hasTime
     *
     * @return DateTimePickerElement
     */
    public function setHasTime($hasTime)
    {
        $this->hasTime = $hasTime === true;

        return $this;
    }

    /**
     * @param \DateTime $maxDate
     *
     * @return DateTimePickerElement
     */
    public function setMaxDate(\DateTime $maxDate)
    {
        $this->maxDate = $maxDate;

        return $this;
    }

    /**
     * @param \DateTime $minDate
     *
     * @return DateTimePickerElement
     */
    public function setMinDate(\DateTime $minDate)
    {
        $this->minDate = $minDate;

        return $this;
    }

    /**
     * @param int $numberOfVisibleMonths
     *
     * @return DateTimePickerElement
     */
    public function setNumberOfVisibleMonths($numberOfVisibleMonths)
    {
        $this->numberOfVisibleMonths = $numberOfVisibleMonths;

        return $this;
    }

    /**
     * @param int $weeksStartingDay
     *
     * @return DateTimePickerElement
     */
    public function setWeeksStartingDay($weeksStartingDay)
    {
        $this->weeksStartingDay = $weeksStartingDay;

        return $this;
    }

    /**
     * @param string $dateValidatorCode
     *
     * @return DateTimePickerElement
     */
    public function setDateValidatorCode($dateValidatorCode)
    {
        $this->dateValidatorCode = $dateValidatorCode;

        return $this;
    }

    /**
     * @param boolean $useInline
     *
     * @return DateTimePickerElement
     */
    public function setUseInline($useInline)
    {
        $this->useInline = $useInline === true;

        return $this;
    }

    /**
     * @param DateTimePickerElement $rangeToElement
     *
     * @return DateTimePickerElement
     */
    public function setRangeToElement(DateTimePickerElement $rangeToElement)
    {
        $this->rangeToElement = $rangeToElement;

        return $this;
    }

    /**
     * @param DateTimePickerElement $rangeFromElement
     *
     * @return DateTimePickerElement
     */
    public function setRangeFromElement(DateTimePickerElement $rangeFromElement)
    {
        $this->rangeFromElement = $rangeFromElement;

        // cross reference
        $rangeFromElement->setRangeToElement($this);

        return $this;
    }

    /**
     * @param \DateTime $value
     *
     * @return DateTimePickerElement
     */
    public function setValue($value)
    {
        if ($value instanceof \DateTime)
        {
            parent::setValue($value->format('c'));
        }

        return $this;
    }

    /**
     * @param array $requestData
     *
     * @return void
     */
    public function setup(array $requestData)
    {
        // required assets
        $this->addAssetFile('moment-js-2.8.3/moment-with-locales.min.js');
        $this->addAssetFile('rome-1.2.3/dist/rome.standalone.min.js');
        $this->addAssetFile('rome-1.2.3/dist/rome-custom.css');

        // element request value
        if ($this->getUseInline() === true && isset($requestData[$this->getId()]))
        {
            $value = $requestData[$this->getId()];
        }
        else
        {
            $value = $this->getValue();
        }

        // options
        $options = [
            'min'              => '"' . $this->getMinDate() . '"',
            'max'              => '"' . $this->getMaxDate() . '"',
            'date'             => $this->getHasDate() === true ? 'true' : 'false',
            'time'             => $this->getHasTime() === true ? 'true' : 'false',
            'monthsInCalendar' => $this->getNumberOfVisibleMonths(),
            'weekStart'        => $this->getWeeksStartingDay(),
            'dateValidator'    => $this->getDateValidatorCode(),
            'inputFormat'      => '"' . $this->getFormat() . '"',
            'initialValue'     => '"' . $value . '"',
        ];

        // render options as JS object
        $json = [];

        foreach ($options as $k => $v)
        {
            $json[$k] = $k . ': ' . $v;
        }

        // load widget
        $this->addAssetInline('rome(' . $this->getId() . ', {' . join(', ', $json) . '})');

        // handle inline
        if ($this->getUseInline() === true)
        {
            $this->elementHtml = $this->elementInlineHtml;
            $this->addAssetInline('rome(' . $this->getId() . ').on("data", function (value) { document.getElementById("' . $this->getId() . '_value").value = value; })');
        }
    }

    /**
     * @return boolean
     */
    private function getHasDate()
    {
        return $this->hasDate;
    }

    /**
     * @return boolean
     */
    private function getHasTime()
    {
        return $this->hasTime;
    }

    /**
     * @return string|null
     */
    private function getMaxDate()
    {
        if ($this->maxDate instanceof \DateTime)
        {
            return $this->maxDate->format('c');
        }

        return null;
    }

    /**
     * @return string|null
     */
    private function getMinDate()
    {
        if ($this->minDate instanceof \DateTime)
        {
            return $this->minDate->format('c');
        }

        return null;
    }

    /**
     * @return int
     */
    private function getNumberOfVisibleMonths()
    {
        return $this->numberOfVisibleMonths;
    }

    /**
     * @return int
     */
    private function getWeeksStartingDay()
    {
        return $this->weeksStartingDay;
    }

    /**
     * @return string
     */
    private function getDateValidatorCode()
    {
        if ($this->getRangeToElement() !== null)
        {
            return 'rome.val.beforeEq(' . $this->getRangeToElement()->getId() . ')';
        }
        elseif ($this->getRangeFromElement() !== null)
        {
            return 'rome.val.afterEq(' . $this->getRangeFromElement()->getId() . ')';
        }

        return 'function(d){' . $this->dateValidatorCode . '}';
    }

    /**
     * @return string
     */
    private function getFormat()
    {
        $format = [];

        if ($this->getHasDate() === true)
        {
            $format[] = $this->formatDate;
        }

        if ($this->getHasTime() === true)
        {
            $format[] = $this->formatTime;
        }

        return join(' ', $format);
    }

    /**
     * @return boolean
     */
    private function getUseInline()
    {
        return $this->useInline;
    }

    /**
     * @return DateTimePickerElement
     */
    private function getRangeToElement()
    {
        return $this->rangeToElement;
    }

    /**
     * @return DateTimePickerElement
     */
    private function getRangeFromElement()
    {
        return $this->rangeFromElement;
    }
}