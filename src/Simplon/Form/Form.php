<?php

  namespace Simplon\Form;

  class Form
  {
    protected $_elements = array();
    protected $_followUps = array();
    protected $_formId = 'form';
    protected $_formClass = '';
    protected $_formUrl = '';
    protected $_formMethod = 'GET';
    protected $_formAcceptCharset = 'utf-8';
    protected $_enabledCsrf = TRUE;
    protected $_csrfSalt = 'x45%da08*(';
    protected $_invalidElements = array();
    protected $_submitClass = 'btn';
    protected $_submitLabel = 'Submit Data';
    protected $_tmpl;

    // ##########################################

    public function __construct()
    {
      // start session to generate session_id
      session_start();

      // kick cookies
      $_REQUEST = array_merge($_GET, $_POST);
    }

    // ##########################################

    public static function init()
    {
      return new Form();
    }

    // ##########################################

    /**
     * @param $use
     * @return Form
     */
    public function enableCsrf($use)
    {
      $this->_enabledCsrf = ($use !== FALSE ? TRUE : FALSE);

      return $this;
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _hasEnabledCsrf()
    {
      return $this->_enabledCsrf;
    }

    // ##########################################

    /**
     * @param $id
     * @return Form
     */
    public function setFormId($id)
    {
      $this->_formId = $id;

      return $this;
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getFormId()
    {
      return $this->_formId;
    }

    // ##########################################

    /**
     * @param $class
     * @return Form
     */
    public function setFormClass($class)
    {
      $this->_formClass = $class;

      return $this;
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getFormClass()
    {
      return $this->_formClass;
    }

    // ##########################################

    /**
     * @param $class
     * @return Form
     */
    public function setSubmitClass($class)
    {
      $this->_submitClass = $class;

      return $this;
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getSubmitClass()
    {
      return $this->_submitClass;
    }

    // ##########################################

    /**
     * @param $label
     * @return Form
     */
    public function setSubmitLabel($label)
    {
      $this->_submitLabel = $label;

      return $this;
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getSubmitLabel()
    {
      return $this->_submitLabel;
    }

    // ##########################################

    /**
     * @param $url
     * @return Form
     */
    public function setUrl($url)
    {
      $this->_formUrl = $url;

      return $this;
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getUrl()
    {
      return $this->_formUrl;
    }

    // ##########################################

    /**
     * @param $charset
     * @return Form
     */
    public function setCharset($charset)
    {
      $this->_formAcceptCharset = $charset;

      return $this;
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getCharset()
    {
      return $this->_formAcceptCharset;
    }

    // ##########################################

    /**
     * @param $method
     * @return Form
     */
    public function setMethod($method)
    {
      $this->_formMethod = $method;

      return $this;
    }

    // ##########################################

    /**
     * @return mixed
     */
    protected function _getMethod()
    {
      return $this->_formMethod;
    }

    // ##########################################

    /**
     * @param $elements
     * @return Form
     */
    public function setElements($elements)
    {
      $this->_elements = $elements;

      return $this;
    }

    // ##########################################

    /**
     * @param Elements\AbstractElement $element
     * @return Form
     */
    public function addElement(\Simplon\Form\Elements\AbstractElement $element)
    {
      $this->_elements[] = $element;

      return $this;
    }

    // ##########################################

    /**
     * @return array
     */
    protected function _getElements()
    {
      return $this->_elements;
    }

    // ##########################################

    /**
     * @param $followUps
     * @return Form
     */
    public function setFollowUps($followUps)
    {
      $this->_followUps = $followUps;

      return $this;
    }

    // ##########################################

    /**
     * @param $followUp
     * @return Form
     */
    public function addFollowUp($followUp)
    {
      $this->_followUps[] = $followUp;

      return $this;
    }

    // ##########################################

    /**
     * @return array
     */
    protected function _getFollowUps()
    {
      return $this->_followUps;
    }

    // ##########################################

    public function setTemplate($templatePath)
    {
      $this->_tmpl = $this->_fetchTemplate($templatePath);

      return $this;
    }

    // ##########################################

    protected function _fetchTemplate($templatePath)
    {
      return join('', file($templatePath));
    }

    // ##########################################

    protected function _getTemplate()
    {
      return $this->_tmpl;
    }

    // ##########################################

    protected function _getRequestValue($key)
    {
      if(! isset($_REQUEST[$key]))
      {
        return FALSE;
      }

      return $_REQUEST[$key];
    }

    // ##########################################

    /**
     * @param $salt
     * @return Form
     */
    public function setCsrfSalt($salt)
    {
      $this->_csrfSalt = $salt;

      return $this;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _getCsrfSalt()
    {
      return $this->_csrfSalt;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _getCsrfValue()
    {
      return md5(session_id() . $this->_getCsrfSalt());
    }

    // ##########################################

    /**
     * @return array
     */
    protected function _getInvalidElements()
    {
      return $this->_invalidElements;
    }

    // ##########################################

    /**
     * @return bool
     */
    protected function _isSubmitted()
    {
      return $this->_getRequestValue('csrf') === $this->_getCsrfValue();
    }

    // ##########################################

    /**
     * @param $elementId
     * @param array $placeholderValues
     */
    protected function _replaceTemplatePlaceholder($elementId, array $placeholderValues)
    {
      foreach($placeholderValues as $placeholder => $value)
      {
        $key = $elementId . ':' . $placeholder;

        if($value !== FALSE)
        {
          preg_match('#(<' . $key . '>.*?</' . $key . '>)\s*#smi', $this->_tmpl, $matched);

          if($matched)
          {
            $container = str_replace('<value>', $value, $matched[1]);
            $container = $this->_cleanPlaceholders($container);
            $this->_tmpl = preg_replace('#' . $matched[1] . '\s*#', $container, $this->_tmpl);
          }
        }
      }
    }

    // ##########################################

    /**
     * @return bool
     */
    protected function _hasUpload()
    {
      $elements = $this->_getElements();

      foreach($elements as $elm)
      {
        if($elm->getType() == 'upload')
        {
          return TRUE;
        }
      }

      return FALSE;
    }

    // ##########################################

    /**
     * @return bool
     */
    protected function _setCsrfElement()
    {
      if($this->_hasEnabledCsrf())
      {
        $csrfValue = $this->_getCsrfValue();

        // create element
        $element = \Simplon\Form\Elements\HiddenField::init()
          ->setId('csrf')
          ->setValue($csrfValue);

        // set element
        $this->addElement($element);

        // set in template
        $this->_tmpl = str_replace('<form:open>', '<form:open><csrf:element><value></csrf:element>', $this->_tmpl);

        return TRUE;
      }

      return FALSE;
    }

    // ##########################################

    /**
     * Set Form open/close tags
     */
    protected function _setFormTags()
    {
      $formOpen = '<form :attributes>';
      $formClose = '</form>';

      $class = $this->_getFormClass();

      // add warning class
      if(!empty($this->_invalidElements))
      {
        $class = $class . ' control-group error';
      }

      // default values
      $formAttributes = array(
        'id'             => $this->_getFormId(),
        'class'          => $class,
        'action'         => $this->_getUrl(),
        'method'         => $this->_getMethod(),
        'accept-charset' => $this->_getCharset(),
      );

      // enable upload
      if($this->_hasUpload())
      {
        $formAttributes['enctype'] = 'multipart/form-data';
      }

      // set values
      $_renderedAttributes = array();

      foreach($formAttributes as $key => $value)
      {
        $_renderedAttributes[] = $key . '="' . $value . '"';
      }

      $formOpen = str_replace(':attributes', join(' ', $_renderedAttributes), $formOpen);

      // set form open
      $this->_tmpl = str_replace('<form:open>', $formOpen, $this->_tmpl);

      // set form close
      $this->_tmpl = str_replace('<form:close>', $formClose, $this->_tmpl);
    }

    // ##########################################

    /**
     * Set form submit tag
     */
    protected function _setFormSubmitTag()
    {
      $formSubmit = '<button :attributes>:label</button>';

      // default values
      $submitAttributes = array(
        'id'    => $this->_getFormId(),
        'name'  => $this->_getFormId(),
        'class' => $this->_getSubmitClass(),
      );

      if($this->_getSubmitClass() == 'image')
      {
        $submitAttributes['src'] = $this->_getSubmitSource();
      }

      $_renderedAttributes = array();

      foreach($submitAttributes as $key => $value)
      {
        $_renderedAttributes[] = $key . '="' . $value . '"';
      }

      $formSubmit = str_replace(':attributes', join(' ', $_renderedAttributes), $formSubmit);
      $formSubmit = str_replace(':label', $this->_getSubmitLabel(), $formSubmit);

      // set form submit
      $this->_tmpl = str_replace('<form:submit>', $formSubmit, $this->_tmpl);
    }

    // ##########################################

    /**
     * Remove placeholders from given template
     */
    protected function _cleanPlaceholders($tmpl)
    {
      return preg_replace('#</*[\w\d]+:[\w\d]+>\s*#sm', '', $tmpl);
    }

    // ##########################################

    /**
     * Remove all left placeholders from template
     */
    protected function _cleanTemplate()
    {
      $this->_tmpl = preg_replace('#(<([\w\d]+:[\w\d]+)>.*?</\\2>|<[\w\d]+:[\w\d]+>)\s*#smi', '', $this->_tmpl);
    }

    // ##########################################

    /**
     * @return array
     */
    protected function _getAllElementsValues()
    {
      $keyValuePairs = array();

      foreach($this->_getElements() as $elementClass)
      {
        $keyValuePairs[$elementClass->getId()] = $elementClass->getValue();
      }

      return $keyValuePairs;
    }

    // ##########################################

    /**
     * @return bool
     */
    public function validate()
    {
      if($this->_isSubmitted())
      {
        // iterate through all elements
        foreach($this->_getElements() as $elementClass)
        {
          // fill element with submitted value
          $_requestValue = $this->_getRequestValue($elementClass->getId());
          $elementClass->setValue($_requestValue);

          // lets run through the validation
          if($elementClass->hasRules())
          {
            $elementRules = $elementClass->getRules();

            foreach($elementRules as $rule)
            {
              $ruleType = $rule['type'];
              $ruleCondition = $rule['condition'];
              $ruleErrorMessage = $rule['errorMessage'];
              $isValid = FALSE;

              // closure
              if(is_callable($ruleType))
              {
                $isValid = $ruleType($elementClass, $ruleErrorMessage);

                if($isValid !== TRUE)
                {
                  return $isValid;
                }
              }

              // rule class
              else
              {
                $classFilePath = __DIR__ . '/Rules/' . $ruleType . '.php';

                if(file_exists($classFilePath))
                {
                  $namespace = "Simplon\Form\\Rules\\$ruleType";
                  $ruleClass = new $namespace();

                  $ruleClass->setElement($elementClass);
                  $ruleClass->setCondition($ruleCondition);
                  $ruleClass->setErrorMessage($ruleErrorMessage);

                  $isValid = $ruleClass->run();
                }
              }

              // collect failed elements
              if($isValid !== TRUE)
              {
                $this->_invalidElements[$elementClass->getId()] = $isValid;
              }
            }
          }
        }

        // if we got failed elements
        if(! empty($this->_invalidElements))
        {
          return FALSE;
        }

        // else all is cool
        return TRUE;
      }

      return FALSE;
    }

    // ##########################################

    /**
     * @return mixed
     */
    public function render()
    {
      $isSubmitted = $this->_isSubmitted();

      // include CSRF field if enabled
      $this->_setCsrfElement();

      $invalidElements = $this->_getInvalidElements();

      // set elements
      foreach($this->_getElements() as $element)
      {
        if(method_exists($element, 'render'))
        {
          // set error
          if(array_key_exists($element->getId(), $invalidElements))
          {
            $placeholder = array(
              'error' => $invalidElements[$element->getId()]
            );

            $this->_replaceTemplatePlaceholder($element->getId(), $placeholder);
          }

          $this->_replaceTemplatePlaceholder($element->getId(), $element->render());
        }
      }

      // set form open/close tag
      $this->_setFormTags();

      // set form submit tag
      $this->_setFormSubmitTag();

      // clean left overs
      $this->_cleanTemplate();

      // return finished template
      return $this->_getTemplate();
    }

    // ##########################################

    /**
     * Run defined FollowUps
     * @return bool
     */
    public function runFollowUps()
    {
      $allElementsValues = $this->_getAllElementsValues();
      $followUps = $this->_getFollowUps();

      foreach($followUps as $followupClosure)
      {
        $followupClosure($allElementsValues);
      }

      return TRUE;
    }
  }
