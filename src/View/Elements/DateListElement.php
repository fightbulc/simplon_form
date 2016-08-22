<?php

namespace Simplon\Form\View\Elements;

use Moment\Moment;
use Moment\MomentException;

/**
 * Class DateListElement
 * @package Simplon\Form\View\Elements
 */
class DateListElement extends DropDownElement
{
    /**
     * @var bool
     */
    private $isNone = false;

    /**
     * @var int
     */
    private $days = 7;

    /**
     * @var string|int
     */
    private $startingDate;

    /**
     * @var string
     */
    private $fieldMetaFormatValue = 'Y-m-d';

    /**
     * @var string
     */
    private $fieldMetaFormatLabel = 'D, d.m.Y';

    /**
     * @return boolean
     */
    public function hasNone()
    {
        return $this->isNone;
    }

    /**
     * @param boolean $isNone
     *
     * @return DateListElement
     */
    public function isNone($isNone)
    {
        $this->isNone = $isNone === true;

        return $this;
    }

    /**
     * @return int|string
     */
    public function getStartingDate()
    {
        return $this->startingDate;
    }

    /**
     * @param int|string $startingDate
     *
     * @return DateListElement
     */
    public function setStartingDate($startingDate)
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @param int $days
     *
     * @return DateListElement
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasSearchable()
    {
        return true;
    }

    /**
     * @return bool
     */
    protected function hasOptions()
    {
        return true;
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        $options = [];

        if ($this->hasNone())
        {
            $options[] = [
                'value' => 'none',
                'label' => 'None',
            ];
        }

        $moment = $this->getMoment();
        $options = [];

        for ($d = 0; $d < $this->getDays(); $d++)
        {
            $options[] = [
                'value' => $moment->format($this->getFormatValue()),
                'label' => $moment->format($this->getFormatLabel()),
            ];

            $moment->addDays(1);
        }

        return $options;
    }

    /**
     * @return int|string
     */
    private function getDateValue()
    {
        $value = $this->getStartingDate();

        if ($value === null)
        {
            $value = $this->getField()->getInitialValue();

            if ($value === null)
            {
                $value = 'now';
            }
        }

        return $value;
    }

    /**
     * @return Moment
     * @throws MomentException
     */
    private function getMoment()
    {
        $value = $this->getDateValue();

        if (preg_match('/^\d+$/', $value))
        {
            $value = '@' . $value;
        }

        return new Moment($value);
    }

    /**
     * @return string|null
     */
    private function getMetaFormat()
    {
        return $this->getField()->getMeta('format');
    }

    /**
     * @return string
     */
    private function getFormatValue()
    {
        $format = $this->getFieldMetaFormatValue();

        if ($this->getMetaFormat() && empty($this->getMetaFormat()['value']) === false)
        {
            $format = $this->getMetaFormat()['value'];
        }

        return $format;
    }

    /**
     * @return string
     */
    private function getFormatLabel()
    {
        $format = $this->getFieldMetaFormatLabel();

        if ($this->getMetaFormat() && empty($this->getMetaFormat()['label']) === false)
        {
            $format = $this->getMetaFormat()['label'];
        }

        return $format;
    }

    /**
     * @return string
     */
    private function getFieldMetaFormatValue()
    {
        return $this->fieldMetaFormatValue;
    }

    /**
     * @return string
     */
    private function getFieldMetaFormatLabel()
    {
        return $this->fieldMetaFormatLabel;
    }
}