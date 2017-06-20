<?php

namespace Simplon\Form\View;

use Simplon\Form\FormError;
use Simplon\Form\Security\Csrf;
use Simplon\Form\View\Elements\SubmitElement;
use Simplon\Phtml\Phtml;
use Simplon\Phtml\PhtmlException;

/**
 * @package Simplon\Form\View
 */
class FormView
{
    /**
     * @var null|string
     */
    private $scope;
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
     * @var ElementInterface[]
     */
    private $elements;
    /**
     * @var FormViewBlock[]
     */
    private $blocks;
    /**
     * @var null|Csrf
     */
    private $csrf;
    /**
     * @var bool
     */
    private $autoRenderErrorMessage = false;
    /**
     * @var string
     */
    private $errorTitle = 'Looks like we are missing some information.';
    /**
     * @var string
     */
    private $errorMessage = 'Please have a look at the error messages below.';
    /**
     * @var bool
     */
    private $hasErrors;
    /**
     * @var string
     */
    private $componentDir = '/assets/vendor';
    /**
     * @var array
     */
    private $pageWideAssets = [
        '/semantic-ui/2.2.x/semantic.min.css',
        '/simplon-form/base.min.css',
        '/jquery/3.2.x/jquery.min.js',
        '/semantic-ui/2.2.x/semantic.min.js',
        '/simplon-form/base.min.js',
    ];

    /**
     * @param null|string $scope
     */
    public function __construct(?string $scope = null)
    {
        $this->scope = $scope;
    }

    /**
     * @return null|string
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return FormView
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return FormView
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getAcceptCharset(): string
    {
        return $this->acceptCharset;
    }

    /**
     * @return SubmitElement
     */
    public function getSubmitElement(): SubmitElement
    {
        if (!$this->submitElement)
        {
            $this->submitElement = new SubmitElement('Submit');
        }

        return $this->submitElement;
    }

    /**
     * @param SubmitElement $element
     *
     * @return FormView
     */
    public function setSubmitElement(SubmitElement $element): self
    {
        $this->submitElement = $element;

        return $this;
    }

    /**
     * @return bool
     */
    public function shouldAutoRenderErrorMessage(): bool
    {
        return $this->autoRenderErrorMessage;
    }

    /**
     * @param bool $autoRenderErrorMessage
     *
     * @return FormView
     */
    public function setAutoRenderErrorMessage(bool $autoRenderErrorMessage): self
    {
        $this->autoRenderErrorMessage = $autoRenderErrorMessage === true;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorTitle(): string
    {
        return $this->errorTitle;
    }

    /**
     * @param string $errorTitle
     *
     * @return FormView
     */
    public function setErrorTitle(string $errorTitle): self
    {
        $this->errorTitle = $errorTitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     *
     * @return FormView
     */
    public function setErrorMessage(string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * @return null|Csrf
     */
    public function getCsrf(): ?Csrf
    {
        return $this->csrf;
    }

    /**
     * @param Csrf $csrf
     *
     * @return FormView
     */
    public function setCsrf(Csrf $csrf): self
    {
        $this->csrf = $csrf;

        return $this;
    }

    /**
     * @return string
     */
    public function getComponentDir(): string
    {
        return rtrim($this->componentDir, '/');
    }

    /**
     * @param string $componentDir
     *
     * @return FormView
     */
    public function setComponentDir(string $componentDir): self
    {
        $this->componentDir = $componentDir;

        return $this;
    }

    /**
     * @return ElementInterface[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @param string $id
     *
     * @return null|ElementInterface
     */
    public function getElement(string $id): ?ElementInterface
    {
        foreach ($this->elements as $element)
        {
            if ($id === $element->getField()->getId())
            {
                return $element;
            }
        }

        return null;
    }

    /**
     * @param ElementInterface $element
     *
     * @return FormView
     */
    public function addElement(ElementInterface $element): self
    {
        $this->elements[] = $element;

        return $this;
    }

    /**
     * @param ElementInterface[] $elements
     *
     * @return FormView
     */
    public function setElements(array $elements): self
    {
        $this->elements = $elements;

        return $this;
    }

    /**
     * @return FormViewBlock[]
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }

    /**
     * @param string $id
     *
     * @return FormViewBlock
     * @throws FormError
     */
    public function getBlock(string $id): FormViewBlock
    {
        if (isset($this->blocks[$id]))
        {
            return $this->blocks[$id];
        }

        throw new FormError('Requested Block ID "' . $id . '" does not exist');
    }

    /**
     * @param string $id
     *
     * @return FormViewBlock[]
     * @throws FormError
     */
    public function getCloneBlocks(string $id): array
    {
        $match = [];

        foreach ($this->getBlocks() as $blockId => $block)
        {
            if (preg_match('/' . $id . '/i', $blockId))
            {
                $match[] = $block;
            }
        }

        if (!empty($match))
        {
            return $match;
        }

        throw new FormError('Did not find any blocks via pattern "' . $id . '"');
    }

    /**
     * @param FormViewBlock $block
     *
     * @return FormView
     * @throws FormError
     */
    public function addBlock(FormViewBlock $block): self
    {
        if (isset($this->blocks[$block->getId()]))
        {
            throw new FormError('FormViewBlock "' . $block->getId() . '" has already been set');
        }

        $this->blocks[$block->getId()] = $block;

        // add elements

        foreach ($block->getRows() as $row)
        {
            foreach ($row->getElements() as $element)
            {
                $this->addElement($element);
            }
        }

        return $this;
    }

    /**
     * @param FormViewBlock[] $blocks
     *
     * @return FormView
     * @throws FormError
     */
    public function addBlocks(array $blocks): self
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
    public function hasErrors(): bool
    {
        if ($this->hasErrors === null)
        {
            foreach ($this->getElements() as $element)
            {
                if ($element->getField()->hasErrors())
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param string $pathTemplate
     * @param array $params
     *
     * @return string
     * @throws PhtmlException
     */
    public function render(string $pathTemplate, array $params = []): string
    {
        $params = array_merge($params, ['formView' => $this]);
        $form = (new Phtml())->render($pathTemplate, $params);

        /** @noinspection HtmlUnknownAttribute */
        $html = '<form {attrs}>{error}{scope}{csrf}{form}</form>';

        $class = ['ui', 'form', 'large'];

        if ($this->hasErrors())
        {
            $class[] = 'warning';
        }

        $formAttrs = [
            'action'         => $this->getUrl(),
            'method'         => $this->getMethod(),
            'accept-charset' => $this->getAcceptCharset(),
            'class'          => $class,
        ];

        if ($scope = $this->getScope())
        {
            $formAttrs['id'] = 'form-' . $this->getScope();
        }

        $placeholders = [
            'attrs' => RenderHelper::attributes(
                '{attrs}',
                [
                    'attrs' => $formAttrs,
                ]
            ),
            'error' => $this->shouldAutoRenderErrorMessage() ? $this->renderErrorMessage() : null,
            'scope' => $this->renderScopeElement(),
            'csrf'  => $this->renderCsrfElement(),
            'form'  => $form,
        ];

        return RenderHelper::placeholders($html, $placeholders);
    }

    /**
     * @return array
     */
    public function getCssAssets(): array
    {
        return $this->buildFieldAssetPaths('.css');
    }

    /**
     * @return array
     */
    public function getJsAssets(): array
    {
        return $this->buildFieldAssetPaths('.js');
    }

    /**
     * @return string
     */
    public function getCodeAssets(): string
    {
        return $this->buildFieldCode();
    }

    /**
     * @param bool $preventCaching
     * @param array|null $excludePageWide
     *
     * @return string
     */
    public function renderFieldAssets(bool $preventCaching = false, ?array $excludePageWide = null): string
    {
        // check if we need to add more assets
        $this->validatePageWideAssets($excludePageWide);

        $assets = implode("\n\n", [
            $this->buildPageWideAssetsCss(),
            $this->buildPageWideAssetsJs(),
            $this->buildFieldAssetsCss(),
            $this->buildFieldAssetsJs(),
            $this->buildFieldCode(),
        ]);

        if ($preventCaching)
        {
            $assets = preg_replace('/(\.css|\.js)/i', '\\1?v=' . time(), $assets);
        }

        return $assets;
    }

    /**
     * @return null|string
     */
    public function renderErrorMessage(): ?string
    {
        if ($this->hasErrors())
        {
            return RenderHelper::placeholders(
                '<div class="ui warning message">{title}{message}</div>',
                [
                    'title'   => $this->getErrorTitle() ? '<div class="header">' . $this->getErrorTitle() . '</div>' : null,
                    'message' => $this->getErrorMessage() ? '<p>' . $this->getErrorMessage() . '</p>' : null,
                ]
            );
        }

        return null;
    }

    /**
     * @return string
     */
    private function renderScopeElement(): string
    {
        return '<input type="hidden" name="form[' . $this->getScope() . ']" value="1">';
    }

    /**
     * @return null|string
     */
    private function renderCsrfElement(): ?string
    {
        if ($this->getCsrf())
        {
            return $this->getCsrf()->renderElement();
        }

        return null;
    }

    /**
     * @return bool
     */
    private function hasCloneableBlocks(): bool
    {
        foreach ($this->blocks as $block)
        {
            if ($block->isCloneable())
            {
                return true;
            }
        }
    }

    /**
     * @param string $fileType
     *
     * @return array
     */
    private function buildFieldAssetPaths(string $fileType): array
    {
        $assets = [];

        foreach ($this->getElements() as $element)
        {
            if ($element->getAssets())
            {
                foreach ($element->getAssets() as $file)
                {
                    if (strpos($file, $fileType) !== false)
                    {
                        $assets[] = $this->getComponentDir() . '/' . $file;
                    }
                }
            }
        }

        return $assets;
    }

    /**
     * @param array|null $excludePageWide
     */
    private function validatePageWideAssets(?array $excludePageWide = null): void
    {
        if ($this->hasCloneableBlocks())
        {
            $this->pageWideAssets[] = '/uikit/3.0.x/css/uikit.min.css';
            $this->pageWideAssets[] = '/uikit/3.0.x/js/uikit.min.js';
            $this->pageWideAssets[] = '/uikit/3.0.x/js/uikit-icons.min.js';
        }

        if ($excludePageWide)
        {
            $assets = [];

            foreach ($this->pageWideAssets as $file)
            {
                $exclude = false;

                foreach ($excludePageWide as $filter)
                {
                    if (stripos($file, $filter) !== false)
                    {
                        $exclude = true;
                        break;
                    }
                }

                if (!$exclude)
                {
                    $assets[] = $file;
                }
            }

            $this->pageWideAssets = $assets;
        }
    }

    /**
     * @return string
     */
    private function buildPageWideAssetsCss(): string
    {
        /** @noinspection HtmlUnknownTarget */
        return $this->buildPageWideAssets('.css', '<link href="{path}" rel="stylesheet">');
    }

    /**
     * @return string
     */
    private function buildPageWideAssetsJs(): string
    {
        /** @noinspection HtmlUnknownTarget */
        return $this->buildPageWideAssets('.js', '<script src="{path}"></script>');
    }

    /**
     * @param string $fileType
     * @param string $html
     *
     * @return string
     */
    private function buildPageWideAssets(string $fileType, string $html): string
    {
        $assets = [];

        foreach ($this->pageWideAssets as $file)
        {
            if (strpos($file, $fileType) !== false)
            {
                $path = $this->getComponentDir() . '/' . trim($file, '/');

                if (preg_match('/^(http|\/\/)/i', $file))
                {
                    $path = $file;
                }

                $assets[$path] = RenderHelper::placeholders($html, ['path' => $path]);
            }
        }

        return join("\n", $assets);
    }

    /**
     * @return string
     */
    private function buildFieldAssetsCss(): string
    {
        /** @noinspection HtmlUnknownTarget */
        return $this->buildFieldAssets('.css', '<link href="{path}" rel="stylesheet">');
    }

    /**
     * @return string
     */
    private function buildFieldAssetsJs(): string
    {
        /** @noinspection HtmlUnknownTarget */
        return $this->buildFieldAssets('.js', '<script src="{path}"></script>');
    }

    /**
     * @param string $fileType
     * @param string $html
     *
     * @return string
     */
    private function buildFieldAssets(string $fileType, string $html): string
    {
        $assets = [];

        foreach ($this->getElements() as $element)
        {
            if ($element->getAssets())
            {
                foreach ($element->getAssets() as $file)
                {
                    if (strpos($file, $fileType) !== false)
                    {
                        $path = $this->getComponentDir() . '/' . trim($file, '/');

                        if (preg_match('/^(http|\/\/)/i', $file))
                        {
                            $path = $file;
                        }

                        $assets[$path] = RenderHelper::placeholders($html, ['path' => $path]);
                    }
                }
            }
        }

        return join("\n", $assets);
    }

    /**
     * @return string
     */
    private function buildFieldCode(): string
    {
        $code = [];

        foreach ($this->getElements() as $element)
        {
            if ($element->getCode())
            {
                $code[] = "\n<!---- ELEMENT: " . $element->getField()->getId() . " //---->\n";
                $code[] = trim($element->getCode(), ';') . ";\n";
            }
        }

        return '<script type="application/javascript">$(document).ready(function (){' . join("", $code) . '});</script>';
    }
}