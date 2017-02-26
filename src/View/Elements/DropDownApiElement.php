<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\Data\FormField;
use Simplon\Form\FormError;
use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiInterface;
use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiResults;
use Simplon\Form\View\RenderHelper;

/**
 * @package Simplon\Form\View\Elements
 */
class DropDownApiElement extends DropDownElement
{
    /**
     * @var DropDownApiInterface
     */
    private $interface;

    /**
     * @param FormField $field
     * @param DropDownApiInterface $interface
     */
    public function __construct(FormField $field, DropDownApiInterface $interface)
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

        $translateResponse = '$.each(result.' . $this->getInterface()->getOnResponse()->getResultItemsKey() . ', function(index, item) { var label = \'' . $this->getInterface()->getOnResponse()->getLabel() . '; var remoteID = ' . $this->getInterface()->getOnResponse()->getRemoteId() . '; response.results.push({ "name": label, "value": encodeURIComponent(JSON.stringify({label: label, remote_id: remoteID})) }); });';

        $functions = [
            'beforeXHR: function(xhr) { ' . $this->getInterface()->getBeforeXHR() . ' return xhr; }',
            'beforeSend: function(settings) { if(settings.urlData.query !== \'\') { ' . $this->getInterface()->getBeforeSend() . ' return settings; } return false; }',
            'onResponse: function(result) { var response = { results:[] }; ' . $translateResponse . ' return response; }',
        ];

        $lines = [];
        $lines[] = $selector . '.dropdown({' . trim(json_encode($options), '{}') . ', "apiSettings": { ' . trim(json_encode($apiSettings), '{}') . ', ' . implode(', ', $functions) . '}})';

        return implode(";\n", $lines);
    }

    /**
     * @return null|string
     */
    protected function renderResults(): ?string
    {
        $options = [];

        if (!empty($this->getField()->getValue()))
        {
            $results = new DropDownApiResults($this->getField()->getValue());

            foreach ($results as $item)
            {
                if ($this->isMultiple() === false)
                {
                    $this->setPlaceholder('');

                    return $this->renderTextWidget($item->getLabel(), false);
                }

                $options[] = '<a class="ui label transition visible" data-value="' . $item->getRaw() . '" style="display: inline-block !important;">' . $item->getLabel() . '<i class="delete icon"></i></a>';
            }
        }

        return implode("\n", $options);
    }

    /**
     * @return DropDownApiInterface
     */
    protected function getInterface(): DropDownApiInterface
    {
        return $this->interface;
    }
}