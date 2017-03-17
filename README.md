# Simplon/Forms

`Simplon/Forms` helps to validate data and, if needed, to build a form view with a couple of widgets by leveraging `Semantic-UI` library.

-------------------------------------------------

1. [__Quick example__](#1-quick-example)  
1.1 [Fields](#11-fields)  
1.2 [Validation](#12-validation)  
1.3 [View](#13-view)  
2. [__Fields__](#2-fields)  
2.1 [Rules](#21-rules)  
2.2 [Filters](#22-filters)  
3. [__View elements__](#3-view-elements)  
3.1 [Input text](#31-input-text)  
4. [__Examples__](#4-examples)  
4.1 [Place search](#41-place-search)  

-------------------------------------------------

# 1. Quick example
 
## 1.1 Fields

In order to validate data we need to create at least one field which can hold any number of rules to define its validity. A field can also hold any number of filters which will be applied to the field value.

### A field example

```php
//
// easiest setup
//

(new FormFields())->add(new FormField('email'));

//
// let's trim our field value
//

(new FormFields())->add(
	(new FormField('email'))->addFilter(new TrimFilter())
);

//
// and let's make sure that we have an email address
// 

(new FormFields())->add(
	(new FormField('email'))
		->addFilter(new TrimFilter())
		->addRule(new EmailRule())
);
```

### Pre-populating your fields

We can pre-poluate our fields with data we already have - not `request` data:

```php
//
// our fields
//

$fields = (new FormFields())->add(
	(new FormField('email'))
		->addFilter(new TrimFilter())
		->addRule(new EmailRule())
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
	(new FormField('name'))->addFilter(new TrimFilter())
);

$fields->add(
	(new FormField('email'))
		->addFilter(new TrimFilter())
		->addRule(new EmailRule())
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

$view = (new FormView())->addBlock($block);
```

We could set much more options but that should do it for now. Let's pass on our view to a template.
For the following example we assume that we have access to the `$view` variable. We simply pass on a form template to the `FormView::render` method and include all `core assets`:

```php
<?php
/**
 * @var FormView $view
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

    <link href="../../assets/vendor/semantic-ui/2.2.x/semantic.min.css" rel="stylesheet">
    <link href="../../assets/vendor/simplon-form/base.min.css" rel="stylesheet">

    <style type="text/css">
        body {
            padding: 0;
            background: #fff;
        }
    </style>
</head>
<body>
    <div class="ui container">
        <?= $view->render(__DIR__ . '/form.phtml') ?>
    </div>

    <script src="../../assets/vendor/jquery/3.1.x/jquery.min.js"></script>
    <script src="../../assets/vendor/semantic-ui/2.2.x/semantic.min.js"></script>
    <script src="../../assets/vendor/simplon-form/base.min.js"></script>

    <?= $view->renderFieldAssets() ?>
</body>
</html>
```
We separate our main template from the actual form template so that we can automatically render the required `<form></form>` tags with all required attributes at the beginning and the end of the template. We also inject the `$view` instance as `$formView` variable.

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

-------------------------------------------------

# 2. Fields

-------------------------------------------------

# 3. View elements

-------------------------------------------------

# 4. Examples
