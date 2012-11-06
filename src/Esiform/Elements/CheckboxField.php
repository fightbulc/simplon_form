<?php

  namespace Esiform\Elements;

  class CheckboxField extends AbstractInputField
  {
    /**
     * @return string
     */
    public function getType()
    {
      return 'checkbox';
    }
  }
