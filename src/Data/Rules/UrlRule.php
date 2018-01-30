<?php

namespace Simplon\Form\Data\Rules;

use Simplon\Form\Data\FormField;
use Simplon\Form\Data\Rule;
use Simplon\Form\Data\RuleException;

class UrlRule extends Rule
{
    /**
     * @var string
     */
    protected $errorMessage = 'Invalid URL address';
    /**
     * @var string
     */
    private $autoProtocol = 'http';
    /**
     * @var string
     */
    private $additionalRegex;

    /**
     * @param string $autoProtocol
     */
    public function __construct(string $autoProtocol = 'http')
    {
        $this->autoProtocol = $autoProtocol;
    }

    /**
     * @param string $additionalRegex
     *
     * @return UrlRule
     */
    public function setAdditionalRegex(string $additionalRegex): UrlRule
    {
        $this->additionalRegex = $additionalRegex;

        return $this;
    }

    /**
     * @param FormField $field
     *
     * @throws RuleException
     */
    public function apply(FormField $field)
    {
        if (!preg_match('/^\w+:\/\//', $field->getValue()))
        {
            $field->setValue($this->autoProtocol . '://' . trim($field->getValue(), '/'));
        }

        $isValid = $this->runRegex($field, $this->getUrlRegEx());

        // if user defines additional validation

        if ($this->additionalRegex)
        {
            $isValid = $this->runRegex($field, $this->additionalRegex);
        }

        if ($isValid === false)
        {
            throw new RuleException(
                $this->getErrorMessage()
            );
        }
    }

    /**
     * @param FormField $field
     * @param string $regex
     *
     * @return bool
     */
    private function runRegex(FormField $field, string $regex): bool
    {
        return preg_match($regex, $field->getValue()) !== 0;
    }

    /**
     * @return string
     */
    private function getUrlRegEx(): string
    {
        return "#^" .

            // protocol identifier
            "(?:(?:https?|ftp):\\/\\/)?" .

            // user:pass authentication
            "(?:\\S+(?::\\S*)?@)?" .
            "(?:" .

            // IP address exclusion
            // private & local networks
            "(?!(?:10|127)(?:\\.\\d{1,3}){3})" .
            "(?!(?:169\\.254|192\\.168)(?:\\.\\d{1,3}){2})" .
            "(?!172\\.(?:1[6-9]|2\\d|3[0-1])(?:\\.\\d{1,3}){2})" .

            // IP address dotted notation octets
            // excludes loopback network 0.0.0.0
            // excludes reserved space >= 224.0.0.0
            // excludes network & broacast addresses
            // (first & last IP address of each class)
            "(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])" .
            "(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}" .
            "(?:\\.(?:[1-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))" .
            "|" .

            // host name
            "(?:(?:[a-z\\x{00a1}-\\x{ffff}0-9]-*)*[a-z\\x{00a1}-\\x{ffff}0-9]+)" .

            // domain name
            "(?:\\.(?:[a-z\\x{00a1}-\\x{ffff}0-9]-*)*[a-z\\x{00a1}-\\x{ffff}0-9]+)*" .

            // TLD identifier
            "(?:\\.(?:[a-z\\x{00a1}-\\x{ffff}]{2,}))" .
            ")" .

            // port number
            "(?::\\d{2,5})?" .

            // resource path
            "(?:\\/\\S*)?" .
            "$#ui";
    }
}