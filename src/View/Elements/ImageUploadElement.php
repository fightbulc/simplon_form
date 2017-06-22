<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\FormError;
use Simplon\Form\View\Element;
use Simplon\Form\View\RenderHelper;

/**
 * @package Simplon\Form\View\Elements
 */
class ImageUploadElement extends Element
{
    /**
     * @var string
     */
    private $attachLabel = 'Attach a photo';
    /**
     * @var string
     */
    private $replaceLabel = 'Replace photo';
    /**
     * @var string
     */
    private $removeLabel = 'Remove';
    /**
     * @var string
     */
    private $downloadLabel = 'Download';
    /**
     * @var bool
     */
    private $showNoThumbContainer = false;
    /**
     * @var int
     */
    private $imageWidth = 1200;
    /**
     * @var int
     */
    private $thumbWidth = 800;
    /**
     * @var float
     */
    private $quality = 1.0;
    /**
     * @var bool
     */
    private $renderThumbContainer = true;

    /**
     * @return ImageUploadElement
     */
    public function disableRenderThumbContainer(): ImageUploadElement
    {
        $this->renderThumbContainer = false;

        return $this;
    }

    /**
     * @return float
     */
    public function getQuality(): float
    {
        return $this->quality;
    }

    /**
     * @param float $quality
     *
     * @return ImageUploadElement
     */
    public function setQuality(float $quality): ImageUploadElement
    {
        $this->quality = $quality;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttachLabel(): string
    {
        return $this->attachLabel;
    }

    /**
     * @param string $attachLabel
     *
     * @return ImageUploadElement
     */
    public function setAttachLabel(string $attachLabel): self
    {
        $this->attachLabel = $attachLabel;

        return $this;
    }

    /**
     * @return string
     */
    public function getReplaceLabel(): string
    {
        return $this->replaceLabel;
    }

    /**
     * @param string $replaceLabel
     *
     * @return ImageUploadElement
     */
    public function setReplaceLabel(string $replaceLabel): self
    {
        $this->replaceLabel = $replaceLabel;

        return $this;
    }

    /**
     * @return string
     */
    public function getRemoveLabel(): string
    {
        return $this->removeLabel;
    }

    /**
     * @param string $removeLabel
     *
     * @return ImageUploadElement
     */
    public function setRemoveLabel(string $removeLabel): self
    {
        $this->removeLabel = $removeLabel;

        return $this;
    }

    /**
     * @return string
     */
    public function getDownloadLabel(): string
    {
        return $this->downloadLabel;
    }

    /**
     * @param string $downloadLabel
     *
     * @return ImageUploadElement
     */
    public function setDownloadLabel(string $downloadLabel): self
    {
        $this->downloadLabel = $downloadLabel;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowNoThumbContainer(): bool
    {
        return $this->showNoThumbContainer;
    }

    /**
     * @return ImageUploadElement
     */
    public function enableShowNoThumbContainer(): self
    {
        $this->showNoThumbContainer = true;

        return $this;
    }

    /**
     * @return int
     * @throws FormError
     */
    public function getImageWidth(): int
    {
        $value = $this->imageWidth;

        if ($value)
        {
            return $value;
        }

        throw new FormError('Missing image width');
    }

    /**
     * @param int $imageWidth
     *
     * @return ImageUploadElement
     */
    public function setImageWidth(int $imageWidth): self
    {
        $this->imageWidth = $imageWidth;

        return $this;
    }

    /**
     * @return string
     */
    public function getThumbContainerId(): string
    {
        return $this->getField()->getId() . '-thumb';
    }

    /**
     * @return int
     * @throws FormError
     */
    public function getThumbWidth(): int
    {
        $value = $this->thumbWidth;

        if ($value)
        {
            return $value;
        }

        throw new FormError('Missing thumb width');
    }

    /**
     * @param int $thumbWidth
     *
     * @return ImageUploadElement
     */
    public function setThumbWidth(int $thumbWidth): self
    {
        $this->thumbWidth = $thumbWidth;

        return $this;
    }

    /**
     * @return array
     */
    public function getWidgetAttributes(): array
    {
        $base = [
            'id'    => $this->renderElementId(),
            'name'  => $this->renderElementName(),
            'style' => 'display:none',
        ];

        if (empty($this->attrs) === false)
        {
            foreach ($this->attrs as $name => $value)
            {
                if (isset($base[$name]))
                {
                    if (is_array($base[$name]))
                    {
                        $base[$name][] = $value;
                    }
                    else
                    {
                        $base[$name] = $base[$name] . ' ' . $value;
                    }
                }
                else
                {
                    $base[$name] = $value;
                }
            }
        }

        return $base;
    }

    /**
     * @return null|string
     */
    public function renderLabel(): ?string
    {
        if ($this->hasLabel())
        {
            /** @noinspection HtmlUnknownAttribute */
            $html = '<label {attrs}>' . $this->getLabel() . $this->renderDescription('&nbsp;') . '</label>';

            $attrs = [
                'attrs' => [
                    'for' => $this->renderElementId(),
                ],
            ];

            return RenderHelper::attributes($html, $attrs);
        }

        return null;
    }

    /**
     * @return string
     */
    public function getWidgetHtml(): string
    {
        /** @noinspection HtmlUnknownAttribute */
        $html = '<div {attrs-wrapper}><input {attrs-file}><textarea {attrs-field}>{image-source}</textarea><div {attrs-button-wrapper}><i class="icon photo"></i>&nbsp;<span>{label}</span></div></div>';

        if ($this->renderThumbContainer === true)
        {
            $html .= '<div id="' . $this->getThumbContainerId() . '" class="auto-image-thumb-container"></div>';
        }

        return $html;
    }

    /**
     * @return string
     * @throws FormError
     */
    public function renderWidget(): string
    {
        $attrs = [
            'attrs-wrapper'        => [
                'class'                   => ['form-image-upload'],
                'data-image-quality'      => $this->getQuality(),
                'data-image-width'        => $this->getImageWidth(),
                'data-thumb-width'        => $this->getThumbWidth(),
                'data-thumb-container'    => $this->getThumbContainerId(),
                'data-attach-label'       => $this->getAttachLabel(),
                'data-replace-label'      => $this->getReplaceLabel(),
                'data-remove-label'       => $this->getRemoveLabel(),
                'data-download-label'     => $this->getDownloadLabel(),
                'data-no-thumb-container' => $this->isShowNoThumbContainer(),
            ],
            'attrs-button-wrapper' => [
                'class'    => ['ui vertical large fluid button'],
                'tabindex' => 0,
            ],
            'attrs-file'           => [
                'type'     => 'file',
                'accept'   => 'image/jpeg,image/png',
                'multiple' => '',
                'style'    => 'display:none',
            ],
            'attrs-field'          => $this->getWidgetAttributes(),
        ];

        $attrs['attrs-button-wrapper']['class'][] = $this->getField()->hasErrors() ? 'red' : 'basic';

        $label = empty($this->getField()->getValue()) ? $this->getAttachLabel() : $this->getReplaceLabel();

        return RenderHelper::placeholders(
            RenderHelper::attributes($this->getWidgetHtml(), $attrs),
            [
                'label'        => $label,
                'image-source' => $this->getField()->getValue(),
            ]
        );
    }

    /**
     * @return array
     */
    public function getAssets(): array
    {
        return [
            'image-upload/bundle.min.css',
            'image-upload/bundle.min.js',
        ];
    }

    /**
     * @return string
     * @throws FormError
     */
    public function getCode(): string
    {
        return '$(\'#' . $this->renderElementId() . '\').imageUpload()';
    }
}