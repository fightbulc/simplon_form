<?php

  class RegisterForm
  {
    /** @var \Esiform\Esiform */
    protected $_formInstance;

    // ##########################################

    public function __construct()
    {
      $this->_formInstance = \Esiform\Esiform::init()
        ->setId('myForm')
        ->setUrl('page.php')
        ->setMethod('POST')
        ->setTemplate($this->_getTemplatePath())
        ->setElements($this->_getElements())
        ->setFollowUps($this->_getFollowUps());
    }

    // ##########################################

    /**
     * @return Esiform\Esiform
     */
    protected function _getFormInstance()
    {
      return $this->_formInstance;
    }

    // ##########################################

    /**
     * @return bool
     */
    public function validate()
    {
      return $this
        ->_getFormInstance()
        ->validate();
    }

    // ##########################################

    /**
     * @return string
     */
    public function render()
    {
      return $this
        ->_getFormInstance()
        ->render();
    }

    // ##########################################

    /**
     * @return bool
     */
    public function runFollowUps()
    {
      return $this
        ->_getFormInstance()
        ->runFollowUps();
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _getTemplatePath()
    {
      return './RegisterFormTemplate.html';
    }

    // ##########################################

    /**
     * @return array
     */
    protected function _getElements()
    {
      $elements = array();

      // username field
      $elements[] = \Esiform\Elements\TextField::init()
        ->setId('username')
        ->setLabel('Username')
        ->addRule('Required');

      // password field
      $elements[] = \Esiform\Elements\PasswordField::init()
        ->setId('password')
        ->setLabel('Password')
        ->addRule('Required')
        ->addRule('MinLength', 4);

      // password field
      $elements[] = \Esiform\Elements\EmailField::init()
        ->setId('email')
        ->setLabel('Email')
        ->addRule('required')
        ->addRule('Email');

      // tos field
      $elements[] = \Esiform\Elements\CheckboxField::init()
        ->setId('tos')
        ->setLabel('<span id="termstext" class="field legalline  formerror">I agree to the <a href="http://war2glory.com/enterms/" target="_blank">General Terms and Conditions</a>, the <a href="http://war2glory.com/enpp/" target="_blank">Privacy Policy</a> and the <a href="http://war2glory.com/enrules/" target="_blank">Game Rules</a>.</span>')
        ->addRule('Required', NULL, 'This field needs to be checked.');

      return $elements;
    }

    // ##########################################

    /**
     * @return array
     */
    protected function _getFollowUps()
    {
      $followUps = array();

      $followUps[] = function ($data)
      {
        echo "FOLLOWUP<br>";
        var_dump($data);
        echo '<hr>';
      };

      return $followUps;
    }
  }