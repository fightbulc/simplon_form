<?php

namespace Simplon\Form\View;

use Simplon\Form\CloneFields;
use Simplon\Form\FormError;

class CloneFormViewBlock
{
    /**
     * @var CloneFields
     */
    private $cloneFields;

    /**
     * @param array $formViewBlocks
     * @param callable $template
     *
     * @return string
     */
    public static function render(array $formViewBlocks, callable $template): string
    {
        /** @noinspection HtmlUnknownAttribute */
        $html = ['<ul uk-sortable="cls-custom: ui form uk-sortable-transition">'];

        foreach ($formViewBlocks as $block)
        {
            $html[] = '<li>';
            $html[] = $template($block);
            $html[] = '</li>';
        }

        $html[] = '</ul>';

        return implode('', $html);
    }

    /**
     * @param CloneFields $cloneFields
     */
    public function __construct(CloneFields $cloneFields)
    {
        $this->cloneFields = $cloneFields;
    }

    /**
     * @param callable $builder
     *
     * @return FormViewBlock[]
     * @throws FormError
     */
    public function build(callable $builder): array
    {
        $blocks = [];

        foreach ($this->cloneFields->getBlocks() as $token => $block)
        {
            $viewBlock = new FormViewBlock(CloneFields::addToken($this->cloneFields->getId(), $token));
            $viewBlock->setCloneChecksum($this->cloneFields->getChecksum());

            $blocks[] = $builder($viewBlock, $token);
        }

        return $blocks;

    }
}