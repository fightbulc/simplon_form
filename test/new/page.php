<?php

    require __DIR__ . '/../../vendor/autoload.php';

    // ##########################################

    $validCharacters = '0-9A-Za-z!@#$%^&*+=-_?:;|';

    $ruleRegExp = (new \Simplon\Form\Rules\RuleRegExp())
        ->setRegExpValue('/[' . $validCharacters . ']+/')
        ->setErrorMessage('Only the following characters are allowed: ' . $validCharacters);

    $password = (new \Simplon\Form\Elements\ElementPasswordField())
        ->setId('password')
        ->setLabel('New password')
        ->setPlaceholder('Set your password')
        ->addClass('input-lg')
        ->addRule(new \Simplon\Form\Rules\RuleRequired())
        ->addRule((new \Simplon\Form\Rules\RuleLengthMin())->setLength(6))
        ->addRule((new \Simplon\Form\Rules\RuleLengthMax())->setLength(30))
        ->addRule($ruleRegExp);

    // ------------------------------------------

    $passwordControl = (new \Simplon\Form\Elements\ElementPasswordField())
        ->setId('password_control')
        ->setLabel('Confirm your password')
        ->setPlaceholder('Retype your password')
        ->addClass('input-lg')
        ->addRule(new \Simplon\Form\Rules\RuleRequired())
        ->addRule((new \Simplon\Form\Rules\RuleMatchElementValue())->setMatchElement($password)->setErrorMessage('Your confirmed password does not match'));

    // ------------------------------------------

    $resetToken = (new \Simplon\Form\Elements\ElementHiddenField())
        ->setId('reset_token')
        ->setValue('123');

    // ------------------------------------------

    $submitButton = (new \Simplon\Form\Elements\ElementSubmitButton())
        ->setId('submit')
        ->setLabel('Reset password');

    // ##########################################

    $form = (new \Simplon\Form\Form())
        ->setId('reset')
        ->setUrl('')
        ->setMethod('POST')
        ->setGeneralErrorMessage('<strong>Validation failed.</strong> Have a look at the error notes below.')
        ->setSubmitElement($submitButton)
        ->setTemplate('./template.html')
        ->addElement($password)
        ->addElement($passwordControl)
        ->addElement($resetToken);

    // ##########################################

    if ($form->isValid() === TRUE)
    {
        echo '<h1>Valid form!</h1><a href="page.php">back to the start</a>';
        var_dump($form->getElementValues());
        exit;
    }

    echo $form->render();