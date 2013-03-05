<?php

  namespace Simplon\Form\Elements;

  class SelectField extends AbstractElement
  {
    /**
     * @return string
     */
    public function getType()
    {
      return 'radio';
    }

    // ##########################################

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
     * @param $key
     * @param $value
     * @return $this
     */
    public function setOption($key, $value)
    {
      $options = $this->getOptions();

      if($options === FALSE)
      {
        $options = array();
      }

      $options[$key] = $value;

      $this->_setByKey('options', $options);

      return $this;
    }

    // ##########################################

    /**
     * @param $options
     * @return $this
     */
    public function setOptions($options)
    {
      $this->_setByKey('options', $options);

      return $this;
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    public function getOptions()
    {
      return $this->_getByKey('options');
    }

    // ##########################################

    /**
     * @param $value
     * @return $this
     */
    public function setSelectOption($value)
    {
      $this->_setByKey('selectOption', $value);

      return $this;
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    public function getSelectOption()
    {
      return $this->_getByKey('selectOption');
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

      return '<label for="' . $elmId . '">' . $value . '</label>';
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

      return $value;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _getElementTemplate()
    {
      return '<select :attributes>:options</select>';
    }

    // ##########################################

    /**
     * @return array
     */
    protected function _getElementAttributes()
    {
      return array(
        'type'  => $this->getType(),
        'id'    => $this->getId(),
        'name'  => $this->getId(),
        'class' => $this->getClass(),
        'value' => $this->getValue(),
      );
    }

    // ##########################################

    /**
     * @param $fieldTemplate
     * @param $attributes
     * @return mixed
     */
    protected function _renderElement($fieldTemplate, $attributes)
    {
      $fieldTemplate = str_replace(':attributes', $this->_renderElementAttributes($attributes), $fieldTemplate);
      $fieldTemplate = str_replace(':options', $this->_renderOptions($this->getOptions()), $fieldTemplate);

      return $fieldTemplate;
    }

    // ##########################################

    /**
     * @param $options
     * @return string
     */
    protected function _renderOptions($options)
    {
      $rendered = [];

      $tmpl = '<option value="{{value}}"{{selected}}>{{label}}</options>';
      $selectedOption = $this->getSelectOption();

      foreach($options as $value => $label)
      {
        $option = str_replace('{{value}}', $value, str_replace('{{label}}', $label, $tmpl));

        if($selectedOption !== FALSE && $selectedOption == $value)
        {
          $option = str_replace('{{selected}}', ' selected', $option);
          $tmpl = str_replace('{{selected}}', '', $tmpl);
        }

        $rendered[] = $option;
      }

      return join("\n", $rendered);
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
