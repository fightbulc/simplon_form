<?php

  namespace Simplon\Form\Rules;

  class Required extends AbstractRule
  {
    /**
     * @return bool|mixed|void
     */
    public function run()
    {
      $elementValue = $this
        ->getElement()
        ->getValue();

      if($elementValue === FALSE)
      {
        return $this->getFormattedErrorMessage();
      }

      return TRUE;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _defaultErrorMessage()
    {
      return '":label" is required.';
    }
  }
