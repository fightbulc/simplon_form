<?php

namespace Simplon\Form\Data\Rules;

/**
 * Class Rule
 * @package Simplon\Form\Data\Rules
 */
abstract class Rule implements RuleInterface
{
    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @var array
     */
    protected $errorParams;

    /**
     * @return string
     */
    protected function getErrorMessage()
    {
        $message = $this->errorMessage;

        if (empty($this->errorParams) === false)
        {
            foreach ($this->errorParams as $key => $value)
            {
                $message = preg_replace('/\{' . $key . '\}/iu', $value, $message);
            }
        }

        return $message;
    }

    /**
     * @param string $message
     * @param array $params
     *
     * @return static
     */
    protected function setErrorMessage($message, array $params = [])
    {
        $this->errorMessage = $message;
        $this->errorParams = $params;

        return $this;
    }
}