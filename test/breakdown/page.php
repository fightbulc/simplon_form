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

$city = (new \Simplon\Form\Elements\AutoComplete\AutoCompleteElement())
    ->setId('city')
    ->setLabel('Search city')
    ->setResultTemplate($resultTemplate)
    ->setSelectedTemplate($selectedTemplate)
    ->addRule(new \Simplon\Form\Rules\RequiredRule());

// ------------------------------------------

$name = (new \Simplon\Form\Elements\TextSingleLine\TextSingleLineElement())
    ->setId('name')
    ->setLabel('Name')
    ->setPlaceholder('Your first name')
    ->addRule(new \Simplon\Form\Rules\RequiredRule())
    ->addRule((new \Simplon\Form\Rules\ExactMatchRule())->setMatchValue('tino'));

// ------------------------------------------

$lastname = (new \Simplon\Form\Elements\TextSingleLine\TextSingleLineElement())
    ->setId('lastname')
    ->setLabel('Last name')
    ->setPlaceholder('Your last name')
    ->addRule(new \Simplon\Form\Rules\RequiredRule());

// ------------------------------------------

$password = (new \Simplon\Form\Elements\Password\PasswordElement())
    ->setId('password')
    ->setLabel('Password')
    ->setPlaceholder('Enter a password')
    ->addRule(new \Simplon\Form\Rules\RequiredRule())
    ->addRule((new \Simplon\Form\Rules\LengthMinRule())->setLength(6))
    ->addRule((new \Simplon\Form\Rules\LengthMaxRule())->setLength(10));

// ------------------------------------------

$passwordControl = (new \Simplon\Form\Elements\Password\PasswordElement())
    ->setId('password-control')
    ->setLabel('Password verification')
    ->setPlaceholder('Repeat your password')
    ->addRule(new \Simplon\Form\Rules\RequiredRule())
    ->addRule((new \Simplon\Form\Rules\MatchElementValueRule())->setMatchElement($password));

// ------------------------------------------

$address = (new \Simplon\Form\Elements\TextMultiLine\TextMultiLineElement())
    ->setId('address')
    ->setLabel('Address')
    ->setDescription('Enter your home address separated by new lines.')
    ->addRule(new \Simplon\Form\Rules\RequiredRule());

// ------------------------------------------

$age = (new \Simplon\Form\Elements\Select\SelectElement())
    ->setId('age')
    ->setLabel('Age')
    ->setPlaceholder('Choose...')
    ->setUseOptionKeys(true)
    ->setTopSplitKeys(['DE'])
    ->setSortByLabel(true)
    ->setOptions(['DE', 'EN', 'GR', 'UK', 'AR'])
    ->addRule(new \Simplon\Form\Rules\RequiredRule());

// ------------------------------------------

$options = [
    'complete' => 'Complete',
    'missing'  => 'Missing',
    'review'   => 'Review',
];

$agreed = (new \Simplon\Form\Elements\Radio\RadioElement())
    ->setId('agreed')
    ->setLabel('Status')
    ->setOptions($options)
    ->addRule(new \Simplon\Form\Rules\RequiredRule());

// ------------------------------------------

$submitButton = (new \Simplon\Form\Elements\SubmitButton\SubmitButtonElement())
    ->setId('submit')
    ->setLabel('Save');

$cancelAnchor = (new \Simplon\Form\Elements\Anchor\AnchorElement())
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
    ->setTemplate('./template.mustache')
    ->addElement($city)
    ->addElement($name)
    ->addElement($lastname)
    ->addElement($password)
    ->addElement($passwordControl)
    ->addElement($address)
    ->addElement($age)
    ->addElement($agreed);

// ##########################################

if ($form->isValid() === true)
{
    echo '<h1>Valid form!</h1><a href="page.php">back to the start</a>';
    exit;
}

echo $form->render();