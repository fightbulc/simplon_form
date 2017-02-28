<?php

namespace Simplon\Form;

use Simplon\Form\Data\FormField;
use Simplon\Form\Data\Rules\RuleException;
use Simplon\Form\Security\Csrf;

/**
 * @package Simplon\Form
 */
class FormValidator
{
    /**
     * @var string|null
     */
    private $scope;
    /**
     * @var array
     */
    private $requestData = [];
    /**
     * @var FormFields[]
     */
    private $fields = [];
    /**
     * @var FormField[]
     */
    private $errorFields = [];
    /**
     * @var Csrf|null
     */
    private $csrf;
    /**
     * @var bool
     */
    private $formIsValid = false;

    /**
     * @param array $requestData
     */
    public function __construct(array $requestData = [])
    {
        if (empty($requestData['form']) === false)
        {
            $requestData = $requestData['form'];
        }

        $this->requestData = $requestData;
    }

    /**
     * @param string $scope
     *
     * @return FormValidator
     */
    public function setScope(string $scope): self
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * @return Csrf|null
     */
    public function getCsrf(): ?Csrf
    {
        return $this->csrf;
    }

    /**
     * @param Csrf $csrf
     *
     * @return FormValidator
     */
    public function setCsrf(Csrf $csrf): self
    {
        $this->csrf = $csrf;

        return $this;
    }

    /**
     * @return FormFields[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param FormFields $fields
     *
     * @return FormValidator
     */
    public function addFields(FormFields $fields): self
    {
        $this->fields[] = $this->applyRequestData($fields);

        return $this;
    }

    /**
     * @param FormFields[] $fields
     *
     * @return FormValidator
     */
    public function setFields(array $fields): self
    {
        foreach ($fields as $item)
        {
            $this->addFields($item);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasBeenSubmitted(): bool
    {
        // nothing to check against
        if ($this->hasRequestData() === false)
        {
            return false;
        }

        // in case we require a scope and scope is not within request data
        if ($this->getScope() && $this->getRequestData($this->getScope()) === null)
        {
            return false;
        }

        return true;
    }

    /**
     * @return FormValidator
     * @throws FormError
     */
    public function validate(): self
    {
        if ($this->hasBeenSubmitted())
        {
            // run check if CSRF is enabled
            if ($this->getCsrf() && $this->getCsrf()->isValid($this->requestData) === false)
            {
                throw new FormError('CSRF mismatch');
            }

            // validate all fields
            foreach ($this->getFields() as $fields)
            {
                foreach ($fields->getAll() as $field)
                {
                    foreach ($field->getRules() as $rule)
                    {
                        try
                        {
                            $rule->apply($field);
                        }
                        catch (RuleException $e)
                        {
                            $field->addError($e->getMessage());
                        }
                    }

                    if ($field->hasErrors())
                    {
                        $this->addErrorField($field);
                    }
                }
            }

            $this->setFormIsValid(
                $this->hasErrorFields() === false
            );
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->formIsValid === true;
    }

    /**
     * @return bool
     */
    public function hasErrorFields(): bool
    {
        return empty($this->errorFields) === false;
    }

    /**
     * @return FormField[]
     */
    public function getErrorFields(): array
    {
        return $this->errorFields;
    }

    /**
     * @return array
     */
    public function getErrorMessages(): array
    {
        $errors = [];

        if ($this->hasErrorFields())
        {
            foreach ($this->getErrorFields() as $field)
            {
                $errors[] = [
                    'id'     => $field->getId(),
                    'value'  => $field->getValue(),
                    'errors' => $field->getErrors(),
                ];
            }

        }

        return $errors;
    }

    /**
     * @param FormFields $fields
     *
     * @return FormFields
     */
    private function applyRequestData(FormFields $fields): FormFields
    {
        if ($this->hasBeenSubmitted())
        {
            foreach ($fields->getAll() as $field)
            {
                $field->setValue(
                    $this->getRequestData($field->getId())
                );
            }
        }

        return $fields;
    }

    /**
     * @param FormField $field
     *
     * @return FormValidator
     */
    private function addErrorField(FormField $field): self
    {
        $this->errorFields[] = $field;

        return $this;
    }

    /**
     * @return bool
     */
    private function hasRequestData(): bool
    {
        return empty($this->requestData) === false;
    }

    /**
     * @param string $id
     *
     * @return mixed|null
     */
    private function getRequestData(string $id)
    {
        if (isset($this->requestData[$id]))
        {
            return $this->requestData[$id];
        }

        return null;
    }

    /**
     * @return string|null
     */
    private function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @param bool $formIsValid
     *
     * @return FormValidator
     */
    private function setFormIsValid(bool $formIsValid): self
    {
        $this->formIsValid = $formIsValid;

        return $this;
    }
}