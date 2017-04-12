<?php

namespace Simplon\Form\Data;

use Simplon\Form\Data\Filters\FilterInterface;
use Simplon\Form\Data\Filters\TrimFilter;
use Simplon\Form\Data\Rules\FieldDependencyRule;
use Simplon\Form\Data\Rules\IfFilledRule;
use Simplon\Form\Data\Rules\RuleInterface;
use Simplon\Form\FormError;
use Simplon\Form\View\Elements\Support\Meta\MetaInterface;

/**
 * @package Simplon\Form\Data
 */
class FormField
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
     * @param bool $setTrimFilter
     *
     * @throws FormError
     */
    public function __construct(string $id, bool $setTrimFilter = true)
    {
        $this->setId($id);

        // most of the fields will need this so let's make it default

        if ($setTrimFilter)
        {
            $this->addFilter(new TrimFilter());
        }
    }

    /**
     * @return string
     */
    public function getId(): string
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
     * @return FormField
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
     * @return FormField
     */
    public function setValue($value): self
    {
        $this->value = $this->applyFilters($value);

        return $this;
    }

    /**
     * @param string $key
     *
     * @return null|mixed
     */
    public function getMeta(string $key)
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
    public function hasMeta(string $key): bool
    {
        return empty($this->meta[$key]) === false;
    }

    /**
     * @param MetaInterface $meta
     *
     * @return FormField
     */
    public function addMeta(MetaInterface $meta): self
    {
        $this->meta[$meta->getKey()] = $meta->getData();

        return $this;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return bool
     */
    public function hasFilters(): bool
    {
        return empty($this->filters) === false;
    }

    /**
     * @param FilterInterface $filter
     *
     * @return FormField
     */
    public function addFilter(FilterInterface $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @param FilterInterface[] $filters
     *
     * @return FormField
     */
    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return RuleInterface[]
     */
    public function getRules(): array
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
    public function hasRules(): bool
    {
        return empty($this->rules) === false;
    }

    /**
     * @return bool
     */
    public function hasDirectRules(): bool
    {
        if ($this->hasRules())
        {
            foreach ($this->rules as $rule)
            {
                if ($rule instanceof IfFilledRule)
                {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param RuleInterface $rule
     *
     * @return FormField
     */
    public function addRule(RuleInterface $rule): self
    {
        $this->rules[] = $rule;
        $this->arrangedRules = false;

        return $this;
    }

    /**
     * @param RuleInterface[] $rules
     *
     * @return FormField
     */
    public function setRules(array $rules): self
    {
        $this->rules = $rules;
        $this->arrangedRules = false;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return empty($this->errors) === false;
    }

    /**
     * @param string $error
     *
     * @return FormField
     */
    public function addError(string $error): self
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @param array $errors
     *
     * @return FormField
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return FormField
     * @throws FormError
     */
    protected function setId(string $id): self
    {
        if (preg_match('/^[0-9a-zA-Z_-]+$/u', $id) === 0)
        {
            throw new FormError('ID "' . $id . '" has invalid characters. Please use only [a-zA-Z_-]');
        }

        $this->id = $id;

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
    private function arrangeRules(array $rules): array
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