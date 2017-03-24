<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\FormError;
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
    private $removeLabel = 'Click to remove';

    /**
     * @var null|string
     */
    private $uploadUrl;

    /**
     * @var array
     */
    private $uploadMetaData = [];

    /**
     * @var int
     */
    private $imageWidth = 1200;

    /**
     * @var int
     */
    private $thumbWidth = 600;

    /**
     * @var string
     */
    private $thumbContainer = '#thumb';

    /**
     * @var string
     */
    private $urlResponseObject = 'response.url';

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
     * @throws FormError
     */
    public function getThumbContainer(): string
    {
        $value = $this->thumbContainer;

        if ($value)
        {
            return $value;
        }

        throw new FormError('Missing thumb container reference');
    }

    /**
     * @param string $thumbContainer
     *
     * @return ImageUploadElement
     */
    public function setThumbContainer(string $thumbContainer): self
    {
        $this->thumbContainer = $thumbContainer;

        return $this;
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
    public function getUploadMetaData(): array
    {
        return $this->uploadMetaData;
    }

    /**
     * @param array $uploadMetaData
     *
     * @return ImageUploadElement
     */
    public function setUploadMetaData(array $uploadMetaData): self
    {
        $this->uploadMetaData = $uploadMetaData;

        return $this;
    }

    /**
     * @return null|string
     * @throws FormError
     */
    public function getUploadUrl(): ?string
    {
        $value = $this->uploadUrl;

        if ($value)
        {
            return $value;
        }

        throw new FormError('Missing upload-url');
    }

    /**
     * @param string $uploadUrl
     *
     * @return ImageUploadElement
     */
    public function setUploadUrl(string $uploadUrl): self
    {
        $this->uploadUrl = $uploadUrl;

        return $this;
    }

    /**
     * @return string
     * @throws FormError
     */
    public function getUrlResponseObject(): string
    {
        $value = $this->urlResponseObject;

        if ($value)
        {
            return $value;
        }

        throw new FormError('Missing url-response-object definition');
    }

    /**
     * @param string $urlResponseObject
     *
     * @return ImageUploadElement
     */
    public function setUrlResponseObject(string $urlResponseObject): self
    {
        $this->urlResponseObject = $urlResponseObject;

        return $this;
    }

    /**
     * @return array
     */
    public function getWidgetAttributes(): array
    {
        $base = [
            'type'  => 'hidden',
            'id'    => $this->renderElementId(),
            'name'  => $this->renderElementName(),
            'value' => $this->getField()->getValue(),
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
        return '<div {attrs-wrapper}><input {attrs-file}><input {attrs-field}><div {attrs-button-wrapper}><i class="icon photo"></i>&nbsp;<span>{label}</span></div></div>';
    }

    /**
     * @return string
     * @throws FormError
     */
    public function renderWidget(): string
    {
        $attrs = [
            'attrs-wrapper'        => [
                'class'                 => ['form-image-upload'],
                'data-upload-url'       => $this->getUploadUrl(),
                'data-upload-meta-data' => urlencode(RenderHelper::jsonEncode($this->getUploadMetaData())),
                'data-image-width'      => $this->getImageWidth(),
                'data-thumb-width'      => $this->getThumbWidth(),
                'data-thumb-container'  => $this->getThumbContainer(),
                'data-attach-label'     => $this->getAttachLabel(),
                'data-replace-label'    => $this->getReplaceLabel(),
                'data-remove-label'     => $this->getRemoveLabel(),
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
            ['label' => $label]
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
        return '$(\'#' . $this->renderElementId() . '\').imageUpload({getUrlResponseObject: function(response) { return ' . $this->getUrlResponseObject() . '; }})';
    }
}