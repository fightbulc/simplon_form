<?php

  namespace Simplon\Form\Rules;

  use CURL;

  class Remote extends AbstractRule
  {
    /** @var string */
    protected $_requestType;

    /** @var string */
    protected $_requestUrl;

    /** @var array */
    protected $_requestData = array();

    // ##########################################

    /**
     * @return bool|mixed|void
     */
    public function run()
    {
      // get field value
      $elementValue = $this
        ->getElement()
        ->getValue();

      // read data
      $this->_readData();

      // validate
      if($this->_hasValidConditions())
      {
        $requestResponse = 0;
        $requestType = 'POST';
        $requestUrl = $this->_getRequestUrl();
        $requestData = $this->_getRequestData();
        $requestData['elementValue'] = $elementValue;

        // get requests
        if($this->_getRequestType() == 'GET')
        {
          $requestType = 'GET';
          $requestUrl = $requestUrl . '?' . http_build_query($requestData);

          // call remote
          $requestResponse = CURL::init($requestUrl)
            ->setReturnTransfer(TRUE)
            ->execute();
        }

        // jsonrpc preparation
        if($this->_getRequestType() == 'JSONRPC')
        {
          if(! isset($requestData['id']) || ! isset($requestData['method']))
          {
            return FALSE;
          }

          if(! isset($requestData['params']))
          {
            $requestData['params']['elementValue'] = $requestData['elementValue'];
            unset($requestData['elementValue']);
          }

          $requestData = json_encode($requestData);
        }

        // post / jsonrpc request
        if($requestType == 'POST')
        {
          // call remote
          $requestResponse = CURL::init($requestUrl)
            ->setPost(TRUE)
            ->setPostFields($requestData)
            ->setReturnTransfer(TRUE)
            ->execute();

          if($this->_getRequestType() == 'JSONRPC')
          {
            $requestResponse = json_decode($requestResponse, TRUE);

            if(isset($requestResponse['result']))
            {
              $requestResponse = $requestResponse['result'];
            }
          }
        }

        // we're cool if we receive "1"
        if((int)$requestResponse != 1)
        {
          return $this->getFormattedErrorMessage();
        }
      }

      return TRUE;
    }

    // ##########################################

    /**
     * @return bool
     */
    protected function _readData()
    {
      $conditions = $this->_getByKey('condition');

      foreach(['type', 'url', 'data'] as $field)
      {
        if(! isset($conditions[$field]))
        {
          $conditions[$field] = NULL;
        }
      }

      $this->_setRequestType($conditions['type']);
      $this->_setRequestUrl($conditions['url']);
      $this->_setRequestData($conditions['data']);

      return TRUE;
    }

    // ##########################################

    /**
     * @param $type
     * @return Remote
     */
    protected function _setRequestType($type)
    {
      $this->_requestType = $type;

      return $this;
    }

    // ##########################################

    /**
     * @return bool|string
     */
    protected function _getRequestType()
    {
      if(! isset($this->_requestType) || ! $this->_isValidType($this->_requestType))
      {
        return FALSE;
      }

      return strtoupper($this->_requestType);
    }

    // ##########################################

    /**
     * @param $url
     * @return Remote
     */
    protected function _setRequestUrl($url)
    {
      if((new Url())->validateUrl($url) !== FALSE)
      {
        $this->_requestUrl = $url;
      }

      return $this;
    }

    // ##########################################

    /**
     * @return bool|string
     */
    protected function _getRequestUrl()
    {
      if(! isset($this->_requestUrl))
      {
        return FALSE;
      }

      return $this->_requestUrl;
    }

    // ##########################################

    /**
     * @param $data
     * @return Remote
     */
    protected function _setRequestData($data)
    {
      if(! empty($data))
      {
        $this->_requestData = $data;
      }

      return $this;
    }

    // ##########################################

    /**
     * @return array
     */
    protected function _getRequestData()
    {
      return $this->_requestData;
    }

    // ##########################################

    /**
     * @param $type
     * @return bool
     */
    protected function _isValidType($type)
    {
      $validTypes = array(
        'GET',
        'POST',
        'JSONRPC',
      );

      if(in_array(strtoupper($type), $validTypes))
      {
        return TRUE;
      }

      return FALSE;
    }

    // ##########################################

    /**
     * @return bool
     */
    protected function _hasValidConditions()
    {
      if($this->_getRequestType() && $this->_getRequestUrl())
      {
        return TRUE;
      }

      return FALSE;
    }

    // ##########################################

    /**
     * @return array
     */
    protected function _getErrorMessagePlaceholders()
    {
      $id = $this
        ->getElement()
        ->getId();

      $label = $this
        ->getElement()
        ->getLabel();

      $value = $this
        ->getElement()
        ->getValue();

      return array(
        'id'    => $id,
        'label' => $label,
        'value' => $value,
      );
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _defaultErrorMessage()
    {
      return '":label" with the ":value" is not allowed/invalid.';
    }
  }
