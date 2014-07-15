<?php

require __DIR__ . '/../../vendor/autoload.php';

// ----------------------------------------------

$cancelAnchor = (new \Simplon\Form\Elements\Anchor\AnchorElement())
    ->setId('cancel')
    ->setLabel('Cancel')
    ->setUrl('page.php');

$submitButton = (new \Simplon\Form\Elements\SubmitButton\SubmitButtonElement())
    ->setId('submit')
    ->setLabel('Submit Data');

// ----------------------------------------------

$fullname = (new \Simplon\Form\Elements\TextSingleLine\TextSingleLineElement())
    ->setId('fullname')
    ->setLabel('Full name')
    ->setDescription('Enter your full name')
    ->setPlaceholder('Full name')
    ->addRule(new \Simplon\Form\Rules\RequiredRule());

$city = (new \Simplon\Form\Elements\TextSingleLine\TextSingleLineElement())
    ->setId('city')
    ->setLabel('City')
    ->setDescription('Enter your city')
    ->setPlaceholder('City')
    ->addRule(new \Simplon\Form\Rules\RequiredRule());

$food = (new \Simplon\Form\Elements\Checkbox\CheckboxButtonGroupElement())
    ->setId('food')
    ->setLabel('Foods')
    ->setDescription('Please select between 2-4 fruits')
    ->setUseOptionKeys(false)
    ->setOptions(['Apfel', 'Birne', 'Orange', 'Döner', 'Brülle',])
    ->setPreselectedOption(['Birne'])
    ->addRule((new \Simplon\Form\Rules\CheckboxRangeCheckedRule())->setMinChecked(2)->setMaxChecked(4));

$cars = (new \Simplon\Form\Elements\Radio\RadioButtonGroupElement())
    ->setId('cars')
    ->setLabel('Car Brands')
    ->setDescription('Please select the brand you favourite the most.')
    ->setUseOptionKeys(false)
    ->setOptions(['Audi', 'BMW', 'Mercedes', 'Trabant'])
    ->addRule((new \Simplon\Form\Rules\RequiredRule())->setErrorMessage('Select a car'));

$country = (new \Simplon\Form\Elements\Select\SelectElement())
    ->setId('country')
    ->setLabel('Country')
    ->setDescription('Choose your country')
    ->setPlaceholder('Choose')
    ->setUseOptionKeys(false)
    ->setOptions(['Germany', 'Spain', 'France'])
    ->setSortByLabel(true)
    ->addRule(new \Simplon\Form\Rules\RequiredRule());

$sendEmail = (new \Simplon\Form\Elements\SwitchBox\SwitchBoxElement())
    ->setId('sendEmail')
    ->setLabel('Send Email')
    ->setDescription('Subscribe our Newsletter?');

// ----------------------------------------------

$form = (new \Simplon\Form\Form())
    ->setId('reset')
    ->setUrl('')
    ->setMethod('POST')
    ->setGeneralErrorMessage('<strong>Validation failed.</strong> Have a look at the error notes below.')
    ->setSubmitElement($submitButton)
    ->setCancelElement($cancelAnchor)
    ->addElement($fullname)
    ->addElement($city)
    ->addElement($food)
    ->addElement($cars)
    ->addElement($country)
    ->addElement($sendEmail)
    ->setTemplate('./template.mustache');

// ----------------------------------------------

if ($form->isValid() === true)
{
    echo '<h1>Valid form!</h1><a href="page.php">back to the start</a>';
    echo '<hr>';
    var_dump($form->getElementValues());
    exit;
}

echo $form->render();