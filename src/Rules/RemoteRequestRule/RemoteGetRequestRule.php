<?php

namespace Simplon\Form\Rules\RemoteRequestRule;

class RemoteGetRequestRule extends AbstractRemoteRequestRule
{
    /**
     * @return bool|string
     */
    protected function sendRequest()
    {
        return RequestHelper::get($this->getUrl(), $this->getParams());
    }
} 