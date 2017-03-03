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
     * @param string $id
     * @param array $requestData
     * @param callable $callback
     */
    public function __construct(string $id, array $requestData, callable $callback)
    {
        parent::__construct($id);

        $this->setCloneIteration($requestData);

        for ($i = 0; $i <= $this->getCloneIteration(); $i++)
        {
            $callback($this, $i);
        }
    }

    /**
     * @return int
     */
    public function getCloneIteration(): int
    {
        return $this->cloneIteration;
    }

    /**
     * @return string
     */
    public function renderBlock(): string
    {
        $html = '{header}{rows}{cloning}';

        $renderedRows = [];

        foreach ($this->getRows() as $row)
        {
            $renderedRows[] = $row->render();
        }

        return RenderHelper::placeholders(
            $html,
            [
                'header'  => $this->renderHeader(),
                'rows'    => implode('', $renderedRows),
                'cloning' => $this->renderCloning(),
            ]
        );
    }

    /**
     * @param array $requestData
     *
     * @return FormCloneViewBlock
     */
    protected function setCloneIteration(array $requestData = []): self
    {
        if (!empty($requestData['form']))
        {
            $requestData = $requestData['form'];
        }

        if (!empty($requestData['clone'][$this->getId() . '-iteration']))
        {
            $value = (int)$requestData['clone'][$this->getId() . '-iteration'];
            $this->cloneIteration = $value > 0 ? $value : 0;
        }


        return $this;
    }

    /**
     * @param array $renderedRows
     *
     * @return string
     */
    protected function proxyCloning(array $renderedRows): string
    {
        $rowsString = implode('', $renderedRows);

//        if ($this->hasCloning())
//        {
//            $clonedRows = [];
//
//            for ($i = 0; $i <= $this->getCloneIteration(); $i++)
//            {
//                $row = $rowsString;
//                $row = preg_replace('/name="form\[(.*?)\]"/', 'name="form[clone][\\1][' . $i . ']"', $row);
//                $row = preg_replace('/id="form\-(.*?)"/', 'id="form-clone-\\1-' . $i . '"', $row);
//                $clonedRows[] = $row;
//            }
//
//            $rowsString = implode('', $clonedRows);
//        }

        return $rowsString;
    }

    /**
     * @return string
     */
    protected function renderCloning(): string
    {
        return '
        <div style="margin-top:20px">
            <input type="text" name="form[clone][' . $this->getId() . '-iteration]" value="' . $this->getCloneIteration() . '">
            <input type="button" value="Clone" class="ui button">
        </div>';
    }
}