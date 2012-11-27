<?php

  namespace Simplon\Form\Rules;

  class Url extends AbstractRule
  {
    /**
     * @return bool|mixed|void
     */
    public function run()
    {
      $elementValue = $this
        ->getElement()
        ->getValue();

      if(! $this->validateUrl($elementValue))
      {
        return $this->getFormattedErrorMessage();
      }

      return TRUE;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _defaultErrorMessage()
    {
      return '":label" needs to be a valid URL address.';
    }

    // ##########################################

    /**
     * Verify the syntax of the given URL.
     *
     * @param $url
     * @return bool|int
     */
    public function validateUrl($url)
    {
      if($this->strStartsWith(strtolower($url), 'http://localhost'))
      {
        return TRUE;
      }

      return preg_match($this->_getUrlRegex(), $url) != 0;
    }

    // ##########################################

    /**
     * @return string
     */
    protected function _getUrlRegex()
    {
      return '/^(https?):\/\/' .                                  // protocol
        '(([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+' .         // username
        '(:([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+)?' .      // password
        '@)?(?#' .                                                  // auth requires @
        ')((([a-z0-9]\.|[a-z0-9][a-z0-9-]*[a-z0-9]\.)*' .           // domain segments AND
        '[a-z][a-z0-9-]*[a-z0-9]' .                                 // top level domain  OR
        '|((\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])\.){3}' .
        '(\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])' .                 // IP address
        ')(:\d+)?' .                                                // port
        ')(((\/+([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)*' . // path
        '(\?([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)' .      // query string
        '?)?)?' .                                                   // path and query string optional
        '(#([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)?' .      // fragment
        '$/i';
    }

    // ##########################################

    /**
     * String starts with something
     *
     * This function will return true only if input string starts with
     * niddle
     *
     * @param string $string Input string
     * @param string $niddle Needle string
     * @return boolean
     */
    function strStartsWith($string, $niddle)
    {
      return substr($string, 0, strlen($niddle)) == $niddle;
    }
  }
