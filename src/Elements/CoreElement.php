<?php

namespace Simplon\Form\Elements;

use Simplon\Form\Filters\Core\CoreFilterInterface;
use Simplon\Form\Rules\Core\CoreRuleInterface;

/**
 * CoreElement
 *
 * @package Simplon\Form\Elements
 * @author  Tino Ehrich (tino@bigpun.me)
 */
abstract class CoreElement implements CoreElementInterface
{
    /**
     * @var string
     */
    protected $elementHtml = '<input type="text" class=":class" id=":id" name=":name" value=":value">';

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var array
     */
    protected $class = [];

    /**
     * @var array
     */
    protected $assetFiles = [];

    /**
     * @var array
     */
    protected $assetInlines = [];

    /**
     * @var CoreRuleInterface[]
     */
    protected $rules = [];

    /**
     * @var CoreFilterInterface[]
     */
    protected $filters = [];

    /**
     * @var bool
     */
    protected $postValue = false;

    /**
     * @var bool
     */
    protected $isValid = true;

    /**
     * @var array
     */
    protected $errorMessages = [];

    /**
     * @var string
     */
    protected $errorContainerWrapper = 'ul';

    /**
     * @var string
     */
    protected $errorItemWrapper = 'li';

    /**
     * @var mixed
     */
    protected $arrayKey;

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
     * @return bool
     */
    public function hasElement()
    {
        return $this->getElementHtml() !== '';
    }

    /**
     * @return string
     */
    public function getElementHtml()
    {
        return (string)$this->elementHtml;
    }

    /**
     * @return string
     */
    public function renderElementHtml()
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
     * @return bool
     */
    public function hasDescription()
    {
        return $this->getDescription() !== '';
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
    public function renderDescription()
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
    public function getRawId()
    {
        return (string)$this->id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getArrayKey() !== null ? $this->getRawId() . '_' . $this->getArrayKey() : $this->getRawId();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getArrayKey() !== null ? $this->getRawId() . '[' . $this->getArrayKey() . ']' : $this->getRawId();
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
     * @return bool
     */
    public function hasLabel()
    {
        return $this->getLabel() !== '';
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
    public function renderLabel()
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
     * @param CoreRuleInterface[] $rules
     *
     * @return static
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @param CoreRuleInterface $rule
     *
     * @return static
     */
    public function addRule(CoreRuleInterface $rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * @return CoreRuleInterface[]
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param CoreFilterInterface[] $filters
     *
     * @return static
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @param CoreFilterInterface $filter
     *
     * @return static
     */
    public function addFilter(CoreFilterInterface $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @return CoreFilterInterface[]
     */
    public function getFilters()
    {
        return $this->filters;
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
     * @return CoreElementInterface
     */
    public function setPostValue($postValue)
    {
        $this->postValue = $postValue;

        return $this;
    }

    /**
     * @return static
     */
    public function processFilters()
    {
        $filters = $this->getFilters();

        if (empty($filters) === false)
        {
            foreach ($filters as $filterInstance)
            {
                $filterInstance->processFilter($this);
            }
        }

        return $this;
    }

    /**
     * @return bool|null
     */
    public function processRules()
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
     * @return bool
     */
    public function hasError()
    {
        $errorMessages = $this->getErrorMessages();

        return empty($errorMessages) === false;
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
     * @return void
     */
    public function setup()
    {
    }

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
     * @param       $string
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
     * @param $message
     *
     * @return static
     */
    protected function addErrorMessage($message)
    {
        $this->errorMessages[] = "<{$this->getErrorItemWrapper()}>{$message}</{$this->getErrorItemWrapper()}>";

        return $this;
    }

    /**
     * @return array
     */
    protected function getFieldPlaceholders()
    {
        return [
            'id'          => $this->getId(),
            'name'        => $this->getName(),
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
     * @return array
     */
    public function getAssetFiles()
    {
        return $this->assetFiles;
    }

    /**
     * @return array
     */
    public function getAssetInlines()
    {
        return $this->assetInlines;
    }

    /**
     * @return mixed
     */
    public function getArrayKey()
    {
        return $this->arrayKey;
    }

    /**
     * @param mixed $arrayKey
     *
     * @return CoreElement
     */
    public function setArrayKey($arrayKey)
    {
        $this->arrayKey = $arrayKey;

        return $this;
    }

    /**
     * @param array $requestData
     *
     * @return CoreElement
     */
    public function setPostValueByRequestData(array $requestData)
    {
        if (isset($requestData[$this->id]))
        {
            $value = $requestData[$this->id];

            if ($this->getArrayKey() !== null && isset($requestData[$this->id][$this->getArrayKey()]))
            {
                $value = $requestData[$this->id][$this->getArrayKey()];
            }

            $this->setPostValue($value);
        }

        return $this;
    }

    /**
     * @param $fileAsset
     *
     * @return $this
     */
    protected function addAssetFile($fileAsset)
    {
        $this->assetFiles[] = $fileAsset;

        return $this;
    }

    /**
     * @param $inline
     *
     * @return $this
     */
    protected function addAssetInline($inline)
    {
        $this->assetInlines[] = trim($inline);

        return $this;
    }
}