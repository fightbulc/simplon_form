<?php

namespace Simplon\Form;

use Simplon\Form\Data\FormField;
use Simplon\Helper\SecurityUtil;

/**
 * @package Simplon\Form
 */
class CloneFields
{
    const CLONE_PATTERN = '_CLB';

    /**
     * @var string
     */
    private $id;
    /**
     * @var array
     */
    private $requestData;
    /**
     * @var array
     */
    private $storedData;
    /**
     * @var string
     */
    private $checksum;
    /**
     * @var FormField[]
     */
    private $coreFields = [];
    /**
     * @var FormField[]
     */
    private $cloneBlocks = [];
    /**
     * @var string
     */
    private $cloneAfterBlockToken;
    /**
     * @var string
     */
    private $removeBlockToken;
    /**
     * @var bool
     */
    private $changedFields = false;

    /**
     * @param string $id
     *
     * @return string
     */
    public static function hasToken(string $id): string
    {
        return strpos($id, self::CLONE_PATTERN) !== false;
    }

    /**
     * @param string $id
     *
     * @return null|string
     */
    public static function findToken(string $id): ?string
    {
        preg_match('/' . self::CLONE_PATTERN . '(.*?)$/i', $id, $match);

        if (!empty($match[1]))
        {
            return $match[1];
        }

        return null;
    }

    /**
     * @param string $id
     * @param string $token
     *
     * @return string
     */
    public static function addToken(string $id, string $token): string
    {
        return $id . self::CLONE_PATTERN . $token;
    }

    /**
     * @param string $id
     *
     * @return string
     */
    public static function removeToken(string $id): string
    {
        return preg_replace('/' . self::CLONE_PATTERN . '.*?$/i', '', $id);
    }

    /**
     * @param string $id
     * @param array $requestData
     * @param array $storedData
     */
    public function __construct(string $id, array $requestData = [], array $storedData = [])
    {
        $this->id = $id;
        $this->requestData = FormValidator::normaliseRequestData($requestData);
        $this->storedData = $storedData;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getChecksum(): ?string
    {
        return $this->checksum;
    }

    /**
     * @param FormField $field
     *
     * @return CloneFields
     * @throws FormError
     */
    public function add(FormField $field): self
    {
        if (isset($this->coreFields[$field->getId()]))
        {
            throw new FormError('FormField ID "' . $field->getId() . '" exists already');
        }

        $this->coreFields[$field->getId()] = $field;

        $this->buildChecksum();

        return $this;
    }

    /**
     * @return array
     * @throws FormError
     */
    public function getBlocks(): array
    {
        if (empty($this->cloneBlocks))
        {
            // add initial fields
            if (empty($this->requestData))
            {
                foreach ($this->createInitialBlock() as $block)
                {
                    $this->addCloneBlock($block);
                }
            }
            else
            {
                // add new fields
                $newBlock = $this->createNewBlock();

                // add existing clone fields
                $discoveredBlocks = $this->discoverBlocks();

                foreach ($discoveredBlocks as $token => $fields)
                {
                    $this->addCloneBlock([$token => $fields]);

                    if (!empty($newBlock) && $token === $this->cloneAfterBlockToken)
                    {
                        $this->addCloneBlock($newBlock);
                    }
                }
            }
        }

        return $this->cloneBlocks;
    }

    /**
     * @return bool
     */
    public function hasChangedFields(): bool
    {
        return $this->changedFields;
    }

    /**
     * @return void
     */
    public function detectNewFields(): void
    {
        if (!$this->cloneAfterBlockToken)
        {
            if (isset($this->requestData[$this->getChecksum()]))
            {
                $data = $this->requestData[$this->getChecksum()];
                $token = self::findToken($data);

                if (preg_match('/^a\-/i', $data))
                {
                    $this->cloneAfterBlockToken = $token;
                }
                elseif (preg_match('/^r\-/i', $data))
                {
                    $this->removeBlockToken = $token;
                }
            }
        }
    }

    /**
     * @return CloneFields
     */
    private function buildChecksum(): self
    {
        $ids = [];

        if (!empty($this->coreFields))
        {
            foreach ($this->coreFields as $field)
            {
                $ids[] = $field->getId();
            }
        }

        $this->checksum = md5(json_encode($ids));

        return $this;
    }

    /**
     * @param array $block
     *
     * @return CloneFields
     */
    private function addCloneBlock(array $block): self
    {
        $token = key($block);

        foreach ($block as $fields)
        {
            /** @var FormField[] $fields */
            foreach ($fields as $field)
            {
                $this->cloneBlocks[$token][$field->getId()] = $field;
            }
        }

        return $this;
    }

    /**
     * @param string $id
     *
     * @return null|FormField
     */
    private function getCoreField(string $id): ?FormField
    {
        if (isset($this->coreFields[$id]))
        {
            return $this->coreFields[$id];
        }

        return null;
    }

    /**
     * @return FormField[]
     */
    private function getAllCoreFields(): array
    {
        return $this->coreFields;
    }

    /**
     * @return int
     */
    private function getInitialBlocksCount(): int
    {
        if (!empty($this->storedData[FormFields::KEY_CLONE_DATA]))
        {
            $count = 0;

            foreach ($this->storedData[FormFields::KEY_CLONE_DATA] as $sets)
            {
                foreach ($sets as $id => $value)
                {
                    // do we have a block field?

                    if (!empty($this->coreFields[$id]))
                    {
                        $count++;
                        break; // one field equals one block
                    }
                }
            }

            if ($count > 0)
            {
                return $count;
            }
        }

        return 1;
    }

    /**
     * @return array|null
     * @throws FormError
     */
    private function createInitialBlock(): ?array
    {
        if (empty($this->requestData))
        {
            $blocks = [];

            for ($i = 0; $i < $this->getInitialBlocksCount(); $i++)
            {
                $blocks[] = $this->createCloneFields();
            }

            return $blocks;
        }

        return null;
    }

    /**
     * @return CloneFields
     */
    private function markAsChangedFields(): CloneFields
    {
        $this->changedFields = true;

        return $this;
    }

    /**
     * @return array|null
     * @throws FormError
     */
    private function createNewBlock(): ?array
    {
        if (!empty($this->requestData) && $this->cloneAfterBlockToken)
        {
            $this->markAsChangedFields();

            return $this->createCloneFields();
        }

        return null;
    }

    /**
     * @return array
     * @throws FormError
     */
    private function createCloneFields(): array
    {
        $fields = [];

        if (!empty($this->coreFields))
        {
            $token = $this->generateToken();

            foreach ($this->getAllCoreFields() as $coreField)
            {
                $field = clone $coreField;
                $field->setId(self::addToken($field->getId(), $token));
                $fields[$token][$field->getId()] = $field;
            }
        }

        return $fields;
    }

    /**
     * @return array
     * @throws FormError
     */
    private function discoverBlocks(): array
    {
        $fields = [];

        if (!empty($this->requestData))
        {
            foreach ($this->requestData as $id => $value)
            {
                if (self::hasToken($id))
                {
                    $token = self::findToken($id);

                    if ($this->removeBlockToken && $this->removeBlockToken === $token)
                    {
                        $this->markAsChangedFields();

                        continue;
                    }

                    $idWithoutToken = self::removeToken($id);

                    if ($coreField = $this->getCoreField($idWithoutToken))
                    {
                        $field = clone $coreField;
                        $field->setId($id);
                        $fields[$token][$field->getId()] = $field;
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * @return string
     */
    private function generateToken(): string
    {
        return SecurityUtil::createRandomToken(8, null, SecurityUtil::TOKEN_UPPERCASE_LETTERS_NUMBERS);
    }
}