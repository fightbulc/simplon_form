<?php

namespace Simplon\Form\View;

/**
 * @package Simplon\Form\View
 */
class FormCloneViewBlock extends FormViewBlock
{
    /**
     * @var int
     */
    protected $cloneIteration = 0;

    /**
     * @return null|int
     */
    public function getCloneIteration(): ?int
    {
        return $this->cloneIteration;
    }

    /**
     * @param array $requestData
     *
     * @return FormViewBlock
     */
    public function setCloneIteration(array $requestData = []): FormViewBlock
    {
        $value = 0;

        if (!empty($requestData['form']))
        {
            $requestData = $requestData['form'];
        }

        if (!empty($requestData['clone']))
        {
            $value = (int)$requestData['clone']['__iteration'];
            $value = $value > 0 ? $value : 0;
        }

        $this->cloneIteration = $value;

        return $this;
    }

    /**
     * @return bool
     */
    protected function hasCloning(): bool
    {
        return $this->getCloneIteration() !== null;
    }

    /**
     * @param array $renderedRows
     *
     * @return string
     */
    protected function proxyCloning(array $renderedRows): string
    {
        $rowsString = implode('', $renderedRows);

        if ($this->hasCloning())
        {
            $clonedRows = [];

            for ($i = 0; $i <= $this->getCloneIteration(); $i++)
            {
                $row = $rowsString;
                $row = preg_replace('/name="form\[(.*?)\]"/', 'name="form[clone][\\1][' . $i . ']"', $row);
                $row = preg_replace('/id="form\-(.*?)"/', 'id="form-clone-\\1-' . $i . '"', $row);
                $clonedRows[] = $row;
            }

            $rowsString = implode('', $clonedRows);
        }

        return $rowsString;
    }

    /**
     * @return null|string
     */
    protected function renderCloning(): ?string
    {
        if ($this->hasCloning())
        {
            return '
            <div style="margin-top:20px">
                <input type="text" name="form[clone][__iteration]" value="' . $this->getCloneIteration() . '">
                <input type="button" value="Clone" class="ui button">
            </div>';
        }

        return null;
    }
}