<?php

    namespace Simplon\Form\Rules\Core;

    use Simplon\Form\Elements\Core\ElementInterface;

    interface RuleInterface
    {
        public function isValid(ElementInterface $elementInterface);

        public function setErrorMessage($errorMessage);

        public function getErrorMessage();

        public function renderErrorMessage(ElementInterface $elementInterface);
    }