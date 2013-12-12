<?php

    namespace Simplon\Form\Elements;

    class ElementPasswordField extends ElementSingleTextField
    {
        protected $_elementHtml = '<div class=":hasError"><input type="password" class=":class" name=":id" id=":id" value=":value" placeholder=":placeholder"></div>';
    }