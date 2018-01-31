<?php

use Simplon\Form\CloneFields;
use Simplon\Form\Data\FormField;
use Simplon\Form\Data\Rules\EmailRule;
use Simplon\Form\Data\Rules\IfFilledRule;
use Simplon\Form\Data\Rules\RequiredRule;
use Simplon\Form\Data\Rules\UrlRule;
use Simplon\Form\FormError;
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
use Simplon\Phtml\PhtmlException;

require __DIR__ . '/../../vendor/autoload.php';

try
{
    $requestData = $_POST;
//die(var_dump($requestData));

//
// define fields
//

    $cloneAddressId = 'address';
    $cloneDatesId = 'dates';

    $initialData = [
        'bikes'     => 'Specialized',
        'url_image' => 'test.jpg',
        'clones'    => [
            $cloneAddressId => [
                [
                    'address'   => 'Mr.',
                    'firstname' => 'Foo',
                    'lastname'  => 'Bar',
                    'email'     => 'foo@bar.me',
                ],
                [
                    'address'   => 'Mrs.',
                    'firstname' => 'Joe',
                    'lastname'  => 'Black',
                    'email'     => 'joe@black.me',
                ],
            ],
            $cloneDatesId   => [
                [
                    'startDate' => '2017-06-17',
                    'endDate'   => '2017-06-20',
                ],
                [
                    'startDate' => '2017-08-05',
                    'endDate'   => '2017-08-10',
                ],
            ],
        ],
    ];

    $cloneBlockOne = (new CloneFields($cloneAddressId))
        ->add((new FormField('address'))->addMeta((new OptionsMeta())->add('Mr.')->add('Mrs.'))->addRule(new RequiredRule()))
        ->add((new FormField('firstname'))->addRule(new RequiredRule()))
        ->add((new FormField('lastname'))->addRule(new RequiredRule()))
        ->add((new FormField('email'))->addRule(new EmailRule()))
        ->add((new FormField('website'))->addRule(new IfFilledRule([(new UrlRule('ftp'))->setAdditionalRegex('/^http:\/\//i')])))
    ;

    $cloneBlockTwo = (new CloneFields($cloneDatesId))
        ->add((new FormField('startDate'))->addRule(new RequiredRule()))
        ->add((new FormField('endDate'))->addRule(new RequiredRule()))
    ;

    $fields = (new FormFields())
        ->add((new FormField('bikes'))->addMeta((new OptionsMeta())->add('Canyon')->add('Specialized'))->addRule(new RequiredRule()))
        ->add((new FormField('city'))->addRule(new RequiredRule()))
        ->add((new FormField('url_image'))->addRule(new RequiredRule()))
        ->add($cloneBlockOne)
        ->add($cloneBlockTwo)
        ->applyBuildData($initialData, $requestData)
    ;

//
// validate form
//

    $validator = (new FormValidator($requestData))->addFields($fields)->validate();

    if ($validator->hasBeenSubmitted())
    {
        if (!$validator->isValid())
        {
//            echo '<div style="background:#f00;color:#fff;padding:10px">INVALID</div>';
        }
        else
        {
//            echo '<div style="background:#0F0;color:#fff;padding:10px">ALL VALID</div>';

//            echo '<pre>';
//            var_dump([
//                $fields->getAllData(),
//                (new DropDownApiData())->fromForm($fields->getData('city'))->toArray(),
//            ]);
//            echo '</pre>';
        }
    }

//
// render view
//

    $defaultBlocks = (new CloneFormViewBlock($fields->fetchCloneField($cloneAddressId)))->build(
        function (FormViewBlock $viewBlock, string $token) use ($fields) {
            $addressElement = (new DropDownElement($fields->get('address', $token)))->enableMultiple()->setLabel('Address');
            $firstnameElement = (new InputTextElement($fields->get('firstname', $token)))->setLabel('First name');
            $lastnameElement = (new InputTextElement($fields->get('lastname', $token)))->setLabel('Last name');
            $emailElement = (new InputTextElement($fields->get('email', $token)))->setLabel('Email address')->setDescription('Required in order to send you a confirmation');
            $websiteElement = (new InputTextElement($fields->get('website', $token)))->setLabel('Homepage URL')->setDescription('In case you have a homepage');

            return $viewBlock
                ->addRow((new FormViewRow())->autoColumns($addressElement))
                ->addRow((new FormViewRow())->autoColumns($firstnameElement)->autoColumns($lastnameElement))
                ->addRow((new FormViewRow())->autoColumns($emailElement))
                ->addRow((new FormViewRow())->autoColumns($websiteElement))
                ;
        }
    );

// ====================================

    $datesBlocks = (new CloneFormViewBlock($fields->fetchCloneField($cloneDatesId)))->build(
        function (FormViewBlock $viewBlock, string $token) use ($fields) {
            $startDateElement = (new DateCalendarElement($fields->get('startDate', $token)))->setLabel('Start date')->setDescription('To make sure you are an adult')->dateOnly()->setDateFormat('DD/MM/YYYY');
            $endDateElement = (new DateCalendarElement($fields->get('endDate', $token), $startDateElement))->setLabel('End date')->setDescription('To make sure you are an adult')->dateOnly()->setDateFormat('DD/MM/YYYY');

            return $viewBlock->addRow(
                (new FormViewRow())->autoColumns($startDateElement)->autoColumns($endDateElement)
            );
        }
    );

// ====================================

    $bikesBlock = (new FormViewBlock('bikes'))
        ->addRow((new FormViewRow())->autoColumns(
            (new DropDownElement($fields->get('bikes')))
                ->enableMultiple()
                ->enableSearchable()
        ));

// ====================================

    $citiesBlock = (new FormViewBlock('cities'))
        ->addRow((new FormViewRow())->autoColumns(
            (new DropDownApiElement($fields->get('city'), (new AlgoliaPlacesApiJs())->setType(AlgoliaPlacesApiJs::TYPE_CITY)))
                ->enableMultiple()
                ->setLabel('City')
                ->setDescription('Search for a city')
                ->setPlaceholder('Search by city ...')
        ));

// ====================================

    $imageBlock = (new FormViewBlock('image'))
        ->addRow((new FormViewRow())->autoColumns(
            (new ImageUploadElement($fields->get('url_image')))->setQuality(1)
        ));

// ====================================

    FormView::useOptionalLabel(true);

    $view = (new FormView())
        ->setComponentDir('../../assets/vendor')
        ->addBlocks($defaultBlocks)
        ->addBlocks($datesBlocks)
        ->addBlock($bikesBlock)
        ->addBlock($citiesBlock)
        ->addBlock($imageBlock)
    ;

    echo (new Phtml())->render(__DIR__ . '/page.phtml', ['formView' => $view]);
}
catch (FormError $e)
{
}
catch (PhtmlException $e)
{
}