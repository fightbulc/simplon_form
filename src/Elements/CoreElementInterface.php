<?php

namespace Simplon\Form\Elements;

use Simplon\Form\Filters\Core\FilterInterface;

/**
 * Interface CoreElementInterface
 * @package Simplon\Form\Elements
 * @author Tino Ehrich (tino@bigpun.me)
 */
interface CoreElementInterface
{
    public function setElementHtml($elementHtml);

    /**
     * @return bool
     */
    public function hasElement();

    /**
     * @return string
     */
    public function getElementHtml();

    public function setId($id);

    /**
     * @return string
     */
    public function getId();

    /**
     * @param $name
     *
     * @return static
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    public function setLabel($label);

    /**
     * @return bool
     */
    public function hasLabel();

    /**
     * @return string
     */
    public function getLabel();

    public function setDescription($description);

    /**
     * @return bool
     */
    public function hasDescription();

    /**
     * @return string
     */
    public function getDescription();

    public function setValue($value);

    /**
     * @return mixed
     */
    public function getValue();

    public function setRules(array $rules);

    public function setPostValue($postValue);

    /**
     * @return mixed
     */
    public function getPostValue();

    public function processFilters();

    /**
     * @param FilterInterface $filter
     *
     * @return static
     */
    public function addOutputFilter(FilterInterface $filter);

    /**
     * @return FilterInterface[]
     */
    public function getOutputFilters();

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function processOutputFilters($value);


    public function processRules();

    /**
     * @return bool
     */
    public function hasError();

    /**
     * @return string
     */
    public function getErrorMessages();

    public function renderErrorMessages();

    /**
     * @return void
     */
    public function setup();

    /**
     * @return string
     */
    public function renderLabel();

    /**
     * @return null|string
     */
    public function renderDescription();

    /**
     * @return string
     */
    public function renderElementHtml();

    /**
     * @param $stringWithPlaceholders
     *
     * @return string
     */
    public function parseFieldPlaceholders($stringWithPlaceholders);

    /**
     * @return array
     */
    public function getAssetFiles();

    /**
     * @return array
     */
    public function getAssetInlines();

    /**
     * @param array $requestData
     *
     * @return CoreElement
     */
    public function setPostValueByRequestData(array $requestData);

    /**
     * @return bool
     */
    public function isValid();
}
