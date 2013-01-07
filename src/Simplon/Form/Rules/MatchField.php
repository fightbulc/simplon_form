<?php

  namespace Simplon\Form\Rules;

  class MatchField extends AbstractRule
  {
    /**
     * @return bool|mixed|void
     */
    public function run()
    {
      $elementValue = $this
        ->getElement()
        ->getValue();

      $matchFieldId = $this->getCondition();

      if(! $this->_fieldValuesMatch($elementValue, $matchFieldId))
      {
        return $this->getFormattedErrorMessage();
      }

      return TRUE;
    }

    // ##########################################

    /**
     * @param $elementValue
     * @param $matchFieldId
     * @return bool
     */
    protected function _fieldValuesMatch($elementValue, $matchFieldId)
    {
      $matchFieldValue = $this->_getMatchFieldValue($matchFieldId);

      if($matchFieldValue === FALSE || $matchFieldValue != $elementValue)
      {
        return FALSE;
      }

      return TRUE;
    }

    // ##########################################

    /**
     * @param $fieldId
     * @return bool
     */
    protected function _getMatchFieldValue($fieldId)
    {
      if(! isset($_POST[$fieldId]))
      {
        return FALSE;
      }

      return $_POST[$fieldId];
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
