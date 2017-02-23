# Simplon/Forms

`Simplon/Forms` helps to deal with the hustles of building a form, rendering and/or validating it.
It includes a couple widgets by leveraging the fantastic `Semantic UI` library. 
 
## Quick example 
 
Follow the example below to get an idea:

```php
//
// define fields
//

$fields = new FormFields();
$fields->add(new FormField('name'));
$fields->add((new FormField('email'))->addRule(new EmailRule()));

//
// validation
//

$validator = new FormValidator($_POST);

if($validator->hasBeenSubmitted())
{
    if($validator->validate()->isValid())
    {
    }
}

//
// create form view
//

$view = new FormView();
```