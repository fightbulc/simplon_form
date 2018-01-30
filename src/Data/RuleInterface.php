<?php

namespace Simplon\Form\Data;

interface RuleInterface
{
    /**
     * @param FormField $field
     *
     * @throws RuleException
     */
    public function apply(FormField $field);
}