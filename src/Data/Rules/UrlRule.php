<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\FormField;
use Simplon\Form\Data\Rule;
use Simplon\Form\Data\RuleException;

/**
 * @package Simplon\Form\Data\Rules
 */
class UrlRule extends Rule
{
    /**
     * @var string
     */
    protected $errorMessage = 'Invalid URL address';
    /**
     * @var bool
     */
    private $autoFillProtocol;
    /**
     * @var string
     */
    private $autoProtocol = 'http';

    /**
     * @param bool $autoFillProtocol
     */
    public function __construct(bool $autoFillProtocol = true)
    {
        $this->autoFillProtocol = $autoFillProtocol;
    }

    /**
     * @param string $autoProtocol
     *
     * @return UrlRule
     */
    public function setAutoProtocol(string $autoProtocol): UrlRule
    {
        $this->autoProtocol = $autoProtocol;

        return $this;
    }

    /**
     * @param FormField $field
     *
     * @throws RuleException
     */
    public function apply(FormField $field)
    {
        if ($this->autoFillProtocol && !preg_match('/^\w+:\/\//', $field->getValue()))
        {
            $field->setValue($this->autoProtocol . '://' . trim($field->getValue(), '/'));
        }

        if (filter_var($field->getValue(), FILTER_VALIDATE_URL) === false)
        {
            throw new RuleException(
                $this->getErrorMessage()
            );
        }
    }
}