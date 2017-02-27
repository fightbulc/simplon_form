<?php

namespace Simplon\Form\View\Elements;

use Moment\Moment;
use Simplon\Form\Data\FormField;
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
     * @var null|string
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
    private $format = 'DD.MM.YYYY';

    /**
     * @param FormField $field
     * @param null|DateCalendarElement $rangeStartElement
     */
    public function __construct(FormField $field, ?DateCalendarElement $rangeStartElement = null)
    {
        parent::__construct($field);

        if($rangeStartElement)
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
    public function isDateTime(): self
    {
        $this->type = null;

        return $this;
    }

    /**
     * @return DateCalendarElement
     */
    public function isMonthOnly(): self
    {
        $this->type = self::TYPE_MONTH;

        return $this;
    }

    /**
     * @return DateCalendarElement
     */
    public function isYearOnly(): self
    {
        $this->type = self::TYPE_YEAR;

        return $this;
    }

    /**
     * @return DateCalendarElement
     */
    public function isDateOnly(): self
    {
        $this->type = self::TYPE_DATE;

        return $this;
    }

    /**
     * @return DateCalendarElement
     */
    public function isTimeOnly(): self
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
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return DateCalendarElement
     */
    public function setFormat(string $format): DateCalendarElement
    {
        $this->format = $format;

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
        return '<div {attrs-wrapper}><div {attrs-field-wrapper}><i {attrs-icon}></i><input {attrs-field}></div></div>';
    }

    /**
     * @return array
     */
    public function getWidgetAttributes(): array
    {
        $base = [
            'type'        => 'text',
            'id'          => $this->renderElementId(),
            'name'        => $this->renderElementName(),
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
            'attrs-field'         => $this->getWidgetAttributes(),
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
     */
    public function getCode(): string
    {
        $options = [
            'firstDayOfWeek' => 1,
        ];

        $functions = [
            'formatter: { date: function(date, settings) { if(!date) return ""; return moment(date).format("' . $this->getFormat() . '"); }}',
        ];

        if ($this->getType())
        {
            $options['type'] = $this->getType();
        }

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
            '$("#' . $this->renderElementId() . '").parent().parent().calendar({' . trim(json_encode($options), '{}') . ', ' . implode(', ', $functions) . '})',
            'moment.locale("en")',
        ];

        return implode(";\n", $code);
    }
}