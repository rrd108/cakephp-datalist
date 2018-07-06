# Datalist plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install this:

```
composer require rrd108/cakephp-datalist
```

/config/bootstrap.php
```php
Plugin::load('Datalist'); 
```

/src/View/AppView.php
```php
public function initialize() 
{ 
    parent::initialize(); 
    $this->loadHelper('Form', [
        'templates' => 'Datalist.form-templates',
        'widgets' => [
            'datalist' => ['Datalist\View\Widget\DatalistWidget']
        ]
    ]);
} 
```
    
/src/Model/Table/GrantsTable.php

```php
public function initialize(array $config)
{
    parent::initialize($config);

    $this->setTable('grants');
    $this->setDisplayField('shortname');
    $this->setPrimaryKey('id');

    $this->addBehavior('Datalist.Datalist', ['Issuers' => 'name', 'Companies' => 'name']);
}
```
