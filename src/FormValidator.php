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
     * @var FormData[]
     */
    private $formData = [];

    /**
     * @var array
     */
    private $errorFields = [];

    /**
     * @var Csrf
     */
    private $csrf;

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
     * @return FormData[]
     */
    public function getFormData()
    {
        return $this->formData;
    }

    /**
     * @param FormData $data
     *
     * @return FormValidator
     */
    public function addFormData(FormData $data)
    {
        $this->formData[] = $data;

        return $this;
    }

    /**
     * @param FormData[] $formData
     *
     * @return FormValidator
     */
    public function setFormData(array $formData)
    {
        $this->formData = $formData;

        return $this;
    }

    /**
     * @return bool|null
     * @throws FormException
     */
    public function isValid()
    {
        if ($this->hasRequestData() === false)
        {
            return null;
        }

        if ($this->getScope() && $this->getRequestData($this->getScope()) === null)
        {
            return null;
        }

        if ($this->getCsrf() && $this->getCsrf()->isValid($this->requestData) === false)
        {
            throw new FormException('CSRF mismatch');
        }

        foreach ($this->getFormData() as $data)
        {
            foreach ($data->getFields() as $field)
            {
                $field->setValue(
                    $this->getRequestData($field->getId())
                );

                foreach ($field->getRules() as $rule)
                {
                    try
                    {
                        $rule->isValid($field);
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

        return $this->hasErrorFields() === false;
    }

    /**
     * @return Field[]
     */
    public function getErrorFields()
    {
        return $this->errorFields;
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
}