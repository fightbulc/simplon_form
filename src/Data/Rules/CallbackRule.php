<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\Field;

/**
 * Class CallbackRule
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
    public function __construct(\Closure $callback, $errorMessage)
    {
        $this->callback = $callback;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @param Field $field
     *
     * @throws RuleException
     */
    public function apply(Field $field)
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