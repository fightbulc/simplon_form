<?php

  namespace Simplon\Form\Elements;

  abstract class AbstractInputField extends AbstractElement
  {
    /**
     * @param $value
     * @return $this
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
     * @return $this
     */
    public function setClass($value)
    {
      $this->_setByKey('class', $value);

      return $this;
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    public function getClass()
    {
      return $this->_getByKey('class');
    }

    // ##########################################

    /**
     * @param $value
     * @return $this
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
     * @return $this
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
     * @return bool|string
     */
    protected function _getRenderedLabel()
    {
      $elmId = $this->getId();
      $value = $this->getLabel();

      if(! $value)
      {
        return FALSE;
      }

      return '<label for="' . $elmId . '">' . $value . '</label>';
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    protected function _getRenderedDescription()
    {
      $value = $this->getDescription();

      if(! $value)
      {
        return FALSE;
      }

      return $value;
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
        'class'       => $this->getClass(),
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

      return $element;
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
