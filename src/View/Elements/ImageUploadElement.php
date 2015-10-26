<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\View\RenderHelper;

/**
 * Class ImageUploadElement
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
     * @return string
     */
    public function getAttachLabel()
    {
        return $this->attachLabel;
    }

    /**
     * @param string $attachLabel
     *
     * @return ImageUploadElement
     */
    public function setAttachLabel($attachLabel)
    {
        $this->attachLabel = $attachLabel;

        return $this;
    }

    /**
     * @return string
     */
    public function getReplaceLabel()
    {
        return $this->replaceLabel;
    }

    /**
     * @param string $replaceLabel
     *
     * @return ImageUploadElement
     */
    public function setReplaceLabel($replaceLabel)
    {
        $this->replaceLabel = $replaceLabel;

        return $this;
    }

    /**
     * @return string
     */
    public function getRemoveLabel()
    {
        return $this->removeLabel;
    }

    /**
     * @param string $removeLabel
     *
     * @return ImageUploadElement
     */
    public function setRemoveLabel($removeLabel)
    {
        $this->removeLabel = $removeLabel;

        return $this;
    }

    /**
     * @return array
     */
    public function getWidgetAttributes()
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
     * @return string
     */
    public function renderLabel()
    {
        if ($this->hasLabel())
        {
            /** @noinspection HtmlUnknownAttribute */
            $html = '<label {attrs}>' . $this->getLabel() . '</label>';

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
    public function getWidgetHtml()
    {
        /** @noinspection HtmlUnknownAttribute */
        return '<div {attrs-wrapper}><input {attrs-file}><input {attrs-field}><div {attrs-button-wrapper}><i class="icon photo"></i>&nbsp;<span>{label}</span></div></div>';
    }

    /**
     * @return string
     */
    public function renderWidget()
    {
        $attrs = [
            'attrs-wrapper'        => [
                'class'                => ['form-image-upload'],
                'data-upload-url'      => 'http://open.dev/simplon_form/test/upload-test.php',
                'data-image-width'     => 1200,
                'data-thumb-width'     => 600,
                'data-thumb-container' => '#thumbnail',
                'data-attach-label'    => $this->getAttachLabel(),
                'data-replace-label'   => $this->getReplaceLabel(),
                'data-remove-label'    => $this->getRemoveLabel(),
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
}