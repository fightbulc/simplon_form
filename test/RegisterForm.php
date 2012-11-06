<?php

  class RegisterForm
  {
    /** @var \Simplon\Form\Form */
    protected $_formInstance;

    // ##########################################

    public function __construct()
    {
      $this->_formInstance = \Simplon\Form\Form::init()
        ->setId('myForm')
        ->setUrl('page.php')
        ->setMethod('POST')
        ->setTemplate($this->_getTemplatePath())
        ->setElements($this->_getElements())
        ->setFollowUps($this->_getFollowUps());
    }

    // ##########################################

    /**
     * @return Simplon\Form\Form
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
      $elements[] = \Simplon\Form\Elements\TextField::init()
        ->setId('username')
        ->setLabel('Username')
        ->addRule('Required');

      // password field
      $elements[] = \Simplon\Form\Elements\PasswordField::init()
        ->setId('password')
        ->setLabel('Password')
        ->addRule('Required')
        ->addRule('MinLength', 4);

      // password field
      $elements[] = \Simplon\Form\Elements\EmailField::init()
        ->setId('email')
        ->setLabel('Email')
        ->addRule('required')
        ->addRule('Email');

      // tos field
      $elements[] = \Simplon\Form\Elements\CheckboxField::init()
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