<?php

namespace Simplon\Form\Rules\RemoteRequestRule;

/**
 * RemotePostRequestRule
 * @package Simplon\Form\Rules\RemoteRequestRule
 * @author Tino Ehrich (tino@bigpun.me)
 */
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