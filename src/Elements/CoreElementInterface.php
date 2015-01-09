<?php

namespace Simplon\Form\Elements;

/**
 * Interface CoreElementInterface
 *
 * @package Simplon\Form\Elements
 * @author  Tino Ehrich (tino@bigpun.me)
 */
interface CoreElementInterface
{
    public function setElementHtml($elementHtml);

    public function hasElement();

    public function getElementHtml();

    public function setId($id);

    public function getRawId();

    public function getId();

    public function setLabel($label);

    public function hasLabel();

    public function getLabel();

    public function setDescription($description);

    public function hasDescription();

    public function getDescription();

    public function setValue($value);

    public function getValue();

    public function setRules(array $rules);

    public function setPostValue($postValue);

    public function getPostValue();

    public function processFilters();

    public function processRules();

    public function hasError();

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
     * @return string
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getArrayKey();

    /**
     * @param mixed $arrayKey
     *
     * @return CoreElement
     */
    public function setArrayKey($arrayKey);

    /**
     * @param array $requestData
     *
     * @return CoreElement
     */
    public function setPostValueByRequestData(array $requestData);
}