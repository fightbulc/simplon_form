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
  }
