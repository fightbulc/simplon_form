<?php

namespace Simplon\Form\View\Elements\Support\Typeahead;

use Simplon\Form\View\RenderHelper;

/**
 * Class TypeaheadAlgoliaSource
 * @package Simplon\Form\View\Elements\Support
 */
class TypeaheadAlgoliaSource implements TypeaheadSourceInterface
{
    use TypeaheadSourceTrait;

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $indexName;

    /**
     * @var string
     */
    private $searchOnlyApiKey;

    /**
     * @var array
     */
    private $options = ['hitsPerPage' => 5];

    /**
     * @param string $appId
     * @param string $searchOnlyApiKey
     * @param string $indexName
     * @param string $idAttr
     * @param string $labelAttr
     */
    public function __construct($appId, $searchOnlyApiKey, $indexName, $idAttr, $labelAttr)
    {
        $this->appId = $appId;
        $this->indexName = $indexName;
        $this->searchOnlyApiKey = $searchOnlyApiKey;
        $this->idAttr = $idAttr;
        $this->labelAttr = $labelAttr;
    }

    /**
     * @param array $options
     *
     * @return TypeaheadAlgoliaSource
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return string
     */
    public function renderSource()
    {
        return RenderHelper::placeholders(
            "index.ttAdapter(JSON.parse('{options}'))",
            ['options' => json_encode($this->options)]
        );
    }

    /**
     * @return array
     */
    public function getAssets()
    {
        return [
            '//cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js',
        ];
    }

    /**
     * @return string
     */
    public function getCode()
    {
        $code = RenderHelper::codeLines([
            "var algolia = algoliasearch('{appId}', '{apiKey}')",
            "var index = algolia.initIndex('{index}')",
        ]);

        return RenderHelper::placeholders($code, [
            'appId'  => $this->appId,
            'apiKey' => $this->searchOnlyApiKey,
            'index'  => $this->indexName,
        ]);
    }
}