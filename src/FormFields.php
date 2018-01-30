<?php

namespace Simplon\Form;

use Simplon\Form\Data\FormField;

class FormFields
{
    const KEY_CLONE_DATA = 'clones';

    /**
     * @var FormField[]
     */
    private $fields = [];
    /**
     * @var CloneFields[]
     */
    private $cloneFields = [];

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
     * @param null|string $cloneToken
     *
     * @return FormField
     * @throws FormError
     */
    public function get(string $id, ?string $cloneToken = null): FormField
    {
        if ($cloneToken)
        {
            $id = CloneFields::addToken($id, $cloneToken);
        }

        if ($this->has($id))
        {
            return $this->fields[$id];
        }

        throw new FormError('FormField with ID "' . $id . '" does not exist');
    }

    /**
     * @param FormField|CloneFields $field
     *
     * @return FormFields
     * @throws FormError
     */
    public function add($field): self
    {
        if ($field instanceof CloneFields)
        {
            $this->addCloneFields($field);

            return $this;
        }

        if (isset($this->fields[$field->getId()]))
        {
            throw new FormError('FormField ID "' . $field->getId() . '" exists already');
        }

        $this->fields[$field->getId()] = $field;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return CloneFields|null
     */
    public function fetchCloneField(string $id): ?CloneFields
    {
        return $this->cloneFields[$id] ?? null;
    }

    /**
     * @return CloneFields[]|null
     */
    public function fetchCloneFields(): ?array
    {
        return $this->cloneFields;
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
        $cloneFields = [];

        foreach ($this->getAll() as $field)
        {
            if (CloneFields::hasToken($field->getId()))
            {
                $token = CloneFields::findToken($field->getId());
                $idWithoutToken = CloneFields::removeToken($field->getId());
                $cloneFields[$token]['ids'][] = $idWithoutToken;
                $cloneFields[$token]['fields'][$idWithoutToken] = $field->getValue();
            }
            else
            {
                $result[$field->getId()] = $field->getValue();
            }
        }

        if (!empty($cloneFields))
        {
            foreach ($cloneFields as $token => $data)
            {
                if ($id = $this->findCloneFieldsIdByChecksum(md5(json_encode($data['ids']))))
                {
                    $result[self::KEY_CLONE_DATA][$id][] = $data['fields'];
                }
            }
        }

        return $result;
    }

    /**
     * @param array $initialData
     * @param array $requestData
     *
     * @return FormFields
     * @throws FormError
     */
    public function applyBuildData(array $initialData = [], array $requestData = []): self
    {
        $this->applyCloneFieldBuildData($initialData, $requestData)->applyInitialData($initialData);

        return $this;
    }

    /**
     * @param array $initialData
     * @param array $requestData
     *
     * @return FormFields
     * @throws FormError
     */
    private function applyCloneFieldBuildData(array $initialData = [], array $requestData = []): self
    {
        $requestData = FormValidator::normaliseRequestData($requestData);

        foreach ($this->cloneFields as $id => $fields)
        {
            $fields
                ->setInitialData($initialData)
                ->setRequestData($requestData)
            ;

            $fields->detectNewFields();

            foreach ($fields->getBlocks() as $block)
            {
                /** @var FormField[] $block */
                foreach ($block as $field)
                {
                    $this->add($field);
                }
            }

            // add hidden clone field
            $this->add(new FormField($fields->getChecksum()));
        }

        return $this;
    }

    /**
     * @param array $data
     *
     * @return FormFields
     */
    private function applyInitialData(array $data): self
    {
        $cloneFieldIndex = [];

        foreach ($this->getAll() as $field)
        {
            $id = $field->getId();
            $cloneFieldId = CloneFields::fetchCloneFieldId($field->getId());

            if (CloneFields::hasToken($id) && !empty($data[self::KEY_CLONE_DATA][$cloneFieldId]))
            {
                $idWithoutToken = CloneFields::removeToken($id);

                if (!isset($cloneFieldIndex[$idWithoutToken]))
                {
                    $cloneFieldIndex[$idWithoutToken] = 0;
                }

                $currentFieldIndex = $cloneFieldIndex[$idWithoutToken];
                $fieldValue = $data[self::KEY_CLONE_DATA][$cloneFieldId][$currentFieldIndex][$idWithoutToken] ?? null;

                if (isset($fieldValue))
                {
                    $field->setInitialValue($fieldValue);
                    $cloneFieldIndex[$idWithoutToken]++;
                }
            }
            else
            {
                if (isset($data[$id]))
                {
                    $field->setInitialValue($data[$id]);
                }
            }
        }

        return $this;
    }

    /**
     * @param CloneFields $cloneFields
     *
     * @return FormFields
     */
    private function addCloneFields(CloneFields $cloneFields): self
    {
        $this->cloneFields[$cloneFields->getId()] = $cloneFields;

        return $this;
    }

    /**
     * @param string $checksum
     *
     * @return null|string
     */
    private function findCloneFieldsIdByChecksum(string $checksum): ?string
    {
        foreach ($this->cloneFields as $fields)
        {
            if ($fields->getChecksum() === $checksum)
            {
                return $fields->getId();
            }
        }

        return null;
    }
}