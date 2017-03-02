<?php

namespace Simplon\Form;

use Simplon\Form\Data\FormField;

/**
 * @package Simplon\Form
 */
class FormFields
{
    /**
     * @var FormField[]
     */
    private $fields = [];
    /**
     * @var null|int
     */
    private $cloneIteration;

    /**
     * @return int|null
     */
    public function getCloneIteration(): ?int
    {
        return $this->cloneIteration;
    }

    /**
     * @param int|null $cloneIteration
     *
     * @return FormFields
     */
    public function setCloneIteration(int $cloneIteration): self
    {
        $this->cloneIteration = $cloneIteration;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->fields[$id]);
    }

    /**
     * @param string $id
     *
     * @return FormField
     * @throws FormError
     */
    public function get(string $id): FormField
    {
        if ($cloneIteration)
        {
            $id .= '-' . $cloneIteration;
        }

        if ($this->has($id))
        {
            return $this->fields[$id];
        }

        throw new FormError('FormField with ID "' . $id . '" does not exist');
    }

    /**
     * @param FormField $field
     *
     * @return FormFields
     * @throws FormError
     */
    public function add(FormField $field): self
    {
        if (isset($this->fields[$field->getId()]))
        {
            throw new FormError('FormField ID "' . $field->getId() . '" exists already');
        }

        $this->fields[$field->getId()] = $field;

        return $this;
    }

    /**
     * @param FormField[] $fields
     *
     * @return FormFields
     * @throws FormError
     */
    public function reset(array $fields): self
    {
        $this->fields = [];

        foreach ($fields as $field)
        {
            $this->add($field);
        }

        return $this;
    }

    /**
     * @return FormField[]
     */
    public function getAll(): array
    {
        return $this->fields;
    }

    /**
     * @param string $id
     *
     * @return mixed|null
     * @throws FormError
     */
    public function getData(string $id)
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
    public function getAllData(): array
    {
        $result = [];

        foreach ($this->getAll() as $field)
        {
            $result[$field->getId()] = $field->getValue();
        }

        return $result;
    }

    /**
     * @param array $data
     *
     * @return FormFields
     */
    public function applyInitialData(array $data): self
    {
        foreach ($this->getAll() as $field)
        {
            if (isset($data[$field->getId()]))
            {
                $field->setInitialValue($data[$field->getId()]);
            }
        }

        return $this;
    }
}