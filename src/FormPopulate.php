<?php

namespace Simplon\Form;

/**
 * Class FormPopulate
 * @package Simplon\Form
 */
class FormPopulate
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
    private $fields = [];

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
     * @return FormPopulate
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * @return FormFields[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param FormFields $fields
     *
     * @return FormPopulate
     */
    public function addFields(FormFields $fields)
    {
        $this->fields[] = $fields;

        return $this;
    }

    /**
     * @return bool|null
     * @throws FormException
     */
    public function populate()
    {
        // nothing to check against
        if ($this->hasRequestData() === false)
        {
            return null;
        }

        // in case we require a scope and scope is not within request data
        if ($this->getScope() && $this->getRequestData($this->getScope()) === null)
        {
            return null;
        }

        // validate all fields
        foreach ($this->getFields() as $fields)
        {
            foreach ($fields->getAllFields() as $field)
            {
                $field->setValue(
                    $this->getRequestData($field->getId())
                );
            }
        }
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