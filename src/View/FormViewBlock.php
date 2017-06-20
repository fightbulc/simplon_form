<?php

namespace Simplon\Form\View;

/**
 * @package Simplon\Form\View
 */
class FormViewBlock
{
    /**
     * @var array
     */
    private static $renderedCloneFields = [];
    /**
     * @var string
     */
    private $id;
    /**
     * @var string|null
     */
    private $header;
    /**
     * @var FormViewRow[]
     */
    private $rows;
    /**
     * @var string
     */
    private $cloneChecksum;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @param string $cloneChecksum
     *
     * @return FormViewBlock
     */
    public function setCloneChecksum(string $cloneChecksum): FormViewBlock
    {
        $this->cloneChecksum = $cloneChecksum;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCloneable(): bool
    {
        return $this->cloneChecksum !== null;
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
    public function getHeader(): ?string
    {
        return $this->header;
    }

    /**
     * @return bool
     */
    public function hasHeader(): bool
    {
        return empty($this->header) === false;
    }

    /**
     * @param string $header
     *
     * @return FormViewBlock
     */
    public function setHeader(string $header): self
    {
        $this->header = $header;

        return $this;
    }

    /**
     * @return FormViewRow[]
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * @param FormViewRow $row
     *
     * @return FormViewBlock
     */
    public function addRow(FormViewRow $row): self
    {
        $this->rows[] = $row;

        return $this;
    }

    /**
     * @return string
     * @throws \Simplon\Form\FormError
     */
    public function render(): string
    {
        $html = '{clone}{header}{rows}';

        $renderedRows = [];

        foreach ($this->getRows() as $row)
        {
            $renderedRows[] = $row->render();
        }

        return RenderHelper::placeholders(
            $html,
            [
                'header' => $this->renderHeader(),
                'rows'   => join('', $renderedRows),
                'clone'  => $this->getCloneDataFields(),
            ]
        );
    }

    /**
     * @return null|string
     */
    private function getCloneChecksum(): ?string
    {
        return $this->cloneChecksum;
    }

    /**
     * @return null|string
     */
    private function renderHeader(): ?string
    {
        if ($this->hasHeader())
        {
            return '<h4 class="ui dividing header">' . $this->getHeader() . '</h4>';
        }

        return null;
    }

    /**
     * @return null|string
     */
    private function getCloneDataFields(): ?string
    {
        if ($checksum = $this->getCloneChecksum())
        {
            $code = [];

            if (!in_array($checksum, self::$renderedCloneFields))
            {
                $code[] = '<input type="hidden" id="' . $checksum . '" name="form[' . $checksum . ']" value="">';
                self::$renderedCloneFields[] = $checksum;
            }

            $code[] = '
<div class="uk-sortable-handle custom-nestable-handle">
    <div class="ui right simple dropdown icon button" style="padding:.3em;margin:0">
        <i class="setting icon"></i>
        <div class="menu">
            <div class="item clone-block" data-block="' . $checksum . '" data-token="' . $this->getId() . '">
                Clone
            </div>
            <div class="item clone-remove" data-block="' . $checksum . '" data-token="' . $this->getId() . '">
                Remove
            </div>
        </div>
    </div>
</div>
';

            return implode("\n", $code);
        }

        return null;
    }
}