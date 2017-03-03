<?php

namespace Simplon\Form\View\Elements;

use Moment\Moment;
use Moment\MomentException;

/**
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
     * @return bool
     */
    public function hasNone(): bool
    {
        return $this->isNone;
    }

    /**
     * @param boolean $isNone
     *
     * @return DateListElement
     */
    public function isNone(bool $isNone): self
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
    public function getDays(): int
    {
        return $this->days;
    }

    /**
     * @param int $days
     *
     * @return DateListElement
     */
    public function setDays(int $days): self
    {
        $this->days = $days;

        return $this;
    }

    /**
     * @return bool
     */
    protected function hasOptions(): bool
    {
        return true;
    }

    /**
     * @return array
     * @throws MomentException
     */
    protected function getOptions(): array
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
    private function getMoment(): Moment
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
    private function getMetaFormat(): ?string
    {
        return $this->getField()->getMeta('format');
    }

    /**
     * @return string
     */
    private function getFormatValue(): string
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
    private function getFormatLabel(): string
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
    private function getFieldMetaFormatValue(): string
    {
        return $this->fieldMetaFormatValue;
    }

    /**
     * @return string
     */
    private function getFieldMetaFormatLabel(): string
    {
        return $this->fieldMetaFormatLabel;
    }
}