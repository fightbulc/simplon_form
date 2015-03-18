<?php

namespace Simplon\Form\Elements\CheckboxSingle;

use Simplon\Form\Elements\CoreElement;
use Simplon\Form\Elements\CoreElementInterface;

/**
 * CheckboxSingleElement
 * @package Simplon\Form\Elements\CheckboxSingle
 * @author  Tino Ehrich (tino@bigpun.me)
 */
class CheckboxSingleElement extends CoreElement
{
    /**
     * @var string
     */
    protected $elementHtml = '<div class=":hasError"><label for=":id" class="checkbox"><input type="checkbox" name=":id" value=":value" :checked :attrs>:label</label></div>';

    /**
     * @var bool
     */
    protected $isChecked = false;

    /**
     * @param $isChecked
     *
     * @return CheckboxSingleElement
     */
    public function setIsChecked($isChecked)
    {
        $this->isChecked = $isChecked === true;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasCheckedOption()
    {
        return $this->isChecked;
    }

    /**
     * @param null $postValue
     *
     * @return CoreElementInterface
     */
    public function setPostValue($postValue)
    {
        parent::setPostValue($postValue);

        if ($this->hasPostValue())
        {
            $this->setIsChecked(true);
        }
    }

    /**
     * @return array
     */
    protected function getFieldPlaceholders()
    {
        return [
            'id'          => $this->getId(),
            'label'       => $this->getLabel(),
            'value'       => $this->getValue(),
            'checked'     => $this->hasCheckedOption() ? 'checked' : null,
            'class'       => $this->getClassString(),
            'attrs'       => $this->getAttributesString(),
            'description' => $this->getDescription(),
            'hasError'    => '',
        ];
    }
}