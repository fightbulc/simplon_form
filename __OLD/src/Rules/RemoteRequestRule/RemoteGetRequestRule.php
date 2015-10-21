<?php

namespace Simplon\Form\Rules\RemoteRequestRule;

/**
 * RemoteGetRequestRule
 * @package Simplon\Form\Rules\RemoteRequestRule
 * @author Tino Ehrich (tino@bigpun.me)
 */
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