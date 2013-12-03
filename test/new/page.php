<?php

    require __DIR__ . '/../../vendor/autoload.php';

    // ##########################################

    $resultTemplate = '
    <div style="float:right">{{country}}</div>
    <div style="float:left;background-image:url({{url_avatar}});background-size:cover;margin:-5px 10px 0 0;height:48px;width:48px">&nbsp;</div>
    <strong>{{name}}</strong>
    <div><small>{{url_soundcloud}}&nbsp;</small></div>
    ';

    $selectedTemplate = '
    <div style="float:right">{{country}}</div>
    <div style="float:left;background-image:url({{url_avatar}});background-size:cover;margin:-5px 10px 0 0;height:48px;width:48px">&nbsp;</div>
    <strong>{{name}}</strong>
    ';

    $city = (new \Simplon\Form\Elements\ElementAutoCompleteField())
        ->setId('city')
        ->setLabel('Search city')
        ->setResultTemplate($resultTemplate)
        ->setSelectedTemplate($selectedTemplate)
        ->addRule(new \Simplon\Form\Rules\RuleRequired());

    // ------------------------------------------

    $name = (new \Simplon\Form\Elements\ElementSingleTextField())
        ->setId('name')
        ->setLabel('Name')
        ->setPlaceholder('Your first name')
        ->addRule(new \Simplon\Form\Rules\RuleRequired())
        ->addRule((new \Simplon\Form\Rules\RuleExactMatch())->setMatchValue('tino'));

    // ------------------------------------------

    $lastname = (new \Simplon\Form\Elements\ElementSingleTextField())
        ->setId('lastname')
        ->setLabel('Last name')
        ->setPlaceholder('Your last name')
        ->addRule(new \Simplon\Form\Rules\RuleRequired());

    // ------------------------------------------

    $password = (new \Simplon\Form\Elements\ElementPasswordField())
        ->setId('password')
        ->setLabel('Password')
        ->setPlaceholder('Enter a password')
        ->addRule(new \Simplon\Form\Rules\RuleRequired())
        ->addRule((new \Simplon\Form\Rules\RuleLengthMin())->setLength(6))
        ->addRule((new \Simplon\Form\Rules\RuleLengthMax())->setLength(10));

    // ------------------------------------------

    $passwordControl = (new \Simplon\Form\Elements\ElementPasswordField())
        ->setId('password-control')
        ->setLabel('Password verification')
        ->setPlaceholder('Repeat your password')
        ->addRule(new \Simplon\Form\Rules\RuleRequired())
        ->addRule((new \Simplon\Form\Rules\RuleMatchElementValue())->setMatchElement($password));

    // ------------------------------------------

    $address = (new \Simplon\Form\Elements\ElementMultiTextField())
        ->setId('address')
        ->setLabel('Address')
        ->setDescription('Enter your home address separated by new lines.')
        ->addRule(new \Simplon\Form\Rules\RuleRequired());

    // ------------------------------------------

    $age = (new \Simplon\Form\Elements\ElementDropDownField())
        ->setId('age')
        ->setLabel('Age')
        ->setPlaceholder('Choose...')
        ->setLabelsEqualsKeys(TRUE)
        ->setTopSplitKeys(['DE'])
        ->setSortByLabel(TRUE)
        ->setOptions(['DE', 'EN', 'GR', 'UK', 'AR'])
        ->addRule(new \Simplon\Form\Rules\RuleRequired());

    // ------------------------------------------

    $options = [
        'complete' => 'Complete',
        'missing'  => 'Missing',
        'review'   => 'Review',
    ];

    $agreed = (new \Simplon\Form\Elements\ElementRadioField())
        ->setId('agreed')
        ->setLabel('Status')
        ->setOptions($options)
        ->addRule(new \Simplon\Form\Rules\RuleRequired());

    // ------------------------------------------

    $submitButton = (new \Simplon\Form\Elements\ElementSubmitButton())
        ->setId('submit')
        ->setLabel('Save');

    $cancelAnchor = (new \Simplon\Form\Elements\ElementAnchor())
        ->setId('cancel')
        ->setLabel('Cancel')
        ->setUrl('page.php');

    // ##########################################

    $form = (new \Simplon\Form\Form())
        ->setId('test')
        ->setUrl('')
        ->setMethod('POST')
        ->setSubmitElement($submitButton)
        ->setCancelElement($cancelAnchor)
        ->setTemplate('./template.html')
        ->addElement($city)
        ->addElement($name)
        ->addElement($lastname)
        ->addElement($password)
        ->addElement($passwordControl)
        ->addElement($address)
        ->addElement($age)
        ->addElement($agreed);

    // ##########################################

    if ($form->isValid() === TRUE)
    {
        echo '<h1>Valid form!</h1><a href="page.php">back to the start</a>';
        exit;
    }

    echo $form->render();