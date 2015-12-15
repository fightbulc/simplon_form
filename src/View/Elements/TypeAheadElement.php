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
    const VALUE_DELIMITER = ':';
    const FIELD_DELIMITER = ',';

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
     * @var bool
     */
    private $multiple = false;

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
    private $minChars = 3;

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
     * @return boolean
     */
    public function hasMultiple()
    {
        return $this->multiple;
    }

    /**
     * @param boolean $multiple
     *
     * @return static
     */
    public function isMultiple($multiple)
    {
        $this->multiple = $multiple === true;

        return $this;
    }

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
            $fieldValue = join(self::FIELD_DELIMITER, $fieldValue);
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
        return '<div {attrs-wrapper}>{single-result-container}<div {attrs-field-wrapper}><input {attrs-search-field}><i class="search icon"></i><input {attrs-field}></div>{multi-result-container}</div>';
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
                'placeholder' => $this->getPlaceholder(),
            ],
            'attrs-field'         => $this->getWidgetAttributes(),
        ];

        if ($this->getField()->getValue())
        {
            $class = 'has-single-result';

            if ($this->hasMultiple())
            {
                $class = 'has-multi-result';
            }

            $attrs['attrs-wrapper']['class'][] = $class;
        }

        return RenderHelper::placeholders(
            RenderHelper::attributes($this->getWidgetHtml(), $attrs),
            [
                'single-result-container' => $this->renderSingleResultContainer(),
                'multi-result-container'  => $this->renderMultiResultContainer(),
            ]
        );
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
        $typeaheadSelector = "$('#{elementId}').parent().find('.typeahead')";

        $code = RenderHelper::codeLines([
            "var typeaheadSelector = $typeaheadSelector",
            "typeaheadSelector.typeahead({hint: {hint}, highlight: {highlight}, classNames: {classNames}, minLength: {minChars}}, {name: 'typeahead-{elementId}', source: {source}, display: '{labelAttr}', templates: { {templates} } })",
            "typeaheadSelector.bind('typeahead:select', typeaheadElement.select('{labelAttr}', '{idAttr}', '{delimiter}'))",
            "typeaheadSelector.bind('typeahead:asyncrequest', typeaheadElement.active)",
            "typeaheadSelector.bind('typeahead:asyncreceive', typeaheadElement.idle)",
            "typeaheadSelector.bind('typeahead:asynccancel', typeaheadElement.idle)",
            "typeaheadSelector.bind('typeahead:close', typeaheadElement.close)",
            "$('#{elementId}').parent().parent().on('click', '.single-result-container a', typeaheadElement.removeResult)",
            "$('#{elementId}').parent().parent().on('click', '.multi-result-container a', typeaheadElement.removeResult)",
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
            'delimiter'  => self::VALUE_DELIMITER,
        ]);

        $sourceCode = $this->getSource()->getCode();

        if ($sourceCode)
        {
            $code = RenderHelper::codeLines([$sourceCode, $code]);
        }

        return $code;
    }

    /**
     * @return null|string
     */
    private function renderSingleResultContainer()
    {
        if ($this->hasMultiple())
        {
            return null;
        }

        return RenderHelper::placeholders(
            '<div class="single-result-container">{item}</div>',
            [
                'item' => '<div class="ui fluid selected-item"><a href="#"><i class="delete icon"></i></a>' . $this->getFieldValueLabel() . '</div>',
            ]
        );
    }

    /**
     * @return null|string
     */
    private function renderMultiResultContainer()
    {
        if ($this->hasMultiple())
        {
            $items = [];
            $fieldValues = $this->getMultiFieldValues();

            foreach ($fieldValues as $field)
            {
                $items[$field['id']] = RenderHelper::placeholders(
                    '<div class="ui fluid selected-item"><a href="#" data-value="{raw}"><i class="delete icon"></i></a>{label}</div>',
                    $field
                );
            }

            return RenderHelper::placeholders(
                '<div class="multi-result-container">{items}</div>',
                ['items' => join('', $items)]
            );
        }

        return null;
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
            $result = $this->parseFieldValue(
                $this->getField()->getValue()
            );

            if ($result)
            {
                $this->fieldValueId = $result['id'];
                $this->fieldValueLabel = $result['label'];
            }
        }

        return $this->fieldValueLabel;
    }

    /**
     * @param string $value
     *
     * @return array|null
     */
    private function parseFieldValue($value)
    {
        if ($value && strpos($value, self::VALUE_DELIMITER) !== false)
        {
            $parts = explode(self::VALUE_DELIMITER, $value);

            return [
                'id'    => array_shift($parts),
                'label' => join(self::VALUE_DELIMITER, $parts),
            ];
        }

        return null;
    }

    /**
     * @return array
     */
    private function getMultiFieldValues()
    {
        $values = [];

        if($this->getField()->getValue())
        {
            foreach ($this->getField()->getValue() as $field)
            {
                $result = $this->parseFieldValue($field);

                if ($result)
                {
                    $values[] = [
                        'raw'   => $field,
                        'id'    => $result['id'],
                        'label' => $result['label'],
                    ];
                }
            }
        }

        return $values;
    }
}