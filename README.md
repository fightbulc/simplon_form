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
3. [__View elements__](#3-view-elements)  
3.1 [Input text](#31-input-text)  
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

# 3. View elements

### CheckboxElement
### DateCalendarElement
### DateListElement
### DropDownApiElement
### DropDownElement
### CheckboxElement
### CheckboxElement
### CheckboxElement
### CheckboxElement
### CheckboxElement
### CheckboxElement

-------------------------------------------------

# 4. Examples
