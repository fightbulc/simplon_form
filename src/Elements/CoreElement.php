<?php

namespace Simplon\Form\Elements;

use Simplon\Form\Rules\Core\CoreRule;
use Simplon\Form\Rules\Core\InterfaceRule;

class CoreElement implements InterfaceElement
{
    protected $elementHtml = '<input type="text" class=":class" id=":id" name=":id" value=":value">';

    protected $id;
    protected $label;
    protected $description;
    protected $value;
    protected $class = [];
    protected $js = [];

    /** @var InterfaceRule[] */
    protected $rules = [];
    protected $postValue = false;
    protected $isValid = true;
    protected $errorMessages = [];
    protected $errorContainerWrapper = 'ul';
    protected $errorItemWrapper = 'li';

    /**
     * @param $tag
     * @param $value
     * @param $string
     *
     * @return string
     */
    protected function replaceFieldPlaceholder($tag, $value, $string)
    {
        return (string)str_replace(":$tag", $value, $string);
    }

    /**
     * @param array $pairs
     * @param $string
     *
     * @return string
     */
    protected function replaceFieldPlaceholderMany(array $pairs, $string)
    {
        foreach ($pairs as $tag => $value)
        {
            $string = $this->replaceFieldPlaceholder($tag, $value, $string);
        }

        return $string;
    }

    /**
     * @return string
     */
    protected function getErrorContainerWrapper()
    {
        return $this->errorContainerWrapper;
    }

    /**
     * @return string
     */
    protected function getErrorItemWrapper()
    {
        return $this->errorItemWrapper;
    }

    /**
     * @param $elementHtml
     *
     * @return $this
     */
    public function setElementHtml($elementHtml)
    {
        $this->elementHtml = $elementHtml;

        return $this;
    }

    /**
     * @return string
     */
    public function getElementHtml()
    {
        return $this->elementHtml;
    }

    /**
     * @return string
     */
    protected function renderElementHtml()
    {
        return $this->parseFieldPlaceholders($this->getElementHtml());
    }

    /**
     * @param mixed $description
     *
     * @return static
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return (string)$this->description;
    }

    /**
     * @return null|string
     */
    protected function renderDescription()
    {
        $description = $this->getDescription();
        $template = '<p>:description</p>';

        if (empty($description))
        {
            return null;
        }

        return $this->parseFieldPlaceholders($template);
    }

    /**
     * @param string $id
     *
     * @return static
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return (string)$this->id;
    }

    /**
     * @param string $label
     *
     * @return static
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return (string)$this->label;
    }

    /**
     * @return string
     */
    protected function renderLabel()
    {
        $template = '<label for=":id">:label</label>';

        return $this->parseFieldPlaceholders($template);
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->hasPostValue() === true ? $this->getPostValue() : $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function addClass($value)
    {
        $this->class[] = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getClassString()
    {
        return join(' ', $this->class);
    }

    /**
     * @param array $rules
     *
     * @return static
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @param CoreRule $rule
     *
     * @return static
     */
    public function addRule(CoreRule $rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * @return array|InterfaceRule[]
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @return bool
     */
    public function hasPostValue()
    {
        return $this->getPostValue() !== false;
    }

    /**
     * @return bool|mixed
     */
    public function getPostValue()
    {
        return $this->postValue;
    }

    /**
     * @param $postValue
     *
     * @return CoreElement
     */
    public function setPostValue($postValue)
    {
        $this->postValue = $postValue;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function validateRules()
    {
        $rules = $this->getRules();

        if (empty($rules))
        {
            return null;
        }

        foreach ($rules as $ruleInstance)
        {
            $isValid = $ruleInstance->isValid($this);

            if ($isValid === false)
            {
                $this->addErrorMessage($ruleInstance->renderErrorMessage($this));
            }
        }

        return true;
    }

    /**
     * @param $message
     */
    protected function addErrorMessage($message)
    {
        $this->errorMessages[] = "<{$this->getErrorItemWrapper()}>{$message}</{$this->getErrorItemWrapper()}>";
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * @return string
     */
    public function renderErrorMessages()
    {
        $placeholders = [
            'containerWrapper'    => $this->getErrorContainerWrapper(),
            'errorMessagesString' => join('', $this->getErrorMessages()),
        ];

        $template = '<:containerWrapper class="rule-error-messages text-danger list-unstyled">:errorMessagesString</:containerWrapper>';

        return $this->replaceFieldPlaceholderMany($placeholders, $template);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $errorMessages = $this->getErrorMessages();

        return empty($errorMessages);
    }

    /**
     * @return array
     */
    protected function getFieldPlaceholders()
    {
        return [
            'id'          => $this->getId(),
            'label'       => $this->getLabel(),
            'value'       => $this->getValue(),
            'class'       => $this->getClassString(),
            'description' => $this->getDescription(),
        ];
    }

    /**
     * @param $stringWithPlaceholders
     *
     * @return string
     */
    public function parseFieldPlaceholders($stringWithPlaceholders)
    {
        return $this->replaceFieldPlaceholderMany($this->getFieldPlaceholders(), $stringWithPlaceholders);
    }

    /**
     * @param $js
     *
     * @return $this
     */
    public function addJs($js)
    {
        $this->js[] = $js;

        return $this;
    }

    /**
     * @return array
     */
    public function getJs()
    {
        return $this->js;
    }

    /**
     * @return array
     */
    public function render()
    {
        return [
            'label'       => $this->renderLabel(),
            'description' => $this->renderDescription(),
            'element'     => $this->renderElementHtml(),
        ];
    }
}