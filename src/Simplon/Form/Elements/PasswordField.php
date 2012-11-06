<?php

  namespace Simplon\Form\Elements;

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
