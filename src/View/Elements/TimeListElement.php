<?php

namespace Simplon\Form\View\Elements;

/**
 * Class TimeListElement
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
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param int $interval
     *
     * @return TimeListElement
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;

        return $this;
    }

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
     * @return TimeListElement
     */
    public function isNone($isNone)
    {
        $this->isNone = $isNone === true;

        return $this;
    }

    /**
     * @return bool
     */
    protected function hasOptions()
    {
        return true;
    }

    /**
     * @return mixed|null
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