# RockCalculator

Add a calculator to any Inputfield in the ProcessWire backend.

![img](https://i.imgur.com/IYuYktN.gif)

## Setup

At the moment there is no UI for defining fields that should support the calculator. You have two options:

1. RockMigrations

```php
// show rockcalculator and round result to .00
$rm->setFieldData('yourfield', ['rockcalculator' => 2]);
```

2. Hook buildForm

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
