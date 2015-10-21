<?php

namespace Simplon\Form;

use Simplon\Form\Data\Field;

/**
 * Class FormData
 * @package Simplon\Form
 */
class FormData
{
    /**
     * @var Field[]
     */
    private $fields = [];

    /**
     * @param string $id
     *
     * @return bool
     */
    public function hasField($id)
    {
        return isset($this->fields[$id]);
    }

    /**
     * @param string $id
     *
     * @return Field
     * @throws FormException
     */
    public function getField($id)
    {
        if ($this->hasField($id))
        {
            return $this->fields[$id];
        }

        throw new FormException('Field with ID "' . $id . '" does not exist');
    }

    /**
     * @param Field $field
     *
     * @return FormData
     * @throws FormException
     */
    public function addField(Field $field)
    {
        if (isset($this->fields[$field->getId()]))
        {
            throw new FormException('Field ID "' . $field->getId() . '" exists already');
        }

        $this->fields[$field->getId()] = $field;

        return $this;
    }

    /**
     * @param Field[] $fields
     *
     * @return FormData
     * @throws FormException
     */
    public function setFields(array $fields)
    {
        foreach ($fields as $field)
        {
            $this->addField($field);
        }

        return $this;
    }

    /**
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $result = [];

        foreach ($this->getFields() as $field)
        {
            $result[$field->getId()] = $field->getValue();
        }

        return $result;
    }
}