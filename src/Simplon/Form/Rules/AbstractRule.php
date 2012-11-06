<?php

  namespace Simplon\Form\Rules;

  abstract class AbstractRule
  {
    /** @var array */
    protected $_data = array();

    /** @var \Simplon\Form\Elements\AbstractElement */
    protected $_elementInstance;

    // ##########################################

    public function run()
    {
    }

    // ##########################################

    /**
     * @param $key
     * @param $val
     * @return AbstractRule
     */
    protected function _setByKey($key, $val)
    {
      $this->_data[$key] = $val;

      return $this;
    }

    // ##########################################

    /**
     * @param $key
     * @return bool
     */
    protected function _getByKey($key)
    {
      if(! isset($this->_data[$key]) || empty($this->_data[$key]))
      {
        return FALSE;
      }

      return $this->_data[$key];
    }

    // ##########################################

    /**
     * @param \Simplon\Form\Elements\AbstractElement $element
     * @return AbstractRule
     */
    public function setElement(\Simplon\Form\Elements\AbstractElement $element)
    {
      $this->_elementInstance = $element;

      return $this;
    }

    // ##########################################

    /**
     * @return \Simplon\Form\Elements\AbstractElement
     */
    public function getElement()
    {
      return $this->_elementInstance;
    }

    // ##########################################

    /**
     * @param $conditions
     * @return AbstractRule
     */
    public function setCondition($conditions)
    {
      $this->_setByKey('condition', $conditions);

      return $this;
    }

    // ##########################################

    /**
     * @return bool
     */
    public function getCondition()
    {
      return $this->_getByKey('condition');
    }

    // ##########################################

    /**
     * @param $errorMessage
     * @return AbstractRule
     */
    public function setErrorMessage($errorMessage)
    {
      $this->_setByKey('errorMessage', $errorMessage);

      return $this;
    }

    // ##########################################

    /**
     * @return bool|string
     */
    protected function _getErrorMessage()
    {
      $errorMessage = $this->_getByKey('errorMessage');

      return $errorMessage === FALSE ? $this->_defaultErrorMessage() : $errorMessage;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _defaultErrorMessage()
    {
      return '":label" didn\'t match the requirements.';
    }

    // ##########################################

    /**
     * @return array
     */
    protected function _getErrorMessagePlaceholders()
    {
      $id = $this
        ->getElement()
        ->getId();

      $label = $this
        ->getElement()
        ->getLabel();

      $value = $this
        ->getElement()
        ->getValue();

      $condition = $this->getCondition();

      return array(
        'id'        => $id,
        'label'     => $label,
        'value'     => $value,
        'condition' => $condition,
      );
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    protected function getFormattedErrorMessage()
    {
      $placeholders = $this->_getErrorMessagePlaceholders();
      $errorMessage = $this->_getErrorMessage();

      foreach($placeholders as $key => $value)
      {
        $errorMessage = str_replace(':' . $key, $value, $errorMessage);
      }

      return $errorMessage;
    }
  }