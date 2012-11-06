<?php

  namespace Simplon\Form\Rules;

  class Regexp extends AbstractRule
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

      if(! preg_match($condition, $elementValue))
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
      return '":label" doesn\'t match the requirements.';
    }
  }
