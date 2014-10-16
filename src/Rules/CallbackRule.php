<?php

namespace Simplon\Form\Rules;

use Simplon\Form\Elements\CoreElementInterface;
use Simplon\Form\Rules\Core\CoreRule;

/**
 * CallbackRule
 * @package Simplon\Form\Rules
 * @author Tino Ehrich (tino@bigpun.me)
 */
class CallbackRule extends CoreRule
{
    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @param callable $callback
     */
    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param CoreElementInterface $elementInstance
     *
     * @return bool
     */
    public function isValid(CoreElementInterface $elementInstance)
    {
        $response = $this->callback->__invoke($elementInstance->getValue());

        return (bool)$response;
    }
}