<?php

namespace Simplon\Form\Rules\Core;

use Simplon\Form\Elements\CoreElementInterface;

/**
 * Interface CoreRuleInterface
 * @package Simplon\Form\Rules\Core
 */
interface CoreRuleInterface
{
    public function isValid(CoreElementInterface $elementInterface);

    public function setErrorMessage($errorMessage);

    public function getErrorMessage();

    public function renderErrorMessage(CoreElementInterface $elementInterface);
}