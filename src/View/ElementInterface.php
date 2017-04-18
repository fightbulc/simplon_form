<?php

namespace Simplon\Form\View;

use Simplon\Form\Data\FormField;

/**
 * @package Simplon\Form\View
 */
interface ElementInterface
{
    /**
     * @return array|null
     */
    public function getAssets(): ?array;

    /**
     * @return null|string
     */
    public function getCode(): ?string;

    /**
     * @return null|string
     */
    public function getWide(): ?string;

    /**
     * @return string
     */
    public function getWidgetHtml(): string;

    /**
     * @return FormField
     */
    public function getField(): FormField;

    /**
     * @return null|string
     */
    public function getLabel(): ?string;

    /**
     * @return null|string
     */
    public function getDescription(): ?string;

    /**
     * @return null|string
     */
    public function renderLabel(): ?string;

    /**
     * @return string
     */
    public function renderWidget(): string;

    /**
     * @return null|string
     */
    public function renderErrors(): ?string;

    /**
     * @return string
     */
    public function renderElement(): string;
}