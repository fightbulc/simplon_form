<?php

use Simplon\Form\CloneFields;
use Simplon\Form\Data\FormField;
use Simplon\Form\Data\Rules\EmailRule;
use Simplon\Form\Data\Rules\RequiredRule;
use Simplon\Form\FormFields;
use Simplon\Form\FormValidator;
use Simplon\Form\View\CloneFormViewBlock;
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

$requestData = $_POST;
//die(var_dump($requestData));

//
// define fields
//

$storedData = [
    'address'   => ['Mr.', 'Mrs.'],
    'firstname' => 'Foo',
    'lastname'  => 'Bar',
    'email'     => 'foo@bar.me',
    'url_image' => 'test.jpg',
    'clones'    => [
        [
            'startDate' => '17/06/2017',
            'endDate'   => '20/06/2017',
        ],
        [
            'startDate' => '23/06/2017',
            'endDate'   => '26/06/2017',
        ],
    ],
];

$cloneBlockOne = (new CloneFields('defaults', $requestData, $storedData))
    ->add((new FormField('address'))->addMeta((new OptionsMeta())->add('Mr.')->add('Mrs.'))->addRule(new RequiredRule()))
    ->add((new FormField('firstname'))->addRule(new RequiredRule()))
    ->add((new FormField('lastname'))->addRule(new RequiredRule()))
    ->add((new FormField('email'))->addRule(new EmailRule()))
;

$cloneBlockTwo = (new CloneFields('dates', $requestData, $storedData))
    ->add((new FormField('startDate'))->addRule(new RequiredRule()))
    ->add((new FormField('endDate'))->addRule(new RequiredRule()))
;

$fields = (new FormFields())
    ->addCloneFields($cloneBlockOne)
    ->addCloneFields($cloneBlockTwo)
    ->add((new FormField('city'))->addRule(new RequiredRule()))
    ->add((new FormField('url_image'))->addRule(new RequiredRule()))
    ->applyInitialData($storedData)
;

//
// validate form
//

$validator = (new FormValidator($requestData))->addFields($fields)->validate();

if ($validator->hasBeenSubmitted())
{
    if (!$validator->isValid())
    {
        echo '<div style="background:#f00;color:#fff;padding:10px">INVALID</div>';
    }

//    echo '<pre>';
//    var_dump($fields->getAllData());
//    echo '</pre>';
}

//
// render view
//

$build = function (FormViewBlock $viewBlock, string $token) use ($fields) {
    $addressElement = (new DropDownElement($fields->get('address', $token)))->enableMultiple()->setLabel('Address');
    $firstnameElement = (new InputTextElement($fields->get('firstname', $token)))->setLabel('First name');
    $lastnameElement = (new InputTextElement($fields->get('lastname', $token)))->setLabel('Last name');
    $emailElement = (new InputTextElement($fields->get('email', $token)))->setLabel('Email address')->setDescription('Required in order to send you a confirmation');

    return $viewBlock
        ->addRow((new FormViewRow())->autoColumns($addressElement))
        ->addRow((new FormViewRow())->autoColumns($firstnameElement)->autoColumns($lastnameElement))
        ->addRow((new FormViewRow())->autoColumns($emailElement))
        ;
};

$defaultBlocks = (new CloneFormViewBlock($cloneBlockOne))->build($build);

// ====================================

$build = function (FormViewBlock $viewBlock, string $token) use ($fields) {
    $startDateElement = (new DateCalendarElement($fields->get('startDate', $token)))->setLabel('Start date')->setDescription('To make sure you are an adult')->dateOnly()->setDateFormat('DD/MM/YYYY');
    $endDateElement = (new DateCalendarElement($fields->get('endDate', $token), $startDateElement))->setLabel('End date')->setDescription('To make sure you are an adult')->dateOnly()->setDateFormat('DD/MM/YYYY');

    return $viewBlock->addRow(
        (new FormViewRow())->autoColumns($startDateElement)->autoColumns($endDateElement)
    );
};

$datesBlocks = (new CloneFormViewBlock($cloneBlockTwo))->build($build);

// ====================================

$cityElement = (new DropDownApiElement($fields->get('city'), (new AlgoliaPlacesApiJs())->setType(AlgoliaPlacesApiJs::TYPE_CITY)))
    ->enableMultiple()
    ->setLabel('City')
    ->setDescription('Search for a city');

$citiesBlock = (new FormViewBlock('cities'))
    ->addRow((new FormViewRow())->autoColumns($cityElement));

// ====================================

$imageElement = (new ImageUploadElement($fields->get('url_image')))->setQuality(.75);

$imageBlock = (new FormViewBlock('image'))
    ->addRow((new FormViewRow())->autoColumns($imageElement));

// ====================================

$view = (new FormView())
    ->setComponentDir('../../assets/vendor')
    ->addBlocks($defaultBlocks)
    ->addBlocks($datesBlocks)
    ->addBlock($citiesBlock)
    ->addBlock($imageBlock)
;

echo (new Phtml())->render(__DIR__ . '/page.phtml', ['formView' => $view]);