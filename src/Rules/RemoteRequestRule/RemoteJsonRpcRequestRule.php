<?php

namespace Simplon\Form\Rules\RemoteRequestRule;

class RemoteJsonRpcRequestRule extends AbstractRemoteRequestRule
{
    /**
     * @var
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
     * @param mixed $method
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