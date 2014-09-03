<?php

namespace Simplon\Form\Rules\RemoteRequestRule;

use Simplon\Form\Elements\InterfaceElement;
use Simplon\Form\Rules\Core\CoreRule;

abstract class AbstractRemoteRequestRule extends CoreRule
{
    /** @var string */
    protected $errorMessage = '":label" remote post failed';

    /** @var  \Closure */
    protected $paramsClosure;

    /** @var  \Closure */
    protected $responseCallback;

    /** @var  string */
    protected $url;

    /** @var  array */
    protected $params;

    /**
     * @param \Simplon\Form\Elements\InterfaceElement $elementInstance
     *
     * @return bool
     */
    public function isValid(InterfaceElement $elementInstance)
    {
        // prepare params
        $this->params = $this->getParamsClosure($elementInstance->getValue());

        // send request
        $response = $this->sendRequest();

        // run callback
        $isValid = $this->getResponseCallback($response);

        return $isValid;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function getResponseCallback($data)
    {
        $closure = $this->responseCallback;

        return (bool)$closure($data);
    }

    /**
     * @param callable $responseCallback
     *
     * @return static
     */
    public function setResponseCallback(\Closure $responseCallback)
    {
        $this->responseCallback = $responseCallback;

        return $this;
    }

    /**
     * @return array
     */
    protected function getParams()
    {
        return $this->params;
    }

    /**
     * @param $data
     *
     * @return array
     */
    protected function getParamsClosure($data)
    {
        $closure = $this->paramsClosure;

        return (array)$closure($data);
    }

    /**
     * @param callable $closure
     *
     * @return static
     */
    public function setParams(\Closure $closure)
    {
        $this->paramsClosure = $closure;

        return $this;
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return (string)$this->url;
    }

    /**
     * @param string $url
     *
     * @return static
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return bool
     */
    protected function sendRequest()
    {
        return true;
    }
}