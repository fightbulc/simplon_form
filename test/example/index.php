<?php

use Simplon\Form\Data\FormField;
use Simplon\Form\Data\Rules\EmailRule;
use Simplon\Form\Data\Rules\RequiredRule;
use Simplon\Form\FormFields;
use Simplon\Form\FormValidator;
use Simplon\Form\View\Elements\DateCalendarElement;
use Simplon\Form\View\Elements\DropDownApiElement;
use Simplon\Form\View\Elements\DropDownElement;
use Simplon\Form\View\Elements\ImageUploadElement;
use Simplon\Form\View\Elements\InputTextElement;
use Simplon\Form\View\Elements\Support\DropDownApi\Algolia\AlgoliaPlacesApiJs;
use Simplon\Form\View\Elements\Support\Meta\OptionsMeta;
use Simplon\Form\View\FormView;
use Simplon\Form\View\FormViewBlock;
use Simplon\Form\View\FormViewRow;
use Simplon\Phtml\Phtml;

require __DIR__ . '/../../vendor/autoload.php';

//
// define fields
//

$storedData = [
    'address'   => ['Mr.', 'Mrs.'],
    'firstname' => 'Foo',
    'lastname'  => 'Bar',
    'email'     => 'foo@bar.me',
    'url_image' => 'test.jpg',
];

$fields = (new FormFields())
    ->add((new FormField('address'))->addMeta((new OptionsMeta())->add('Mr.')->add('Mrs.'))->addRule(new RequiredRule()))
    ->add((new FormField('firstname'))->addRule(new RequiredRule()))
    ->add((new FormField('lastname'))->addRule(new RequiredRule()))
    ->add((new FormField('email'))->addRule(new EmailRule()))
    ->add((new FormField('startDate'))->addRule(new RequiredRule()))
    ->add((new FormField('endDate'))->addRule(new RequiredRule()))
    ->add((new FormField('city'))->addRule(new RequiredRule()))
    ->add((new FormField('url_image'))->addRule(new RequiredRule()))
    ->applyInitialData($storedData)
;

//
// validate form
//

$requestData = $_POST;
$validator = (new FormValidator($requestData))->addFields($fields)->validate();

if ($validator->hasBeenSubmitted())
{
    echo '<pre>';
    var_dump([
        'is_valid'   => $validator->isValid(),
        'field_data' => $fields->getAllData(),
    ]);
    echo '</pre>';
}

//
// render view
//

$addressElement = (new DropDownElement($fields->get('address')))->enableMultiple()->setLabel('Address');
$firstnameElement = (new InputTextElement($fields->get('firstname')))->setLabel('First name');
$lastnameElement = (new InputTextElement($fields->get('lastname')))->setLabel('Last name');
$emailElement = (new InputTextElement($fields->get('email')))->setLabel('Email address')->setDescription('Required in order to send you a confirmation');

$defaultBlock = (new FormViewBlock('default'))
    ->addRow((new FormViewRow())->autoColumns($addressElement))
    ->addRow((new FormViewRow())->autoColumns($firstnameElement)->autoColumns($lastnameElement))
    ->addRow((new FormViewRow())->autoColumns($emailElement))
;

// ====================================

$startDateElement = (new DateCalendarElement($fields->get('startDate')))->setLabel('Start date')->setDescription('To make sure you are an adult')->dateOnly()->setDateFormat('DD/MM/YYYY');
$endDateElement = (new DateCalendarElement($fields->get('endDate'), $startDateElement))->setLabel('End date')->setDescription('To make sure you are an adult')->dateOnly()->setDateFormat('DD/MM/YYYY');

$datesBlock = (new FormViewBlock('dates'))
    ->addRow((new FormViewRow())->autoColumns($startDateElement)->autoColumns($endDateElement));

// ====================================

$cityElement = (new DropDownApiElement($fields->get('city'), (new AlgoliaPlacesApiJs())->setType(AlgoliaPlacesApiJs::TYPE_CITY)))->enableMultiple()->setLabel('City')->setDescription('Search for a city');

$citiesBlock = (new FormViewBlock('cities'))
    ->addRow((new FormViewRow())->autoColumns($cityElement));

// ====================================

$imageElement = (new ImageUploadElement($fields->get('url_image')))
    ->setLabel('Upload')
    ->setUploadUrl('#')
;

$imageBlock = (new FormViewBlock('image'))
    ->addRow((new FormViewRow())->autoColumns($imageElement));

// ====================================

$view = (new FormView())
    ->setComponentDir('../../assets/vendor')
    ->addBlock($defaultBlock)
    ->addBlock($datesBlock)
    ->addBlock($citiesBlock)
    ->addBlock($imageBlock)
;

echo (new Phtml())->render(__DIR__ . '/page.phtml', ['formView' => $view]);