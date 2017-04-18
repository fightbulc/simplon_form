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
    private $formatOptionValue = 'Y-m-d';
    /**
     * @var string
     */
    private $formatOptionLabel = 'D, d.m.Y';

    /**
     * @return bool
     */
    public function hasNone(): bool
    {
        return $this->isNone;
    }

    /**
     * @return DateListElement
     */
    public function enableNone(): self
    {
        $this->isNone = true;

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
     * @param string $formatOptionValue
     *
     * @return DateListElement
     */
    public function setFormatOptionValue(string $formatOptionValue): DateListElement
    {
        $this->formatOptionValue = $formatOptionValue;

        return $this;
    }

    /**
     * @param string $formatOptionLabel
     *
     * @return DateListElement
     */
    public function setFormatOptionLabel(string $formatOptionLabel): DateListElement
    {
        $this->formatOptionLabel = $formatOptionLabel;

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
                'value' => $moment->format($this->getFormatOptionValue()),
                'label' => $moment->format($this->getFormatOptionLabel()),
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
     * @return string
     */
    private function getFormatOptionValue(): string
    {
        return $this->formatOptionValue;
    }

    /**
     * @return string
     */
    private function getFormatOptionLabel(): string
    {
        return $this->formatOptionLabel;
    }
}