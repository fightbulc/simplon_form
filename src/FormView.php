<?php

namespace Simplon\Form;

use Simplon\Form\Security\Csrf;
use Simplon\Form\View\Elements\CancelElement;
use Simplon\Form\View\Elements\SubmitElement;
use Simplon\Form\View\RenderHelper;
use Simplon\Phtml\Phtml;
use Simplon\Phtml\PhtmlException;

/**
 * Class FormView
 * @package Simplon\Form
 */
class FormView
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method = 'POST';

    /**
     * @var string
     */
    private $acceptCharset = 'utf-8';

    /**
     * @var SubmitElement
     */
    private $submitElement;

    /**
     * @var CancelElement
     */
    private $cancelElement;

    /**
     * @var FormBlock[]
     */
    private $blocks;

    /**
     * @var Csrf
     */
    private $csrf;

    /**
     * @var bool
     */
    private $hasErrors;

    /**
     * FormView constructor.
     *
     * @param $id
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
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return FormView
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return FormView
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getAcceptCharset()
    {
        return $this->acceptCharset;
    }

    /**
     * @return SubmitElement
     */
    public function getSubmitElement()
    {
        return $this->submitElement;
    }

    /**
     * @return Csrf
     */
    public function getCsrf()
    {
        return $this->csrf;
    }

    /**
     * @param Csrf $csrf
     *
     * @return FormView
     */
    public function setCsrf(Csrf $csrf)
    {
        $this->csrf = $csrf;

        return $this;
    }

    /**
     * @param SubmitElement $element
     *
     * @return FormView
     */
    public function setSubmitElement(SubmitElement $element)
    {
        $this->submitElement = $element;

        return $this;
    }

    /**
     * @return CancelElement|null
     */
    public function getCancelElement()
    {
        return $this->cancelElement;
    }

    /**
     * @return bool
     */
    public function hasCancelElement()
    {
        return empty($this->cancelElement) === false;
    }

    /**
     * @param CancelElement $element
     *
     * @return FormView
     */
    public function setCancelElement(CancelElement $element)
    {
        $this->cancelElement = $element;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return FormBlock
     * @throws FormException
     */
    public function getBlock($id)
    {
        if (isset($this->blocks[$id]))
        {
            return $this->blocks[$id];
        }

        throw new FormException('Requested FormElement "' . $id . '" does not exist');
    }

    /**
     * @param FormBlock $block
     *
     * @return FormView
     * @throws FormException
     */
    public function addBlock(FormBlock $block)
    {
        if (isset($this->blocks[$block->getId()]))
        {
            throw new FormException('FormBlock "' . $block->getId() . '" has already been set');
        }

        $this->blocks[$block->getId()] = $block;

        return $this;
    }

    /**
     * @param FormBlock[] $blocks
     *
     * @return FormView
     */
    public function setBlocks(array $blocks)
    {
        foreach ($blocks as $block)
        {
            $this->addBlock($block);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        if ($this->hasErrors === null)
        {
            foreach ($this->blocks as $block)
            {
                foreach ($block->getElements() as $element)
                {
                    if ($element->getField()->hasErrors())
                    {
                        $this->hasErrors = true;
                    }
                }
            }
        }

        return $this->hasErrors;
    }

    /**
     * @param string $pathTemplate
     * @param array $params
     *
     * @return string
     * @throws PhtmlException
     */
    public function render($pathTemplate, array $params = [])
    {
        $params = array_merge($params, ['formView' => $this]);
        $form = (new Phtml())->render($pathTemplate, $params);

        $html = '<form {attrs}>{ident}{csrf}{form}</form>';

        $placeholders = [
            'attrs' => RenderHelper::attributes(
                '{attrs}',
                [
                    'attrs' => [
                        'action'         => $this->getUrl(),
                        'method'         => $this->getMethod(),
                        'accept-charset' => $this->getAcceptCharset(),
                    ],
                ]
            ),
            'ident' => $this->renderIdElement(),
            'csrf'  => $this->renderCsrfElement(),
            'form'  => $form,
        ];

        return RenderHelper::placeholders($html, $placeholders);
    }

    /**
     * @return string|null
     */
    private function renderIdElement()
    {
        return '<input type="hidden" name="form[' . $this->getId() . ']" value="1">';
    }

    /**
     * @return string|null
     */
    private function renderCsrfElement()
    {
        if ($this->getCsrf())
        {
            return $this->getCsrf()->renderElement();
        }

        return null;
    }
}