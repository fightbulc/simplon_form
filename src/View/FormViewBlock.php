<?php

namespace Simplon\Form\View;

/**
 * @package Simplon\Form\View
 */
class FormViewBlock
{
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
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
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
     */
    public function render(): string
    {
        $html = '{header}{rows}';

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
            ]
        );
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
}