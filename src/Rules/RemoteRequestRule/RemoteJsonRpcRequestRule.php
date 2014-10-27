<?php

namespace Simplon\Form\Rules\RemoteRequestRule;

/**
 * RemoteJsonRpcRequestRule
 * @package Simplon\Form\Rules\RemoteRequestRule
 * @author Tino Ehrich (tino@bigpun.me)
 */
class RemoteJsonRpcRequestRule extends AbstractRemoteRequestRule
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @return string
     */
    public function getMethod()
    {
        return (string)$this->method;
    }

    /**
     * @param string $method
     *
     * @return RemoteJsonRpcRequestRule
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return array|bool
     * @throws \Exception
     */
    protected function sendRequest()
    {
        return RequestHelper::jsonRpc(
            $this->getUrl(),
            $this->getMethod(),
            $this->getParams()
        );
    }
} 