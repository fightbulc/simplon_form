<?php

  namespace Simplon\Form\Elements;

  class CheckboxField extends AbstractInputField
  {
    /**
     * @return string
     */
    public function getType()
    {
      return 'checkbox';
    }

    // ##########################################

    /**
     * @param $value
     * @return AbstractElement|CheckboxField
     */
    public function setValue($value)
    {
      if($value !== FALSE)
      {
        parent::setValue($value);
        $this->setChecked();
      }

      return $this;
    }

    // ##########################################

    /**
     * @return CheckboxField
     */
    public function setChecked()
    {
      $this->_setByKey('checked', 'checked');

      return $this;
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    public function getChecked()
    {
      return $this->_getByKey('checked');
    }

    // ##########################################

    /**
     * @return array
     */
    protected function _getElementAttributes()
    {
      return array(
        'type'    => $this->getType(),
        'id'      => $this->getId(),
        'name'    => $this->getId(),
        'class'   => $this->getClass(),
        'value'   => $this->getValue(),
        'checked' => $this->getChecked(),
      );
    }
  }
