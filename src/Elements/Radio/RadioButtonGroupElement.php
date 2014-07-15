<?php

namespace Simplon\Form\Elements\Radio;

class RadioButtonGroupElement extends RadioElement
{
    protected $elementHtml = '<div id=":id" class="btn-group btn-group-justified" data-toggle="buttons">:items</div>';
    protected $elementItemHtml = '<label class="btn btn-lg btn-primary:active"><input type="radio" name=":id" id=":id_:value" value=":value" value=":value":checked>:label</label>';
}