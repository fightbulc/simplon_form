<?php

  namespace Esiform\Rules;

  class ExactLength extends AbstractRule
  {
    /**
     * @return bool|mixed|void
     */
    public function run()
    {
      $elementValue = $this
        ->getElement()
        ->getValue();

      $condition = $this->getCondition();

      if($elementValue === FALSE || strlen($elementValue) != $condition)
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
      return '":label" must have exactly ":condition" characters.';
    }
  }
