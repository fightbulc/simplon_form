<?php

namespace Simplon\Form;

use Simplon\Form\View\RenderHelper;

/**
 * Class FormBlock
 * @package Simplon\Form
 */
class FormBlock
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $header;

    /**
     * @var FormRow[]
     */
    private $rows;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return bool
     */
    public function hasHeader()
    {
        return empty($this->header) === false;
    }

    /**
     * @param string $header
     *
     * @return FormBlock
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * @return FormRow[]
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param FormRow $row
     *
     * @return FormBlock
     */
    public function addRow(FormRow $row)
    {
        $this->rows[] = $row;

        return $this;
    }

    /**
     * @return string
     */
    public function renderBlock()
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
    private function renderHeader()
    {
        if ($this->hasHeader())
        {
            return '<h4 class="ui dividing header">' . $this->getHeader() . '</h4>';
        }

        return null;
    }
}