<?php

namespace Simplon\Form\Rules\Core;

use Simplon\Form\Elements\InterfaceElement;

interface InterfaceRule
{
    public function isValid(InterfaceElement $elementInterface);

    public function setErrorMessage($errorMessage);

    public function getErrorMessage();

    public function renderErrorMessage(\Simplon\Form\Elements\InterfaceElement $elementInterface);
}