<?php

namespace Simplon\Form;

use Simplon\Form\Data\Field;

/**
 * Class FormCollectionData
 * @package Simplon\Form
 */
class FormCollectionData
{
    /**
     * @var Field[]
     */
    private $fields = [];

    /**
     * @var array
     */
    private $fieldSets = [];

    /**
     * @var array
     */
    private $collection;

    /**
     * @param array $initialCollection
     */
    public function __construct(array $initialCollection = [])
    {
        $this->collection = $initialCollection;
    }

    /**
     * @return array
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param array $collection
     *
     * @return FormData
     */
    public function resetCollection(array $collection)
    {
        if (empty($collection) === false)
        {
            $this->fieldSets = [];

            foreach ($collection as $set => $fields)
            {
                foreach ($fields as $id => $value)
                {
                    $this->collection[$set][$id] = $value;
                }
            }

            foreach ($this->getFields() as $field)
            {
                $this->addFieldSet($field);
            }
        }

        return $this;
    }

    /**
     * @param string $id
     *
     * @return Field[]
     * @throws FormException
     */
    public function getFieldFromSets($id)
    {
        $match = [];

        foreach ($this->fieldSets as $set => $fields)
        {
            if (array_key_exists($id, $fields))
            {
                $match[$set] = $fields[$id];
            }
        }

        if (empty($match))
        {
            throw new FormException('Field with ID "' . $id . '" does not exist in any set');
        }

        return $match;
    }

    /**
     * @return array
     */
    public function getFieldSets()
    {
        return $this->fieldSets;
    }

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

        // build field set
        $this->addFieldSet($field);

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
     * @param Field $field
     *
     * @return FormData
     */
    private function addFieldSet(Field $field)
    {
        $setCount = $this->getCollectionCount();

        if ($setCount === 0)
        {
            $setCount = 1;
        }

        for ($f = 0; $f < $setCount; $f++)
        {
            $clone = clone $field;

            $this->fieldSets[$f][$clone->getId()] = $clone->setValue(
                $this->getCollectionValue($f, $clone->getId())
            );
        }

        return $this;
    }

    /**
     * @return Field[]
     */
    private function getFields()
    {
        return $this->fields;
    }

    /**
     * @return int
     */
    private function getCollectionCount()
    {
        return count($this->collection);
    }

    /**
     * @param int $count
     * @param string $id
     *
     * @return mixed|null
     */
    private function getCollectionValue($count, $id)
    {
        if (isset($this->collection[$count][$id]))
        {
            return $this->collection[$count][$id];
        }

        return null;
    }
}