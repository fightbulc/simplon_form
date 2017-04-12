<?php

namespace Simplon\Form\Data;

/**
 * @package Simplon\Form\Data
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
    protected function getErrorMessage(): string
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
    protected function setErrorMessage(string $message, array $params = [])
    {
        $this->errorMessage = $message;
        $this->errorParams = $params;

        return $this;
    }
}