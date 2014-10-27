<?php

namespace Simplon\Form\Elements;

/**
 * Interface CoreElementInterface
 * @package Simplon\Form\Elements
 */
interface CoreElementInterface
{
    public function setElementHtml($elementHtml);

    public function getElementHtml();

    public function addAssetFile($file);

    public function getAssetFiles();

    public function addAssetInline($inline);

    public function getAssetInlines();

    public function setId($id);

    public function getId();

    public function setLabel($label);

    public function getLabel();

    public function setDescription($description);

    public function getDescription();

    public function setValue($value);

    public function getValue();

    public function setRules(array $rules);

    public function setPostValue($postValue);

    public function getPostValue();

    public function processFilters();

    public function processRules();

    public function getErrorMessages();

    public function renderErrorMessages();

    public function isValid();

    public function parseFieldPlaceholders($stringWithPlaceholders);

    public function render();
}