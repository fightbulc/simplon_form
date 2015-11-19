<?php

namespace Simplon\Form\Data;

use Simplon\Form\Data\Filters\FilterInterface;
use Simplon\Form\Data\Rules\FieldDependencyRule;
use Simplon\Form\Data\Rules\IfFilledRule;
use Simplon\Form\Data\Rules\RuleInterface;
use Simplon\Form\FormException;
use Simplon\Form\View\Elements\Support\Meta\MetaInterface;

/**
 * Class Field
 * @package Simplon\Form\Data
 */
class Field
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var mixed
     */
    private $initialValue;

    /**
     * @var array
     */
    private $meta = [];

    /**
     * @var FilterInterface[]
     */
    private $filters = [];

    /**
     * @var RuleInterface[]
     */
    private $rules = [];

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var bool
     */
    private $arrangedRules = false;

    /**
     * @param string $id
     *
     * @throws FormException
     */
    public function __construct($id)
    {
        if (preg_match('/^[a-zA-Z_-]+$/u', $id) === 0)
        {
            throw new FormException('ID "' . $id . '" has invalid characters. Please use only [a-zA-Z_-]');
        }

        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getInitialValue()
    {
        return $this->initialValue;
    }

    /**
     * @param mixed $initialValue
     *
     * @return Field
     */
    public function setInitialValue($initialValue)
    {
        $this->initialValue = $initialValue;

        $this->setValue($initialValue);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return Field
     */
    public function setValue($value)
    {
        $this->value = $this->applyFilters($value);

        return $this;
    }

    /**
     * @param string $key
     *
     * @return null|mixed
     */
    public function getMeta($key)
    {
        if (isset($this->meta[$key]))
        {
            return $this->meta[$key];
        }

        return null;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasMeta($key)
    {
        return empty($this->meta[$key]) === false;
    }

    /**
     * @param MetaInterface $meta
     *
     * @return Field
     */
    public function addMeta(MetaInterface $meta)
    {
        $this->meta[$meta->getKey()] = $meta->getData();

        return $this;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return bool
     */
    public function hasFilters()
    {
        return empty($this->filters) === false;
    }

    /**
     * @param FilterInterface $filter
     *
     * @return Field
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @param FilterInterface[] $filters
     *
     * @return Field
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return RuleInterface[]
     */
    public function getRules()
    {
        if ($this->arrangedRules === false)
        {
            $this->rules = $this->arrangeRules($this->rules);
            $this->arrangedRules = true;
        }

        return $this->rules;
    }

    /**
     * @return bool
     */
    public function hasRules()
    {
        return empty($this->rules) === false;
    }

    /**
     * @param RuleInterface $rule
     *
     * @return Field
     */
    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;
        $this->arrangedRules = false;

        return $this;
    }

    /**
     * @param RuleInterface[] $rules
     *
     * @return Field
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
        $this->arrangedRules = false;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return empty($this->errors) === false;
    }

    /**
     * @param string $error
     *
     * @return Field
     */
    public function addError($error)
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @param array $errors
     *
     * @return Field
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    private function applyFilters($value)
    {
        if ($this->hasFilters())
        {
            foreach ($this->getFilters() as $filter)
            {
                $value = $filter->apply($value);
            }
        }

        return $value;
    }

    /**
     * @param RuleInterface[] $rules
     *
     * @return RuleInterface[]
     */
    private function arrangeRules(array $rules)
    {
        $arranged = [];

        foreach ($rules as $rule)
        {
            // needs to be on top because other rules might throw an exception
            if ($rule instanceof FieldDependencyRule)
            {
                array_unshift($arranged, $rule);
                continue;
            }

            if ($rule instanceof IfFilledRule)
            {
                $rule->setFollowUpRules(
                    $this->arrangeRules($rule->getFollowUpRules())
                );
            }

            $arranged[] = $rule;
        }

        return $arranged;
    }
}