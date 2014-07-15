<?php

namespace Simplon\Form\Elements\Checkbox;

class CheckboxButtonGroupElement extends CheckboxElement
{
    protected $elementHtml = '<div id=":id" class="btn-group btn-group-justified" data-toggle="buttons">:items</div>';
    protected $elementItemHtml = '<label class="btn btn-lg btn-primary:active"><input type="checkbox" name=":id[]" value=":value":checked>:label</label>';
}