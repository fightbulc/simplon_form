<?php

  namespace Esiform\Elements;

  class PasswordField extends AbstractInputField
  {
    /**
     * @return string
     */
    public function getType()
    {
      return 'password';
    }
  }
