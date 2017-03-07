# Form fields

`Form fields` are used to define a set of fields and to apply their initial values if available.
A field carries only the most essential data to describe it. We do not define if a field is a
drop-down or text-field because we wanna be able to describe and validate not only a visible form
but also data we receive via an api endpoint.
 
## Quick example 
 
A simple set of a `name` and `email` field. The latter has an `email rule` attached which requires the field to be a valid email address.

```php
$fields = new FormFields();
$fields->add(new FormField('name'));
$fields->add((new FormField('email'))->addRule(new EmailRule()));
```

Now lets run these fields against our `form validator` with a payload we just received:

```php
```
 
## Options