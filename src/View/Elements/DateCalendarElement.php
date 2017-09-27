<?php

namespace Simplon\Form\View\Elements;

use Moment\Moment;
use Moment\MomentException;
use Simplon\Form\Data\FormField;
use Simplon\Form\View\Element;
use Simplon\Form\View\FormView;
use Simplon\Form\View\RenderHelper;

/**
 * @package Simplon\Form\View\Elements
 */
class DateCalendarElement extends Element
{
    const TYPE_MONTH = 'month';
    const TYPE_YEAR = 'year';
    const TYPE_DATE = 'date';
    const TYPE_TIME = 'time';

    const ALLOWED_TYPES = [
        self::TYPE_MONTH,
        self::TYPE_YEAR,
        self::TYPE_DATE,
        self::TYPE_TIME,
    ];

    /**
     * @var string
     */
    private $placeholder = 'Select';
    /**
     * @var string
     */
    private $type;
    /**
     * @var null|Moment
     */
    private $minDate;
    /**
     * @var null|Moment
     */
    private $maxDate;
    /**
     * @var null|DateCalendarElement
     */
    private $rangeStartElement;
    /**
     * @var null|DateCalendarElement
     */
    private $rangeEndElement;
    /**
     * @var string
     */
    private $dateFormat = 'DD.MM.YYYY';
    /**
     * @var string
     */
    private $timeFormat = 'HH:mm';
    /**
     * @var string
     */
    private $dateTimeFormat = 'DD.MM.YYYY HH:mm';

    /**
     * @param FormField $field
     * @param null|DateCalendarElement $rangeStartElement
     */
    public function __construct(FormField $field, ?DateCalendarElement $rangeStartElement = null)
    {
        parent::__construct($field);

        if ($rangeStartElement)
        {
            $this->rangeStartElement = $rangeStartElement->setRangeEndElement($this);
        }
    }

    /**
     * @return null|DateCalendarElement
     */
    public function getRangeStartElement(): ?DateCalendarElement
    {
        return $this->rangeStartElement;
    }

    /**
     * @return null|DateCalendarElement
     */
    public function getRangeEndElement()
    {
        return $this->rangeEndElement;
    }

    /**
     * @param DateCalendarElement $rangeEndElement
     *
     * @return DateCalendarElement
     */
    public function setRangeEndElement(DateCalendarElement $rangeEndElement)
    {
        $this->rangeEndElement = $rangeEndElement;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return DateCalendarElement
     */
    public function monthOnly(): self
    {
        $this->type = self::TYPE_MONTH;

        return $this;
    }

    /**
     * @return DateCalendarElement
     */
    public function yearOnly(): self
    {
        $this->type = self::TYPE_YEAR;

        return $this;
    }

    /**
     * @return DateCalendarElement
     */
    public function dateOnly(): self
    {
        $this->type = self::TYPE_DATE;

        return $this;
    }

    /**
     * @return DateCalendarElement
     */
    public function timeOnly(): self
    {
        $this->type = self::TYPE_TIME;

        return $this;
    }

    /**
     * @return Moment|null
     */
    public function getMinDate(): ?Moment
    {
        return $this->minDate;
    }

    /**
     * @param Moment|null $minDate
     *
     * @return DateCalendarElement
     */
    public function setMinDate(Moment $minDate): self
    {
        $this->minDate = $minDate;

        return $this;
    }

    /**
     * @return Moment|null
     */
    public function getMaxDate(): ?Moment
    {
        return $this->maxDate;
    }

    /**
     * @param Moment|null $maxDate
     *
     * @return DateCalendarElement
     */
    public function setMaxDate(Moment $maxDate): self
    {
        $this->maxDate = $maxDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     *
     * @return DateCalendarElement
     */
    public function setPlaceholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @return string
     */
    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }

    /**
     * @param string $dateFormat
     *
     * @return DateCalendarElement
     */
    public function setDateFormat(string $dateFormat): DateCalendarElement
    {
        $this->dateFormat = $dateFormat;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimeFormat(): string
    {
        return $this->timeFormat;
    }

    /**
     * @param string $timeFormat
     *
     * @return DateCalendarElement
     */
    public function setTimeFormat(string $timeFormat): DateCalendarElement
    {
        $this->timeFormat = $timeFormat;

        return $this;
    }

    /**
     * @return string
     */
    public function getDateTimeFormat(): string
    {
        return $this->dateTimeFormat;
    }

    /**
     * @param string $dateTimeFormat
     *
     * @return DateCalendarElement
     */
    public function setDateTimeFormat(string $dateTimeFormat): DateCalendarElement
    {
        $this->dateTimeFormat = $dateTimeFormat;

        return $this;
    }

    /**
     * @return null|string
     */
    public function renderLabel():?string
    {
        if ($this->hasLabel())
        {
            /** @noinspection HtmlUnknownAttribute */
            $html = '<label {attrs}>' . $this->getLabel() . $this->renderDescription('&nbsp;') . '</label>';

            $attrs = [
                'attrs' => [
                    'for'                 => $this->renderElementId(),
                    'data-label-optional' => FormView::getOptionalLabel(),
                    'data-label-required' => FormView::getRequiredLabel(),
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
        return '<div {attrs-wrapper}><div {attrs-field-wrapper}><i {attrs-icon}></i><input {attrs-field}><input {attrs-helper-field}></div></div>';
    }

    /**
     * @return array
     */
    public function getWidgetAttributes(): array
    {
        $base = [
            'type'        => 'text',
            'id'          => $this->renderElementId(),
            'value'       => $this->getField()->getValue(),
            'placeholder' => $this->getPlaceholder(),
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
    public function renderWidget(): string
    {
        $attrs = [
            'attrs-wrapper'       => [
                'class' => ['ui calendar'],
            ],
            'attrs-field-wrapper' => [
                'class' => ['ui input left icon'],
            ],
            'attrs-icon'          => [
                'class' => ['icon'],
            ],
            'attrs-field'         => [
                'type'  => 'hidden',
                'name'  => $this->renderElementName(),
                'value' => $this->getField()->getValue(),
            ],
            'attrs-helper-field'  => $this->getWidgetAttributes(),
        ];

        if (!$this->getType() || in_array($this->getType(), [self::TYPE_DATE]))
        {
            $calendarType = 'calendar';
        }
        else
        {
            $calendarType = 'time';
        }

        $attrs['attrs-icon']['class'][] = $calendarType;

        return RenderHelper::attributes($this->getWidgetHtml(), $attrs);
    }

    /**
     * @return array
     */
    public function getAssets(): array
    {
        return [
            'semantic-calendar/0.0.x/calendar.min.css',
            'semantic-calendar/0.0.x/calendar.min.js',
            'momentjs/2.17.x/moment-with-locales.min.js',
        ];
    }

    /**
     * @return string
     * @throws MomentException
     */
    public function getCode(): string
    {
        $options = [
            'firstDayOfWeek' => 1,
            'ampm'           => false,
            'selector'       => [
                'input' => 'input[type=text]',
            ],
        ];

        if ($this->getType())
        {
            $options['type'] = $this->getType();
        }

        $functions = [
            $this->getFormatterFunction(),
            $this->getParserFunction(),
            $this->getOnChangeFunction(),
        ];

        if ($this->getMinDate())
        {
            $functions[] = 'minDate: new Date(' . $this->getMinDate()->format('Y') . ', ' . ((int)$this->getMinDate()->format('m') - 1) . ', ' . $this->getMinDate()->format('d') . ')';
        }

        if ($this->getMaxDate())
        {
            $functions[] = 'maxDate: new Date(' . $this->getMaxDate()->format('Y') . ', ' . ((int)$this->getMaxDate()->format('m') - 1) . ', ' . $this->getMaxDate()->format('d') . ')';
        }

        if ($this->getRangeStartElement())
        {
            $functions[] = 'startCalendar: $(\'#' . $this->getRangeStartElement()->renderElementId() . '\').parent().parent()';
        }

        if ($this->getRangeEndElement())
        {
            $functions[] = 'endCalendar: $(\'#' . $this->getRangeEndElement()->renderElementId() . '\').parent().parent()';
        }

        $code = [
            'moment.locale("' . $this->getLanguage() . '")',
            '$("#' . $this->renderElementId() . '").parent().parent().calendar({' . RenderHelper::jsonEncode($options, true) . ', ' . implode(', ', $functions) . '})',
        ];

        return implode(";\n", $code);
    }

    /**
     * @return string
     */
    private function getFormatterFunction(): string
    {
        $objs = [
            'date: function(date, settings) { if(!date) return ""; return moment(date).format("' . $this->getDateFormat() . '"); }',
            'time: function(date, settings) { if(!date) return ""; return moment(date).format("' . $this->getTimeFormat() . '"); }',
            'dateTime: function(date, settings) { if(!date) return ""; return moment(date).format("' . $this->getDateTimeFormat() . '"); }',
        ];

        return 'formatter: { ' . implode(",\n", $objs) . ' }';
    }

    /**
     * @return string
     */
    private function getParserFunction(): string
    {
        $objs = [
            'date: function(text, settings) { var m = moment(text, "' . $this->getMomentDateFormat() . '"); return m.toDate(); }',
        ];

        return 'parser: { ' . implode(",\n", $objs) . ' }';
    }

    /**
     * @return string
     */
    private function getOnChangeFunction(): string
    {
        $lines = [
            'var m = moment(date)',
            '$(this).find("input[type=hidden]").val(text === "" ? "" : m.format("' . $this->getMomentDateFormat() . '"))', // set saving time
        ];

        return 'onChange: function(date, text) { ' . implode(";\n", $lines) . '; }';
    }

    /**
     * @return string
     */
    private function getMomentDateFormat(): string
    {
        switch ($this->getType())
        {
            case self::TYPE_TIME:
                $format = 'HH:mm';
                break;
            case self::TYPE_DATE:
                $format = 'YYYY-MM-DD';
                break;
            default:
                $format = 'YYYY-MM-DD\THH:mm:ss';
        }

        return $format;
    }
}