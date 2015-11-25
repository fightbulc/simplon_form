<?php

namespace Simplon\Form;

use Simplon\Form\Data\Field;
use Simplon\Form\Data\Rules\RuleException;
use Simplon\Form\Security\Csrf;

/**
 * Class FormValidator
 * @package Simplon\Form
 */
class FormValidator
{
    /**
     * @var array
     */
    private $requestData = [];

    /**
     * @var string
     */
    private $scope;

    /**
     * @var FormFields[]
     */
    private $formFields = [];

    /**
     * @var Field[]
     */
    private $errorFields = [];

    /**
     * @var Csrf
     */
    private $csrf;

    /**
     * @var bool
     */
    private $hasBeenValidated = false;

    /**
     * @var bool|null
     */
    private $validationResult;

    /**
     * @param array $requestData
     */
    public function __construct(array $requestData = [])
    {
        if (empty($requestData['form']) === false)
        {
            $this->requestData = $requestData['form'];
        }
    }

    /**
     * @param string $scope
     *
     * @return FormValidator
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * @return Csrf
     */
    public function getCsrf()
    {
        return $this->csrf;
    }

    /**
     * @param Csrf $csrf
     *
     * @return FormValidator
     */
    public function setCsrf(Csrf $csrf)
    {
        $this->csrf = $csrf;

        return $this;
    }

    /**
     * @return FormFields[]
     */
    public function getForm()
    {
        return $this->formFields;
    }

    /**
     * @param FormFields $fields
     *
     * @return FormValidator
     */
    public function addForm(FormFields $fields)
    {
        $this->formFields[] = $fields;

        return $this;
    }

    /**
     * @param FormFields[] $formFields
     *
     * @return FormValidator
     */
    public function setForm(array $formFields)
    {
        $this->formFields = $formFields;

        return $this;
    }

    /**
     * @return bool|null
     * @throws FormException
     */
    public function isValid()
    {
        if ($this->hasBeenValidated() === false)
        {
            $this->setHasBeenValidated();

            // nothing to check against
            if ($this->hasRequestData() === false)
            {
                $this->setValidationResult(null);

                return null;
            }

            // in case we require a scope and scope is not within request data
            if ($this->getScope() && $this->getRequestData($this->getScope()) === null)
            {
                $this->setValidationResult(null);

                return null;
            }

            // run check if CSRF is enabled
            if ($this->getCsrf() && $this->getCsrf()->isValid($this->requestData) === false)
            {
                throw new FormException('CSRF mismatch');
            }

            // validate all fields
            foreach ($this->getForm() as $fields)
            {
                foreach ($fields->getAll() as $field)
                {
                    $field->setValue(
                        $this->getRequestData($field->getId())
                    );

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

            $this->setValidationResult($this->hasErrorFields() === false);
        }

        return $this->getValidationResult();
    }

    /**
     * @return Field[]
     */
    public function getErrorFields()
    {
        return $this->errorFields;
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        $errors = [];

        if ($this->hasErrorFields())
        {
            foreach ($this->errorFields as $field)
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
     * @return bool
     */
    public function hasErrorFields()
    {
        return empty($this->errorFields) === false;
    }

    /**
     * @param Field $field
     *
     * @return FormValidator
     */
    private function addErrorField(Field $field)
    {
        $this->errorFields[] = $field;

        return $this;
    }

    /**
     * @return bool
     */
    private function hasRequestData()
    {
        return empty($this->requestData) === false;
    }

    /**
     * @param string $id
     *
     * @return mixed|null
     */
    private function getRequestData($id)
    {
        if (isset($this->requestData[$id]))
        {
            return $this->requestData[$id];
        }

        return null;
    }

    /**
     * @return string
     */
    private function getScope()
    {
        return $this->scope;
    }

    /**
     * @return boolean
     */
    private function hasBeenValidated()
    {
        return $this->hasBeenValidated;
    }

    /**
     * @return FormValidator
     */
    private function setHasBeenValidated()
    {
        $this->hasBeenValidated = true;

        return $this;
    }

    /**
     * @return boolean|null
     */
    private function getValidationResult()
    {
        return $this->validationResult;
    }

    /**
     * @param bool|null $result
     *
     * @return FormValidator
     */
    private function setValidationResult($result)
    {
        $this->validationResult = $result;

        return $this;
    }
}