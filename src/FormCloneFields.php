<?php

namespace Simplon\Form;

/**
 * @package Simplon\Form
 */
class FormCloneFields
{
    /**
     * @var FormFields
     */
    private $fields;
    /**
     * @var array
     */
    private $requestData;

    /**
     * @param FormFields $fields
     * @param array $requestData
     */
    public function __construct(FormFields $fields, array $requestData = [])
    {
        $this->fields = $fields;
        $this->requestData = $requestData;
    }
}