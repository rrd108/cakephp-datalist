[![Build Status](https://travis-ci.org/rrd108/cakephp-datalist.svg?branch=master)](https://travis-ci.org/rrd108/cakephp-datalist)
[![codecov](https://codecov.io/gh/rrd108/cakephp-datalist/branch/master/graph/badge.svg)](https://codecov.io/gh/rrd108/cakephp-datalist)


# Datalist plugin for CakePHP

Many of the HTML 5 new widgets are automatically supported by CakePHP.
Unfortunatelly [datalist](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/datalist) is not supported by default.

With the _datalist_ HTML 5 element you can create a widget similar to _select_ elements, but with _datalist_ you are not forced to select one of the options, but you can add any new value also.

If you are looking for plain _datalist_ support by a CakePHP plugin you should check
[dereuromark/cakephp-tools](https://github.com/dereuromark/cakephp-tools)

This plugin adds an extra feature on a plain _datalist_. 
If you create a new option, than CakePHP will save the value in the associted model as a new record.

## Backward compatibility

From version 1.0.0 we have renamed the widget, so in the `AppView.php` and in your template files you should use `datalistJs` instead of `datalist` as described below.
Sorry for breaking backward compatibility, but there were only a few installs of the previous versions and as [dereuromark/cakephp-tools](https://github.com/dereuromark/cakephp-tools) implemented plain _datalist_, this was the easiest way to do...

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install the plugin is via composer:

```
composer require rrd108/cakephp-datalist
```

Than you should load the plugin by issuing the following command in your terminal.
```
bin/cake plugin load Datalist
```

As the plugin extends the core HTML widgets you should load the form helper in your `/src/View/AppView.php` file like this:
```php
public function initialize() 
{ 
    parent::initialize(); 
    $this->loadHelper('Form', [
        'templates' => 'Datalist.form-templates',
        'widgets' => [
            'datalistJs' => ['Datalist\View\Widget\DatalistJsWidget']
        ]
    ]);
} 
```

## Usage
    
In any model where you want to use _datalist_ you should add _datalist_ behavior.
For example in your `/src/Model/Table/SkillsTable.php` you should have the following code, where `Languages` should be associated to `SkillsTable` and `name` is the field on what we want to use _datalist_.

```php
public function initialize(array $config)
{
    parent::initialize($config);

    $this->addBehavior(
        'Datalist.Datalist', 
        ['Languages' => 'name']
    );
    
    $this->belongsTo('Languages', [
        'foreignKey' => 'language_id',
        'joinType' => 'INNER'
        ]);
}
```

If you want more datalists you can add more models to the behavior.
```php
$this->addBehavior(
    'Datalist.Datalist', 
    ['Languages' => 'name', 'Countries' => 'country']
);
}
```
Than in your controller you do a simple find operation and set the result to the view.

```php
//src/Controller/SkillsController.php
public function add()
{
    // your controller code
    $languages = $this->Skills->Languages->find('list', ['limit' => 200]);
    $this->set(compact('languages'));
}
```
By this the `$languages` variable is available at `/src/Template/Skills/add.ctp`file.

```php
<?= $this->Form->create($skill) ?>
<?php
    echo $this->Form->control(
        'language_id',
        ['type' => 'datalistJs', 'options' => $languages]
    );
?>
```
The end result should work like a charm and if you do not select one of the options but type in a new one, CakePHP will save it as a new entry in the associated model.
![Alt Text](http://webmania.cc/static/cakephp/datalist.gif)
