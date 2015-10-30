<?php

namespace Simplon\Form\View\Elements\Support\Typeahead;

/**
 * Class TypeaheadSourceTrait
 * @package Simplon\Form\View\Elements\Support\Typeahead
 */
trait TypeaheadSourceTrait
{
    /**
     * @var string
     */
    private $idAttr;

    /**
     * @var string
     */
    private $labelAttr;


    /**
     * @return string
     */
    public function getIdAttr()
    {
        return $this->idAttr;
    }

    /**
     * @param string $idAttr
     *
     * @return static
     */
    public function setIdAttr($idAttr)
    {
        $this->idAttr = $idAttr;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabelAttr()
    {
        return $this->labelAttr;
    }

    /**
     * @param string $labelAttr
     *
     * @return static
     */
    public function setLabelAttr($labelAttr)
    {
        $this->labelAttr = $labelAttr;

        return $this;
    }
}