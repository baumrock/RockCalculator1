# RockCalculator

Add a calculator to any Inputfield in the ProcessWire backend.

![img](https://i.imgur.com/IYuYktN.gif)

## Setup

At the moment there is no UI for defining fields that should support the calculator. You have multiple options:

1. Tracy Console

```php
// show rockcalculator and round result to .00
$field = $fields->get('yourfieldname');
$field->set('rockcalculator', 2); // 2 digit precision
$field->save();
```

2. RockMigrations

```php
$rm->setFieldData('yourfield', ['rockcalculator' => 2]);
```

3. Hook buildForm

```php
$wire->addHookAfter("ProcessPageEdit::buildForm", function($event) {
  $form = $event->return;
  $page = $event->process->getPage(); // edited page
  if($page->template !== 'yourpagetemplate') return;
  if($f = $form->get('yourfield1')) $f->rockcalculator = 2;
  if($f = $form->get('yourfield2')) $f->rockcalculator = 2;
  if($f = $form->get('yourfield3')) $f->rockcalculator = 2;
});
```

# License

See license of math.js here: https://github.com/josdejong/mathjs/blob/develop/LICENSE
