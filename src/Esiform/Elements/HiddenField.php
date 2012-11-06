<?php

  namespace Esiform\Elements;

  class HiddenField extends AbstractElement
  {
    /**
     * @return string
     */
    public function getType()
    {
      return 'hidden';
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
        'type'  => $this->getType(),
        'id'    => $this->getId(),
        'name'  => $this->getId(),
        'value' => $this->getValue(),
      );
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getRenderedElement()
    {
      return $this->_renderElement($this->_getElementTemplate(), $this->_getElementAttributes());
    }

    // ##########################################

    /**
     * @return array
     */
    public function render()
    {
      return array(
        'element' => $this->_getRenderedElement(),
      );
    }
  }
