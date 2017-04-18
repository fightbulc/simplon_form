# Simplon/Forms

`Simplon/Forms` helps to validate data and, if needed, to build a form view with a couple of widgets by leveraging `Semantic-UI` library.

-------------------------------------------------

1. [__Quick example__](#1-quick-example)  
1.1 [Fields](#11-fields)  
1.2 [Validation](#12-validation)  
1.3 [View](#13-view)  
2. [__Fields__](#2-fields)  
2.1 [General fields](#21-general-fields)  
2.2 [Fields with options](#22-fields-with-options)  
2.3 [Rules](#23-rules)  
2.4 [Filters](#24-filters)  
3. [__View__](#3-view)  
3.1 [Simple example](#31-simple-example)  
3.2 [Blocks & Rows](#32-blocks--rows)  
3.3 [Elements](#33-elements)  
4. [__Examples__](#4-examples)  
4.1 [Place search](#41-place-search)  

-------------------------------------------------

# 1. Quick example
 
## 1.1 Fields

In order to validate data we need to create at least one field which can hold any number of rules to define its validity. A field can also hold any number of filters which will be applied to the field value.

### Some field examples

```php
//
// easiest setup
//

(new FormFields())->add(
	new FormField('email')
);

//
// we can add filters
//

(new FormFields())->add(
	(new FormField('email'))->addFilter(new CaseLowerFilter())
);

//
// and let's make sure that we have an email address
// 

(new FormFields())->add(
	(new FormField('email')->addRule(new EmailRule())
);
```

### Pre-populating your fields

We can pre-poluate our fields with data we already have - not `request` data:

```php
//
// our fields
//

$fields = (new FormFields())->add(
	(new FormField('email'))->addRule(new EmailRule())
);

//
// set our data
//

$fields->applyInitialData([
	'email' => 'foo@bar.com',
]);

//
// testing ...
//

$fields->get('email')->getInitialValue(); // foo@bar.com
$fields->get('email')->getValue(); // foo@bar.com
```

## 1.2 Validation
 
`FormValidation` is expecting at least one set of `FormFields`. Let's pick up the example from above with an additional `name` field. We set an empty array for our `request data`. You can take the value from any source as long as its organised as an array.

```php
//
// request data
//

$requestData = [
	'name' => 'Johnny',
	'email' => '',
];

//
// define fields
//

$fields = new FormFields();

$fields->add(
	new FormField('name')
);

$fields->add(
	(new FormField('email'))->addRule(new EmailRule())
);

//
// validation
//

$validator = new FormValidator($requestData);

if($validator->hasBeenSubmitted()) // any request data?
{
    if($validator->validate()->isValid())
    {
	    // all validated field data as array
    	var_dump($fields->getAllData());
    }
    else
    {
	    // array of error messages
    	var_dump($validator->getErrorMessages());

		// OR ...
		
    	// array of error fields    	
    	var_dump($validator->getErrorFields());
    }
}
```

`FormValidator::hasBeenSubmitted` checks if our received data are empty or filled. In case we received data we can go ahead and run all applied rules over our defined fields by `FormValidator::validate` and `FormValidator::isValid` tells us if all fields passed their requirements.

In case of success we can collect all field values by `FormFields::getAllData`. Otherwise you can check for errors by collecting the error messages via `FormValidator::getErrorMessages` or by collecting all error fields `FormValidator::getErrorFields`. The latter also holds all error messages by field.

## 1.3 View

In order to render your fields we need to apply them to `Elements`. These elements can be applied to the `FormView` directly or to a `FormBlock` which renders our form automatically in a set structure. We will continue with our before defined fields:

```php
//
// define fields
//

$nameElement = (new InputTextElement($fields->get('name')))
	->setLabel('Email address')
	->setDescription('Required in order to send you a confirmation');

$emailElement = (new InputTextElement($fields->get('email')))
	->setLabel('Email address')
	->setDescription('Required in order to send you a confirmation');

//
// apply to a block w/ one row
//

$block = (new FormViewBlock('default')) // set block ID to default
    ->addRow(
	    (new FormViewRow())
	    	->autoColumns($nameElement)
	    	->autoColumns($emailElement)
    )
;

//
// set view
//

$formView = (new FormView())->addBlock($block);
```

We could set much more options but that should do it for now. Let's pass on our view to a template.
For the following example we assume that we have access to the `$view` variable. We simply pass on a form template to the `FormView::render` method and include all `core assets`:

```php
<?php
/**
 * @var FormView $formView
 */
use Simplon\Form\View\FormView;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <title>simplon/form</title>

    <link href="/assets/vendor/semantic-ui/2.2.x/semantic.min.css" rel="stylesheet">
    <link href="/assets/vendor/simplon-form/base.min.css" rel="stylesheet">

    <style type="text/css">
        body {
            padding: 0;
            background: #fff;
        }
    </style>
</head>
<body>
    <div class="ui container">
        <?= $formView->render(__DIR__ . '/form.phtml') ?>
    </div>

    <script src="/assets/vendor/jquery/3.1.x/jquery.min.js"></script>
    <script src="/assets/vendor/semantic-ui/2.2.x/semantic.min.js"></script>
    <script src="/assets/vendor/simplon-form/base.min.js"></script>

    <?= $formView->renderFieldAssets() ?>
</body>
</html>
```
We separate our main template from the actual form template so that we can automatically render the required `<form></form>` tags with all required attributes at the beginning and the end of the template. We also inject the `FormView` instance as `$formView` variable.

```php
<?php
/**
 * @var FormView $formView
 */
use Simplon\Form\View\FormView;

?>

<?php if ($formView->hasErrors()): ?>
    <div class="ui basic segment">
        <?= $formView->renderErrorMessage() ?>
    </div>
<?php endif ?>

<div class="ui basic segment">
    <?= $formView->getBlock('default')->renderBlock() ?>
</div>

<div class="ui basic segment">
    <?= $formView->getSubmitElement()->renderElement() ?>
</div>
```

Any validation errors would be rendered on top of the template. After this follows our defined block with the ID `default`. This statement will render both of your fields next to each other with equal spacing. At the end you can place your `Submit element` which comes in our example as an automatically set element. You can also define this field yourself.

# 2. Fields

Fields are an abstraction of your data and are by design very generic. The goal was that fields should work with incoming API requests as well as with the usual html forms.

## 2.1. General fields

Most of the fields simply require one rule and a filter. These would be most commonly `RequiredRule` and the `TrimFilter`. The latter comes already by default. What you definitely need is a `field-id` which needs to be passed on with the constructor.

```php
$field = (new FormField('name'))->addRule(new RequiredRule()) // required field with ID "name"
```

## 2.2. Fields with options

You might have fields which accept only a given set of values. In that case you can add `options` as meta data to the field. Classic example would be an address field or a country selection.

```php
$options = (new OptionsMeta())
	->add('DE', 'Germany')
	->add('FR', 'France')
	->add('US', 'United States')
	;
	
$field = (new FormField('shipping-to'))
	->addMeta($options)
	->addRule(new RequiredRule())
	;
```

## 2.3. Rules

There are a couple of common rules which we used so for all our form requirements. You can find them below. However, if you are in need to have some more rules it's very easy to add/extend rules. Just take a look at one of the existing rules to see how to do that.
 
### RequiredRule

Mark a field to be required.

```php
(new FormField('name'))->addRule(new RequiredRule())
```

### UrlRule

Make sure that the field's value validates against a URL format.

```php
(new FormField('website'))->addRule(new UrlRule())
```

### EmailRule

Make sure that the field's value validates against an email format.

```php
(new FormField('email'))->addRule(new EmailRule())
```

### ExactLengthRule

Make sure that the field's value validates against an exact length.

```php
(new FormField('photos'))->addRule(new ExactLengthRule(5))
```

### MaxLengthRule

Make sure that the field's value validates against a maximum length.

```php
(new FormField('photos'))->addRule(new MaxLengthRule(5))
```

### MinLengthRule

Make sure that the field's value validates against a minimum length.

```php
(new FormField('photos'))->addRule(new MinLengthRule(1))
```

### CallbackRule

Sometimes you need to run a check against the database or maybe to a cross-reference with some other fields. 
In that case this rule comes in handy. It lets you handle the validation and requires only the return of a boolean value to determine if the value is valid. We use it e.g. to make sure that the given email address is unique before we accept a new user registration.

First parameter takes the `callback` and the second parameter is an optional `error message`.

```php
$callback = function(FormField $field)
{
	$model = $this->db->read(['email' => $field->getValue()]);

	return $model === null;
};

(new FormField('email'))->addRule(new CallbackRule($callback, 'Email address exists already'))
```

### IfFilledRule

Sometimes we have optional fields which should only validate against a set of rules if they have a value.
For this case we have this rule in place.

```php
(new FormField('email'))->addRule(new IfFilledRule([new EmailRule()])
```

### FieldDependencyRule

This rules lets you add rules to another field. Imagine that your actual field is an optional one and only if filled you want to validate some other fields.

```php
$email = new FormField('email');

$depRule = new FieldDependencyRule($email, [new EmailRule()]);

(new FormField('newsletter'))->addRule(new IfFilledRule([$depRule]))
```

### WithinOptionsRule

Semantic-UI's drop-down field saves its selection in a hidden field. For multi-selection fields the selected values will be separated by a comma within that hidden field. To make sure that the submitted values still match your given options we can make use of `WithinOptionsRule`.

```php
$options = (new OptionsMeta())
	->add('DE', 'Germany')
	->add('FR', 'France')
	->add('US', 'United States')
	;
	
$field = (new FormField('shipping-to'))
	->addMeta($options)
	->addRule(new RequiredRule())
	->addRule(new WithinOptionsRule())
	;
```

## 2.4. Filters

A filter is run over your submitted field values. For instance, to make sure that a textfield does not include any white space characters you can add `TrimFilter` to your field and simplon\form will make sure that your field value is cleared before processed further. Below is a list available filters but as for the rules you are able to add your own filters. Just take a look at one of the filters. Lastly, filters are combinable.

### CaseLowFilter

Transform the field value to all lower-case.

```php
(new FormField('email'))->addFilter(new CaseLowFilter()); // Foo@BAR.com --> foo@bar.com
```

### CaseTitleFilter

Uppercase the first character of each word in the field's value.

```php
(new FormField('name'))->addFilter(new CaseTitleFilter()); // foo bar --> Foo Bar
```

### CaseUpperFilter

Uppercase the the complete field's value.

```php
(new FormField('name'))->addFilter(new CaseUpperFilter()); // foo bar --> FOO BAR
```

### TrimFilter

__Each field holds this filter by default.__ Strip whitespace (or other characters) from the beginning and end of the field's value.

```php
(new FormField('emai'))->addFilter(new TrimFilter()); // " foo@bar.com " --> "foo@bar.com"
```

You can override the default trimming characters by passing them through the filter's constructor:

```php
(new FormField('emai'))->addFilter(new TrimFilter("$")); // "$foo@bar.com$" --> "foo@bar.com"
```

Instead of overriding the default trimming characters you can simply add characters:

```php
(new FormField('emai'))->addFilter(
	(new TrimFilter())->addChars("$")
);

// " foo@bar.com$" --> "foo@bar.com"
```

### TrimLeftFilter

Same as for the `TrimFilter` but only for the left-side of the field's value.

```php
(new FormField('emai'))->addFilter(new TrimLeftFilter()); // " foo@bar.com" --> "foo@bar.com"
```

### TrimRightFilter

Same as for the `TrimFilter` but only for the right-side of the field's value.

```php
(new FormField('emai'))->addFilter(new TrimRightFilter()); // "foo@bar.com " --> "foo@bar.com"
```

### XssFilter

Use this filter to avoid [XSS](https://en.wikipedia.org/wiki/Cross-site_scripting). The filter will try to catch and remove all html-related elements which seem to appear in your field's value.

```php
(new FormField('comment'))->addFilter(new XssFilter());

// "A comment <script>...</script>" --> "A comment"
```

# 3. View

A view helps you to collect your fields in a structured way and to render them to display your form.

## 3.1. Simple example

For this example we would like to create a form for entering an email address. Remember that html structure is build on top of Semantic-UI's grid and widgets.

### Code

```php
//
// fields
//

$emailId = 'email';

$fields = (new FormFields())->add(
	(new FormField($emailId))->addRule(new EmailRule())
);

//
// validation
//

$validator = (new FormValidator($_POST))->addFields($fields)->validate();

if ($validator->hasBeenSubmitted())
{
	// do something when form is OK
}

//
// build view
//

$view = (new FormView())->addElement(
	(new InputTextElement($fields->get($emailId)))->setLabel('Email address')
);

//
// render view
// https://github.com/fightbulc/simplon_phtml
// 

echo (new Phtml())->render('page.phtml', ['formView' => $view]);
```

### Page template

```php	
/**
 * @var FormView $formView
 */
use Simplon\Form\View\FormView;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <title>simplon/form</title>

    <link href="/assets/vendor/semantic-ui/2.2.x/semantic.min.css" rel="stylesheet">
    <link href="/assets/vendor/simplon-form/base.min.css" rel="stylesheet">
</head>
<body>
    <div class="ui container">
        <?= $formView->render('form.phtml') ?>
    </div>

    <script src="/assets/vendor/jquery/3.1.x/jquery.min.js"></script>
    <script src="/assets/vendor/semantic-ui/2.2.x/semantic.min.js"></script>
    <script src="/assets/vendor/simplon-form/base.min.js"></script>

    <?= $formView->renderFieldAssets() ?>
</body>
</html>
```

### Form template

```php	
/**
 * @var FormView $formView
 */
use Simplon\Form\View\FormView;

?>

<?php if ($formView->hasErrors()): ?>
    <div class="ui basic segment">
        <?= $formView->renderErrorMessage() ?>
    </div>
<?php endif ?>

<div class="ui basic segment">
    <?= $formView->getElement('email')->renderElement() ?>
</div>

<div class="ui basic segment">
    <?= $formView->getSubmitElement()->renderElement() ?>
</div>
```

## 3.2. Blocks & Rows

In the prior example we built our view by adding the email element directly to our view. Blocks and rows help us to automatically arrange our elements with ease.

### Blocks

Blocks are directly added to your view and hold a unique ID which is needed to reference them later in your template. You can add as many block as you wish.

```php
$block = new FormViewBlock('foo');

(new FormView())->addBlock($block);
```

### Rows

Rows are added to your blocks and hold your elements. A row structures your elements in columns. There is no row limit for your blocks. Let's continue the blocks example:

```php
$block = new FormViewBlock('foo');

$someElement = ... ; // some element

$block->addRow(
	(new FormViewRow())->autoColumns($someElement) // takes up all columns
);

(new FormView())->addBlock($block);
```

You can see that we are using `autoColumns()` for setting our element. This means that the element will take as much space as is available for this row. For instance if we would have set second element for this row both elements would take 50% of the row's width.

```php
// before

// all auto columns

$block->addRow(
	(new FormViewRow())
		->autoColumns($someElement) // takes up half of all columns
		->autoColumns($someOtherElement) // takes up half of all columns
);

// after
```

It is also possible to set a specific width for each element respectively to combine `auto-width` with a `specific-width`.


```php
// before

// two rows with mixed width specifications

$block
	->addRow(
		(new FormViewRow())
			->threeColumns($someElement) // takes up 3 columns
			->autoColumns($someOtherElement) // takes everything what is left (13 columns)
	)
	->addRow(
		(new FormViewRow())
			->tenColumns($someElement) // takes up 10 columns
			->sixColumns($someOtherElement) // takes up 6 columns
	)
;

// after
```

## 3.3. Elements

Most of the elements require a `FormField` in order to be build. The following elements come delivered with your simplon\form release. You can always build your own elements which should inherit the abstract `Element` class.

### InputTextElement

This builds a single-line text field.

```php
$element = new InputTextElement(
	new FormField('name')
);

$element
	->setLabel('Your name')
	->setPlaceholder('Enter your name ...')
	->setDescription('Name is needed so that we can address your properly')
	;
	
// attach to FormView ...
```

### InputPasswordElement

This builds a single-line password field. This field inherits from `InputTextElement`.

```php
$element = new InputPasswordElement(
	new FormField('password')
);

$element
	->setLabel('Your password')
	->setPlaceholder('Enter your password ...')
	->setDescription('Needed so that you can login')
	;

// attach to FormView ...
```

### InputHiddenElement

This builds a hidden field. This field inherits from `InputTextElement`.

```php
$element = new InputHiddenElement(
	new FormField('counter')
);

// attach to FormView ...
```

### TextareaElement

This builds a multi-line text field.

```php
$element = new TextareaElement(
	new FormField('comment')
);

$element
	->setLabel('Your comment')
	->setPlaceholder('Enter your comment ...')
	->setRows(10) // determines rows-height; default is 4
	;
	
// attach to FormView ...
```


### CheckboxElement

We use checkbox elements for fields which offer one or more options to choose from - but in general only a few options. It requires at least one option. If any option has been selected you will receive an array of the selected options.

```php
$options = (new OptionsMeta())->add('yes', 'I herewith confirm ...');

$confirmElement = new CheckboxElement(
	(new FormField('confirm'))->addMeta($options)
);

// attach to FormView ...

// [ ] I herwith confirm ...
```

Example for multiple values. We only need to add more options. Also, notice that it's sufficient to define a value for each our options. The value will be used for the label.

```php
$options = (new OptionsMeta())
	->add('Magazines')
	->add('Books')
	->add('Newspapers')
	;

$confirmElement = new CheckboxElement(
	(new FormField('reading'))->addMeta($options)
);

// attach to FormView ...

// [ ] Magazines
// [ ] Books
// [ ] Newspapers
```

### RadioElement

We use radio elements for fields which offer only a few options but where we require only one selection. Your selected option will be Note that it's sufficient to define a value for each our options. The value will be used for the label.

```php
$options = (new OptionsMeta())
	->add('Magazines')
	->add('Books')
	->add('Newspapers')
	;

$confirmElement = new RadioElement(
	(new FormField('reading'))->addMeta($options)
);

// attach to FormView ...

// ( ) Magazines
// ( ) Books
// ( ) Newspapers
```

### SubmitElement

This builds a submit button which can be added to your `FormView`. It does not require any field but you may set a button label and add css classes.

```php
$element = new SubmitElement('Save data', ['foo-class', 'bar-class']); // values are optional
	
// attach to FormView ...
```

### DropDownElement

We use drop-down elements for fields which offer one or more options to choose from. It requires at least one option. If any option has been selected you will receive it as a string within your request data. Multiple selections are passed on as string separated with commas.

Drop-down elements can either accept one- or multiple-selected options. We are also able to add new options on the fly. Further, we are able to filter through all options.

```php
$options = (new OptionsMeta())
	->add('DE', 'Germany')
	->add('FR', 'France')
	->add('US', 'United States')
	->add('...')
	->add('...')
	->add('...')
	;

$element = new DropDownElement(
	(new FormField('countries'))->addMeta($options)
);

$element
	->setLabel('City')
	->setDescription('Search for a city')
	->enableMultiple()		// allows selection of multiple options
	->enableSearchable()	// lets user search over options
	->enableAdditions()		// lets user add new options
;

// attach to FormView ...
```

### TimeListElement

This element renders a drop-down with time options in the given minute interval. This field inherits from `DropDownElement`.

```php
$element = new TimeListElement(
	new FormField('time')
);

$element
	->setInterval(30) 	// build time options with 30 minutes interval
	->enableNone() 		// add "none" option
	;

// attach to FormView	

// - None
// - 00:00
// - 00:30
// - 01:00
// - 01:30
// - ...
// - ...
// - ...
```

### DateListElement

This element renders a drop-down with date options starting from a given start date for a set number of days. This field inherits from `DropDownElement`.

```php
$element = new DateListElement(
	new FormField('date')
);

$element
	->setFormatOptionLabel('D, d.m.Y') 	// label for each option; e.g. Sat, 01.04.2017
	->setFormatOptionValue('Y-m-d') 	// value for each option; e.g. 2017-04-01
	->setStartingDate('2017-04-01') 	// set starting date; YYYY-MM-DD or unix time stamp
	->setDays(7) 						// build date options for n days from given start date
	->enableNone() 						// add "none" option
	;

// attach to FormView	

// - None
// - Sat, 01.04.2017
// - Sun, 02.04.2017
// - Mon, 03.04.2017
// - Tues, 04.04.2017
// - ...
// - ...
// - ...
```

### DateCalendarElement

We can also implement a proper calendar which is build on top of a [calendar extension](https://github.com/mdehoog/Semantic-UI-Calendar) for Semantic-UI. [Here](https://jsbin.com/ruqakehefa/1/edit?html,js,output)  are a couple of the look & feel of this extension. We are also making use of [momentjs](https://github.com/moment/moment/) for rendering the actual dates while respecting locale information.

```php
$element = new DateCalendarElement(
	new FormField('date')
);

$calendar
	->setLabel('Date')
	->dateOnly() 					// only show dates
	->setDateFormat('DD/MM/YYYY') 	// format of the selected date; e.g. 01/04/2017

// attach to FormView	
```

__Date/Time options:__ You can see that there is an option `dateOnly()` which limits the calendar to let the user only select a date. By default the user is asked for a date/time combination. Related options are:

- `timeOnly()`
- `monthOnly()`
- `yearOnly()`

__Format options:__ Next to `setDateFormat()` there are also the following format options: `setTimeFormat()` and `setDateTimeFormat()`.

__Defining a range:__ It is also possible to two have two calendars being directly related so that the user can define a specific date range:

```php
//
// start date range
//

$startDateElement = (new DateCalendarElement(new FormField('startDate')))
	->setLabel('Start date')
	->dateOnly()
	->setDateFormat('DD/MM/YYYY')
	;

//
// end date range
//

$endDateElement = new DateCalendarElement(
	new FormField('endDate'),
	$startDateElement // pass in related DateCalendarElement instance
);

$endDateElement
	->setLabel('End date')
	->dateOnly()
	->setDateFormat('DD/MM/YYYY')
	;
```

### DropDownApiElement

This element enables you to send a search request against a defined API and to display its results. This can work with any API since handling the response is up to you. Out of the box we support [Algolia place search](https://community.algolia.com/places/) and [Semantic-UI's API response handling]().

```php
$jsOptions = (new AlgoliaPlacesApiJs())
	->setType(AlgoliaPlacesApiJs::TYPE_CITY)
	;

$cityElement = new DropDownApiElement(new FormField('city'), $jsOptions);

$cityElement
	->enableMultiple()
	->setLabel('City')
	->setDescription('Search for a city')
	;
```

### ImageUploadElement

This element handles the client side of an image upload for you.

```php
$imageElement = new ImageUploadElement(new FormField('urlImage'));

$imageElement
    ->setUploadUrl('/upload/images') // URL which will receive a POST file upload request
    ;
```

-------------------------------------------------

# 4. Examples
