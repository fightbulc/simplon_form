<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\FormException;
use Simplon\Form\View\RenderHelper;

/**
 * Class SearchElement
 * @package Simplon\Form\View\Elements
 */
class SearchElement extends Element
{
    /**
     * @var string
     */
    private $placeholder;

    /**
     * @var string
     */
    private $fieldValueId;

    /**
     * @var string
     */
    private $fieldValueLabel;

    /**
     * @var string
     */
    private $sourceUrl;

    /**
     * @var array
     */
    private $sourceLocal;

    /**
     * @var array
     */
    private $sourceLocalSearchFields;

    /**
     * @var string
     */
    private $resultsRoot = 'results';

    /**
     * @var string
     */
    private $itemIdAttr;

    /**
     * @var string
     */
    private $itemTitleAttr = 'title';

    /**
     * @var int
     */
    private $maxResults = 10;

    /**
     * @var int
     */
    private $minChars = 3;

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     *
     * @return SearchElement
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @return string
     */
    public function getItemIdAttr()
    {
        return $this->itemIdAttr;
    }

    /**
     * @param string $itemIdAttr
     *
     * @return SearchElement
     */
    public function setItemIdAttr($itemIdAttr)
    {
        $this->itemIdAttr = $itemIdAttr;

        return $this;
    }

    /**
     * @return string
     */
    public function getItemTitleAttr()
    {
        return $this->itemTitleAttr;
    }

    /**
     * @param string $itemTitleAttr
     *
     * @return SearchElement
     */
    public function setItemTitleAttr($itemTitleAttr)
    {
        $this->itemTitleAttr = $itemTitleAttr;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * @param int $maxResults
     *
     * @return SearchElement
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinChars()
    {
        return $this->minChars;
    }

    /**
     * @param int $minChars
     *
     * @return SearchElement
     */
    public function setMinChars($minChars)
    {
        $this->minChars = $minChars;

        return $this;
    }

    /**
     * @return string
     */
    public function getResultsRoot()
    {
        return $this->resultsRoot;
    }

    /**
     * @param string $resultsRoot
     *
     * @return SearchElement
     */
    public function setResultsRoot($resultsRoot)
    {
        $this->resultsRoot = $resultsRoot;

        return $this;
    }

    /**
     * @return string
     */
    public function getSourceUrl()
    {
        return $this->sourceUrl;
    }

    /**
     * @param string $sourceUrl
     *
     * @return SearchElement
     */
    public function setSourceUrl($sourceUrl)
    {
        $this->sourceUrl = $sourceUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getSourceLocal()
    {
        return $this->sourceLocal;
    }

    /**
     * @param array $data
     *
     * @return SearchElement
     */
    public function setSourceLocal(array $data)
    {
        $this->sourceLocal = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getSourceLocalSearchFields()
    {
        return $this->sourceLocalSearchFields;
    }

    /**
     * @param array $sourceLocalSearchFields
     *
     * @return SearchElement
     */
    public function setSourceLocalSearchFields(array $sourceLocalSearchFields)
    {
        $this->sourceLocalSearchFields = $sourceLocalSearchFields;

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
        return '<div {attrs-wrapper}><div {attrs-field-wrapper}><input {attrs-field}><input {attrs-search-field}><i class="search icon"></i></div></div>';
    }

    /**
     * @return string
     */
    public function renderWidget()
    {
        $attrs = [
            'attrs-wrapper'       => [
                'class' => ['ui search fluid'],
            ],
            'attrs-field-wrapper' => [
                'class' => ['ui', 'right icon', 'input'],
            ],
            'attrs-field'         => $this->getWidgetAttributes(),
            'attrs-search-field'  => [
                'class'       => ['prompt'],
                'value'       => $this->getFieldValueLabel(),
                'placeholder' => $this->getPlaceholder(),
            ],
        ];

        return RenderHelper::attributes($this->getWidgetHtml(), $attrs);
    }

    /**
     * @return string
     * @throws FormException
     */
    public function getCode()
    {
        // source requirements
        if ($this->getSourceUrl() === null && $this->getSourceLocal() === null)
        {
            throw new FormException('Missing search source');
        }

        // local search requirements
        if ($this->getSourceUrl() === null)
        {
            $searchFields = $this->getSourceLocalSearchFields();

            if ($searchFields === null)
            {
                throw new FormException('Missing search fields for your local source data');
            }
        }

        $code[] = '$(\'#' . $this->renderElementId() . '\').parent().find(\'.prompt\').on(\'blur\', function (){ if ($(this).val() === \'\') { $(this).parent().find(\'input[type=hidden]\').val(\'\') } })';

        if ($this->getSourceUrl())
        {
            $code[] = '$(\'#' . $this->renderElementId() . '\').parent().parent().search({ apiSettings: { url: \'{sourceUrl}\' }, fields: { results: \'{resultsRoot}\', title: \'{itemTitleAttr}\' }, maxResults: {maxResults}, minCharacters: {minChars}, onSelect: function (item) { var value = item.{itemTitleAttr}; if(item.{itemIdAttr}) { value = item.{itemIdAttr} + \',\' + item.{itemTitleAttr}; } $(this).find(\'input[type=hidden]\').val(value); } })';
        }
        else
        {
            $code[] = '$(\'#' . $this->renderElementId() . '\').parent().parent().search({ source: JSON.parse(\'{sourceLocal}\'), searchFields: JSON.parse(\'{searchFields}\'), fields: { title: \'{itemTitleAttr}\' }, maxResults: {maxResults}, minCharacters: {minChars}, onSelect: function (item) { var value = item.{itemTitleAttr}; if(item.{itemIdAttr}) { value = item.{itemIdAttr} + \',\' + item.{itemTitleAttr}; } $(this).find(\'input[type=hidden]\').val(value); } })';
        }

        return RenderHelper::placeholders(
            join("\n", $code),
            [
                'sourceUrl'     => $this->getSourceUrl(),
                'sourceLocal'   => json_encode($this->getSourceLocal()),
                'searchFields'  => json_encode($this->getSourceLocalSearchFields()),
                'resultsRoot'   => $this->getResultsRoot(),
                'itemIdAttr'    => $this->getItemIdAttr(),
                'itemTitleAttr' => $this->getItemTitleAttr(),
                'maxResults'    => $this->getMaxResults(),
                'minChars'      => $this->getMinChars(),
            ]
        );
    }

    /**
     * @return null|string
     */
    private function getFieldValueLabel()
    {
        if ($this->fieldValueLabel === null)
        {
            $this->parseFieldValue();
        }

        return $this->fieldValueLabel;
    }

    private function parseFieldValue()
    {
        if ($this->getField()->getValue() && strpos($this->getField()->getValue(), ',') !== false)
        {
            $parts = explode(',', $this->getField()->getValue());
            $this->fieldValueId = array_shift($parts);
            $this->fieldValueLabel = join(',', $parts);
        }
    }
}