<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\Data\FormField;
use Simplon\Form\View\Elements\Support\DropDownApi\DropDownApiInterface;

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
            'apiSettings'      => [
                'cache'  => false,
                'method' => $this->getInterface()->getMethod(),
                'url'    => $this->getInterface()->getUrl(),
            ],
        ];

        $translateResponse = '$.each(result[' . $this->getInterface()->getOnResponse()->getResultItemsKey() . '], function(index, item) { var name = \'' . $this->getInterface()->getOnResponse()->renderName() . '\'; var value = \'' . $this->getInterface()->getOnResponse()->renderValue() . '\'; response.results.push({ "name": name, "value": encodeURIComponent(JSON.stringify({text: text, value: value})) }); });';

        $functions = [
            'beforeXHR: function(xhr) { return xhr; }',
            'beforeSend: function(settings) { if(settings.urlData.query !== \'\') { return settings; } return false }',
            'onResponse: function(result) { var response = { results:[] }; ' . $translateResponse . ' return response; }',
        ];

        return $selector . '.dropdown(' . json_encode($options) . ', ' . implode(', ', $functions) . ')';
    }

    /**
     * @return DropDownApiInterface
     */
    protected function getInterface(): DropDownApiInterface
    {
        return $this->interface;
    }
}