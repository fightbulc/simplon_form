<?php

namespace Simplon\Form;

use Simplon\Form\Data\Field;

/**
 * Class FormFields
 * @package Simplon\Form
 */
class FormFields
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
    public function has($id)
    {
        return isset($this->fields[$id]);
    }

    /**
     * @param string $id
     *
     * @return Field
     * @throws FormException
     */
    public function get($id)
    {
        if ($this->has($id))
        {
            return $this->fields[$id];
        }

        throw new FormException('Field with ID "' . $id . '" does not exist');
    }

    /**
     * @param Field $field
     *
     * @return FormFields
     * @throws FormException
     */
    public function add(Field $field)
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
     * @return FormFields
     * @throws FormException
     */
    public function reset(array $fields)
    {
        $this->fields = [];

        foreach ($fields as $field)
        {
            $this->add($field);
        }

        return $this;
    }

    /**
     * @return Field[]
     */
    public function getAllFields()
    {
        return $this->fields;
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function getData($id)
    {
        if ($this->has($id))
        {
            return $this->get($id)->getValue();
        }

        return null;
    }

    /**
     * @return array
     */
    public function getAllData()
    {
        $result = [];

        foreach ($this->getAllFields() as $field)
        {
            $result[$field->getId()] = $field->getValue();
        }

        return $result;
    }
}