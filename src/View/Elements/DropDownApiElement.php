<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\Data\FormField;
use Simplon\Form\FormError;
use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiData;
use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiJsInterface;
use Simplon\Form\View\RenderHelper;

/**
 * @package Simplon\Form\View\Elements
 */
class DropDownApiElement extends DropDownElement
{
    /**
     * @var DropDownApiJsInterface
     */
    private $interface;

    /**
     * @param FormField $field
     * @param DropDownApiJsInterface $interface
     */
    public function __construct(FormField $field, DropDownApiJsInterface $interface)
    {
        parent::__construct($field);
        $this->interface = $interface;
    }

    /**
     * @return bool
     */
    public function isSearchable(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getWidgetHtml(): string
    {
        /** @noinspection HtmlUnknownAttribute */
        return '<div {attrs-wrapper}><input {attrs-field}><i class="dropdown icon"></i>{results}{placeholder}</div>';
    }

    /**
     * @return string
     * @throws FormError
     */
    public function renderWidget(): string
    {
        $attrs = [
            'attrs-wrapper' => [
                'class' => ['ui fluid selection dropdown'],
            ],
            'attrs-field'   => $this->getWidgetAttributes(),
        ];

        if ($this->isMultiple())
        {
            $attrs['attrs-wrapper']['class'][] = 'multiple';
        }

        if ($this->isSearchable())
        {
            $attrs['attrs-wrapper']['class'][] = 'search';
        }

        return RenderHelper::placeholders(
            RenderHelper::attributes($this->getWidgetHtml(), $attrs),
            [
                'results'     => $this->renderResults(),
                'placeholder' => !empty($this->getPlaceholder()) ? $this->renderTextWidget($this->getPlaceholder()) : null,
            ]
        );
    }

    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        $selector = '$(\'#' . $this->renderElementId() . '\').parent()';

        $options = [
            'forceSelection'   => false,
            'filterRemoteData' => false,
            'saveRemoteData'   => false,
            'allowAdditions'   => $this->getAllowAdditions(),
        ];

        $apiSettings = [
            'cache'  => false,
            'method' => $this->getInterface()->getMethod(),
            'url'    => $this->getInterface()->getUrl(),
        ];

        $functions = [
            $this->renderBeforeXHR(),
            $this->renderBeforeSend(),
            $this->renderOnResponse(),
        ];

        return
            $selector
            . '.dropdown({'
            . substr(json_encode($options), 1, -1) . ', '
            . '"apiSettings": { ' . substr(json_encode($apiSettings), 1, -1) . ', ' . implode(",", $functions) . '}'
            . '})';
    }

    /**
     * @return null|string
     */
    protected function renderResults(): ?string
    {
        $options = [];

        if (!empty($this->getField()->getValue()))
        {
            $results = (new DropDownApiData())->fromForm($this->getField()->getValue());

            foreach ($results as $item)
            {
                if ($this->isMultiple() === false)
                {
                    $this->setPlaceholder('');

                    return $this->renderTextWidget($item->getLabel(), false);
                }

                $options[] = '<a class="ui label transition visible" data-value="' . $item->getEncodedJson() . '" style="display: inline-block !important;">' . $item->getLabel() . '<i class="delete icon"></i></a>';
            }
        }

        return implode("\n", $options);
    }

    /**
     * @return DropDownApiJsInterface
     */
    protected function getInterface(): DropDownApiJsInterface
    {
        return $this->interface;
    }

    /**
     * @return string
     */
    private function renderBeforeXHR(): string
    {
        $beforeXHRJsString = null;

        if (!empty($this->getInterface()->renderBeforeXHRJsString()))
        {
            $beforeXHRJsString = rtrim($this->getInterface()->renderBeforeXHRJsString(), ';') . '; ';
        }

        return 'beforeXHR: function(xhr) { ' . $beforeXHRJsString . 'return xhr; }';
    }

    /**
     * @return string
     */
    private function renderBeforeSend(): string
    {
        $beforeSend = null;

        if ($this->getInterface()->renderBeforeSendJsString())
        {
            $beforeSend = rtrim($this->getInterface()->renderBeforeSendJsString(), ';') . ';';
        }

        return 'beforeSend: function(settings) { if(settings.urlData.query !== \'\') { ' . $beforeSend . 'return settings; } return false; }';
    }

    /**
     * @return string
     */
    private function renderOnResponse(): string
    {
        $responseJs = $this->getInterface()->getOnResponse();

        $responseParams = [
            'var label = ' . $responseJs->renderLabelJsString(),
            'var name = ' . $responseJs->renderNameJsString(),
            'var remoteID = ' . $responseJs->renderRemoteIdJsString(),
            'var meta = ' . ($responseJs->renderMetaJsString() ?? 'null'),
            'response.results.push({ "name": label, "value": encodeURIComponent(JSON.stringify({ label: label, name: name, remoteID: remoteID, meta: meta })) })',
        ];

        return
            'onResponse: function(result) { var response = { results:[] }; '
            . "$.each(result." . $responseJs->renderResultObjectJsString() . ", function(index, item) {\n" . implode(";", $responseParams) . "\n});"
            . ' return response; }';
    }
}