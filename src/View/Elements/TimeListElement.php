<?php

namespace Simplon\Form\View\Elements;

/**
 * @package Simplon\Form\View\Elements
 */
class TimeListElement extends DropDownElement
{
    /**
     * @var int
     */
    private $interval = 15;
    /**
     * @var bool
     */
    private $isNone = false;

    /**
     * @return int
     */
    public function getInterval(): int
    {
        return $this->interval;
    }

    /**
     * @param int $interval
     *
     * @return TimeListElement
     */
    public function setInterval(int $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasNone(): bool
    {
        return $this->isNone;
    }

    /**
     * @return TimeListElement
     */
    public function enableNone(): self
    {
        $this->isNone = true;

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
     * @return array|null
     */
    protected function getOptions(): ?array
    {
        $options = [];

        if ($this->hasNone())
        {
            $options[] = [
                'value' => 'none',
                'label' => 'None',
            ];
        }

        for ($h = 0; $h < 24; $h++)
        {
            for ($m = 0; $m < 60; $m += $this->getInterval())
            {
                $options[] = [
                    'value' => ($h < 10 ? '0' . $h : $h) . ':' . ($m < 10 ? '0' . $m : $m),
                ];
            }
        }

        return $options;
    }
}