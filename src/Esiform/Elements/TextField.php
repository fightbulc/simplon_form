<?php

  namespace Esiform\Elements;

  class TextField extends AbstractInputField
  {
    /**
     * @return string
     */
    public function getType()
    {
      return 'text';
    }
  }
