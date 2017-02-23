<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\FormField;

/**
 * @package Simplon\Form\Data\Rules
 */
class CallbackRule extends Rule
{
    /**
     * @var \Closure
     */
    private $callback;
    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @param \Closure $callback
     * @param string $errorMessage
     */
    public function __construct(\Closure $callback, string $errorMessage)
    {
        $this->callback = $callback;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @param FormField $field
     *
     * @throws RuleException
     */
    public function apply(FormField $field)
    {
        $response = call_user_func_array($this->callback, [$field]);

        if ($response === false)
        {
            throw new RuleException(
                $this->getErrorMessage()
            );
        }
    }
}