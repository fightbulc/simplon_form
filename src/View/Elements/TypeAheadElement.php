<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\View\Elements\Support\Typeahead\TypeaheadSourceInterface;
use Simplon\Form\View\RenderHelper;

/**
 * Class TypeAheadElement
 * @package Simplon\Form\View\Elements
 */
class TypeAheadElement extends Element
{
    const DELIMITER = ':';

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
     * @var TypeaheadSourceInterface
     */
    private $source;

    /**
     * @var bool
     */
    private $showHint = false;

    /**
     * @var bool
     */
    private $showHighlight = true;

    /**
     * @var int
     */
    private $maxResults = 10;

    /**
     * @var int
     */
    private $minChars = 1;

    /**
     * @var string
     */
    private $notFoundTemplate;

    /**
     * @var string
     */
    private $pendingTemplate;

    /**
     * @var string
     */
    private $headerTemplate;

    /**
     * @var string
     */
    private $footerTemplate;

    /**
     * @var string
     */
    private $suggestionTemplate = '<div>{{{{labelAttr}}}}</div>';

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
     * @return TypeAheadElement
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

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
     * @return TypeAheadElement
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
     * @return TypeAheadElement
     */
    public function setMinChars($minChars)
    {
        $this->minChars = $minChars;

        return $this;
    }

    /**
     * @return TypeaheadSourceInterface
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param TypeaheadSourceInterface $source
     *
     * @return TypeAheadElement
     */
    public function setSource(TypeaheadSourceInterface $source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return boolean
     */
    public function showHint()
    {
        return $this->showHint;
    }

    /**
     * @param boolean $showHint
     *
     * @return TypeAheadElement
     */
    public function setShowHint($showHint)
    {
        $this->showHint = $showHint === true;

        return $this;
    }

    /**
     * @return boolean
     */
    public function showHighlight()
    {
        return $this->showHighlight;
    }

    /**
     * @param boolean $showHighlight
     *
     * @return TypeAheadElement
     */
    public function setShowHighlight($showHighlight)
    {
        $this->showHighlight = $showHighlight === true;

        return $this;
    }

    /**
     * @return string
     */
    public function getFooterTemplate()
    {
        return $this->footerTemplate;
    }

    /**
     * @param string $footerTemplate
     *
     * @return TypeAheadElement
     */
    public function setFooterTemplate($footerTemplate)
    {
        $this->footerTemplate = $footerTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeaderTemplate()
    {
        return $this->headerTemplate;
    }

    /**
     * @param string $headerTemplate
     *
     * @return TypeAheadElement
     */
    public function setHeaderTemplate($headerTemplate)
    {
        $this->headerTemplate = $headerTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getNotFoundTemplate()
    {
        return $this->notFoundTemplate;
    }

    /**
     * @param string $notFoundTemplate
     *
     * @return TypeAheadElement
     */
    public function setNotFoundTemplate($notFoundTemplate)
    {
        $this->notFoundTemplate = $notFoundTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getPendingTemplate()
    {
        return $this->pendingTemplate;
    }

    /**
     * @param string $pendingTemplate
     *
     * @return TypeAheadElement
     */
    public function setPendingTemplate($pendingTemplate)
    {
        $this->pendingTemplate = $pendingTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getSuggestionTemplate()
    {
        return $this->suggestionTemplate;
    }

    /**
     * @param string $suggestionTemplate
     *
     * @return TypeAheadElement
     */
    public function setSuggestionTemplate($suggestionTemplate)
    {
        $this->suggestionTemplate = $suggestionTemplate;

        return $this;
    }

    /**
     * @return array
     */
    public function getWidgetAttributes()
    {
        $fieldValue = $this->getField()->getValue();

        if (is_array($fieldValue))
        {
            $fieldValue = join(self::DELIMITER, $fieldValue);
        }

        $base = [
            'type'  => 'hidden',
            'id'    => $this->renderElementId(),
            'name'  => $this->renderElementName(),
            'value' => $fieldValue,
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
        return '<div {attrs-wrapper}><div {attrs-field-wrapper}><input {attrs-search-field}><i class="search icon"></i><input {attrs-field}></div></div>';
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
            'attrs-search-field'  => [
                'class'       => ['typeahead'],
                'value'       => $this->getFieldValueLabel(),
                'placeholder' => $this->getPlaceholder(),
            ],
            'attrs-field'         => $this->getWidgetAttributes(),
        ];

        return RenderHelper::attributes($this->getWidgetHtml(), $attrs);
    }

    /**
     * @return array
     */
    public function getAssets()
    {
        $assets = [
            'handlebars/4.0.x/handlebars.min.js',
            'typeahead/0.11.x/typeahead.bundle.slice-fix.min.js',
            'typeahead/typeahead.element.min.css',
            'typeahead/typeahead.element.min.js',
        ];

        $sourceAssets = $this->getSource()->getAssets();

        if ($sourceAssets)
        {
            $assets = array_merge($assets, $sourceAssets);
        }

        return $assets;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        $classNames = "{ dataset: 'ui segments', suggestion: 'ui segment' }";
        $selector = "$('#{elementId}').parent().find('.typeahead')";

        $code = RenderHelper::codeLines([
            "var selector = $selector",
            "selector.typeahead({hint: {hint}, highlight: {highlight}, classNames: {classNames}, minLength: {minChars}}, {name: 'typeahead-{elementId}', source: {source}, display: '{labelAttr}', templates: { {templates} } })",
            "selector.bind('typeahead:select', typeaheadElement.select('{labelAttr}', '{idAttr}', '{delimiter}'))",
            "selector.bind('typeahead:asyncrequest', typeaheadElement.active)",
            "selector.bind('typeahead:asyncreceive', typeaheadElement.idle)",
            "selector.bind('typeahead:asynccancel', typeaheadElement.idle)",
            "selector.bind('typeahead:close', typeaheadElement.close)",
        ]);

        $code = RenderHelper::placeholders($code, [
            'templates'  => $this->renderTemplates(),
            'elementId'  => $this->renderElementId(),
            'hint'       => $this->showHint() ? 'true' : 'false',
            'highlight'  => $this->showHighlight() ? 'true' : 'false',
            'classNames' => $classNames,
            'minChars'   => $this->getMinChars(),
            'source'     => $this->getSource()->renderSource(),
            'idAttr'     => $this->getSource()->getIdAttr(),
            'labelAttr'  => $this->getSource()->getLabelAttr(),
            'delimiter'  => self::DELIMITER,
        ]);

        $sourceCode = $this->getSource()->getCode();

        if ($sourceCode)
        {
            $code = RenderHelper::codeLines([$sourceCode, $code]);
        }

        return $code;
    }

    /**
     * @return string
     */
    private function renderTemplates()
    {
        $templates = [];

        if ($this->getNotFoundTemplate())
        {
            $templates[] = 'notFound: Handlebars.compile(\'<div class="ui segment">' . $this->getNotFoundTemplate() . '</div>\')';
        }

        if ($this->getHeaderTemplate())
        {
            $templates[] = 'header: Handlebars.compile(\'<div class="ui segment">' . $this->getHeaderTemplate() . '</div>\')';
        }

        if ($this->getFooterTemplate())
        {
            $templates[] = 'footer: Handlebars.compile(\'<div class="ui segment">' . $this->getFooterTemplate() . '</div>\')';
        }

        $templates[] = 'suggestion: Handlebars.compile(\'' . $this->getSuggestionTemplate() . '\')';

        return join(', ', $templates);
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

    /**
     * @return void
     */
    private function parseFieldValue()
    {
        if ($this->getField()->getValue() && strpos($this->getField()->getValue(), self::DELIMITER) !== false)
        {
            $parts = explode(self::DELIMITER, $this->getField()->getValue());
            $this->fieldValueId = array_shift($parts);
            $this->fieldValueLabel = join(self::DELIMITER, $parts);
        }
    }
}