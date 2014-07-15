<?php

namespace Simplon\Form\Elements;

interface InterfaceElement
{
    public function setElementHtml($elementHtml);

    public function getElementHtml();

    public function addJs($code);

    public function getJs();

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

    public function validateRules();

    public function getErrorMessages();

    public function renderErrorMessages();

    public function isValid();

    public function parseFieldPlaceholders($stringWithPlaceholders);

    public function render();
}