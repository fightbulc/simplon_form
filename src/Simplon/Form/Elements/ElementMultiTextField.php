<?php

    namespace Simplon\Form\Elements;

    use Simplon\Form\Elements\Core\ElementCore;

    class ElementMultiTextField extends ElementCore
    {
        protected $_elementHtml = '<div class=":hasError"><textarea name=":id" class="form-control" id=":id">:value</textarea></div>';
    }