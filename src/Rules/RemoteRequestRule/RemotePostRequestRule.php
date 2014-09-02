<?php

namespace Simplon\Form\Rules\RemoteRequestRule;

class RemotePostRequestRule extends AbstractRemoteRequestRule
{
    /**
     * @return bool|string
     */
    protected function sendRequest()
    {
        return RequestHelper::post($this->getUrl(), $this->getParams());
    }
} 