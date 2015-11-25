<?php

namespace Simplon\Form;

use Simplon\Form\Data\Field;

/**
 * Class Form
 * @package Simplon\Form
 */
class Form
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
     * @return Form
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
     * @return Form
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
    public function getAll()
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $result = [];

        foreach ($this->getAll() as $field)
        {
            $result[$field->getId()] = $field->getValue();
        }

        return $result;
    }
}