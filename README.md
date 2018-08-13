Laravel MultiMenu widget
==============

1 Introduction
----------------------------

[![Latest Stable Version](https://poser.pugx.org/itstructure/laravel-multi-menu/v/stable)](https://packagist.org/packages/itstructure/laravel-multi-menu)
[![Latest Unstable Version](https://poser.pugx.org/itstructure/laravel-multi-menu/v/unstable)](https://packagist.org/packages/itstructure/laravel-multi-menu)
[![License](https://poser.pugx.org/itstructure/laravel-multi-menu/license)](https://packagist.org/packages/itstructure/laravel-multi-menu)
[![Total Downloads](https://poser.pugx.org/itstructure/laravel-multi-menu/downloads)](https://packagist.org/packages/itstructure/laravel-multi-menu)
[![Build Status](https://scrutinizer-ci.com/g/itstructure/laravel-multi-menu/badges/build.png?b=master)](https://scrutinizer-ci.com/g/itstructure/laravel-multi-menu/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/itstructure/laravel-multi-menu/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/itstructure/laravel-multi-menu/?branch=master)

This widget is to display a multi level menu. There can be nested submenus. Used for Laravel framework.

The widget uses data from the **database**, in which there are, in addition to the primary keys, also the parent keys.

Data from the **database** is taken from a model, which instance of **Illuminate\Database\Eloquent\Model**.

2 Dependencies
----------------------------

- php >= 7.1
- composer

3 Installation
----------------------------

### 3.1 General from remote repository

Via composer:

```composer require "itstructure/laravel-multi-menu": "^1.0.2"```

or in section **require** of composer.json file set the following:
```
"require": {
    "itstructure/laravel-multi-menu": "^1.0.2"
}
```
and command ```composer install```, if you install laravel project extensions first,

or command ```composer update```, if all laravel project extensions are already installed.

### 3.2 If you are testing this package from local server directory

In application ```composer.json``` file set the repository, like in example:

```
"repositories": [
    {
        "type": "path",
        "url": "../laravel-multi-menu",
        "options": {
            "symlink": true
        }
    }
],
```

Here,

**laravel-multi-menu** - directory name, which hase the same directory level like application and contains multi menu package.

Then run command:

```composer require itstructure/laravel-multi-menu:dev-master --prefer-source```

### 3.3 App config

Add to application ```config/app.php``` file to section **providers**: ```Itstructure\MultiMenu\MultiMenuServiceProvider::class```

### 3.4 Publish in application

Run command:

```php artisan vendor:publish --provider="Itstructure\MultiMenu\MultiMenuServiceProvider"```

## 4 Usage

### 4.1 Usage in view template

To run widget in blade view template:

```blade
{!! app('multiMenuWidget')->run($models, $additionData) !!}
```

Here,

**$models** - must be instance of ```Illuminate\Database\Eloquent\Collection```

**$additionData** - addition cross cutting data for all nesting levels. Can be empty or not defined.

Example of custom changed view ```item.blade```:
```html
<li><a href="/catalog/{!! $data->id !!}">{!! $data->title !!}</a></li>
```

### 4.2 Config simple

File ```/config/multiMenu.php```:
```php
return [
    'primaryKeyName' => 'id',
    'parentKeyName' => 'parentId',
    'mainTemplate' => 'main',
    'itemTemplate' => 'item',
];
```

### 4.3 Config for nesting levels

File ```/config/multiMenu.php```:
```php
return [
    'primaryKeyName' => 'id',
    'parentKeyName' => 'parentId',
    'mainTemplate' => [
        'levels' => [
            'main',
            'main2'
        ]
    ],
    'itemTemplate' => [
        'levels' => [
            'item',
            'item2'
        ]
    ],
];
```

### 4.4 Database table structure example

```Table "catalogs"```

```php
| id  | parentId |   title  | ... |
|-----|----------|----------|-----|
|  1  |   NULL   | catalog1 | ... |
|  2  |   NULL   | catalog2 | ... |
|  3  |     1    | catalog3 | ... |
|  4  |     1    | catalog4 | ... |
|  5  |     4    | catalog5 | ... |
|  6  |     4    | catalog6 | ... |
|  7  |     3    | catalog7 | ... |
|  8  |     3    | catalog8 | ... |
|  9  |   NULL   | catalog9 | ... |
|  10 |   NULL   | catalog10| ... |
| ... |    ...   |    ...   | ... |
```

## 5 Prevention of collisions

### 5.1 Before save model

To prevent the entry of the wrong parent identifier (for example, the new number that is a child in a subordinate chain of nested records):

Use ```checkNewParentId(Model $mainModel, int $newParentId... e.t.c)```

Here are the required parameters:

**$mainModel** - current model record, in which the parent id will be changed for new value.

**$newParentId** - new parent id, which must be verified.

### 5.2 After delete model

To prevent breaks in the chain of subject submissions:

Use ```afterDeleteMainModel(Model $mainModel... e.t.c)```

Here is the required parameter:

**$mainModel** - deleted model record.

This function will rebuild the chain.

License
----------------------------

Copyright © 2018 Andrey Girnik girnikandrey@gmail.com.

Licensed under the [MIT license](http://opensource.org/licenses/MIT). See LICENSE.txt for details.
