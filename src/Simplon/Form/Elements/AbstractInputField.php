<?php

  namespace Simplon\Form\Elements;

  abstract class AbstractInputField extends AbstractElement
  {
    /**
     * @param $value
     * @return AbstractInputField
     */
    public function setLabel($value)
    {
      $this->_setByKey('label', $value);

      return $this;
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    public function getLabel()
    {
      return $this->_getByKey('label');
    }

    // ##########################################

    /**
     * @param $value
     * @return AbstractInputField
     */
    public function setDescription($value)
    {
      $this->_setByKey('description', $value);

      return $this;
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    public function getDescription()
    {
      return $this->_getByKey('description');
    }

    // ##########################################

    /**
     * @param $value
     * @return AbstractInputField
     */
    public function setPlaceholder($value)
    {
      $this->_setByKey('placeholder', $value);

      return $this;
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    public function getPlaceholder()
    {
      return $this->_getByKey('placeholder');
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getRenderedLabel()
    {
      $elmId = $this->getId();
      $value = $this->getLabel();

      if(! $value)
      {
        return FALSE;
      }

      return '<div><label for="' . $elmId . '">' . $value . '</label></div>';
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getRenderedDescription()
    {
      $value = $this->getDescription();

      if(! $value)
      {
        return FALSE;
      }

      return '<div>' . $value . '</div>';
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _getElementTemplate()
    {
      return '<input :attributes>';
    }

    // ##########################################

    /**
     * @return array
     */
    protected function _getElementAttributes()
    {
      return array(
        'type'        => $this->getType(),
        'id'          => $this->getId(),
        'name'        => $this->getId(),
        'placeholder' => $this->getPlaceholder(),
        'value'       => $this->getValue(),
      );
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getRenderedElement()
    {
      $element = $this->_renderElement($this->_getElementTemplate(), $this->_getElementAttributes());

      return '<div>' . $element . '</div>';
    }

    // ##########################################

    /**
     * @return array
     */
    public function render()
    {
      return array(
        'label'       => $this->_getRenderedLabel(),
        'description' => $this->_getRenderedDescription(),
        'element'     => $this->_getRenderedElement(),
      );
    }
  }
