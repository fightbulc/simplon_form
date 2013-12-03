<?php

    namespace Simplon\Form\Elements;

    class ElementPasswordField extends ElementSingleTextField
    {
        protected $_elementHtml = '<div class=":hasError"><input type="password" class="form-control" name=":id" id=":id" value=":value" placeholder=":placeholder"></div>';
    }