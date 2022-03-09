# Laravel MultiMenu widget

## 1 Introduction

[![Latest Stable Version](https://poser.pugx.org/itstructure/laravel-multi-menu/v/stable)](https://packagist.org/packages/itstructure/laravel-multi-menu)
[![Latest Unstable Version](https://poser.pugx.org/itstructure/laravel-multi-menu/v/unstable)](https://packagist.org/packages/itstructure/laravel-multi-menu)
[![License](https://poser.pugx.org/itstructure/laravel-multi-menu/license)](https://packagist.org/packages/itstructure/laravel-multi-menu)
[![Total Downloads](https://poser.pugx.org/itstructure/laravel-multi-menu/downloads)](https://packagist.org/packages/itstructure/laravel-multi-menu)
[![Build Status](https://scrutinizer-ci.com/g/itstructure/laravel-multi-menu/badges/build.png?b=master)](https://scrutinizer-ci.com/g/itstructure/laravel-multi-menu/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/itstructure/laravel-multi-menu/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/itstructure/laravel-multi-menu/?branch=master)

This widget is to display a multi level menu. There can be nested sub-menus. Used for Laravel framework.

The widget uses data from the **database**, in which there are, in addition to the primary keys, also the parent keys.

Data from the **database** is taken from a model and must be instance of **Illuminate\Database\Eloquent\Collection**.

![Multi level menu example scheme](https://github.com/itstructure/laravel-multi-menu/blob/master/ML_menu_en.jpg)

## 2 Requirements

- laravel 5.5+ | 6+ | 7+ | 8+ | 9+
- php >= 7.1.0
- composer

## 3 Installation

### 3.1 General from remote repository

Via composer:

`composer require itstructure/laravel-multi-menu "~2.0.6"`

### 3.2 If you are testing this package from local server directory

In application `composer.json` file set the repository, like in example:

```json
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

**../laravel-multi-menu** - directory name, which has the same directory level as application and contains multi menu package.

Then run command:

`composer require itstructure/laravel-multi-menu:dev-master --prefer-source`

### 3.3 App config

Add to application `config/app.php` file to section **providers**:

`Itstructure\MultiMenu\MultiMenuServiceProvider::class`

### 3.4 Publish in application

- To publish all parts run command:
    
    `php artisan multimenu:publish`

- To publish only config run command:

    `php artisan multimenu:publish --only=config`
    
    It stores `multimenu.php` config file to `config` folder.
        
- To publish only views run command:
            
    `php artisan multimenu:publish --only=views`
    
    It stores view files to `resources/views/vendor/multimenu` folder.

- Else you can use `--force` argument to rewrite already published files.

Or another variant:

`php artisan vendor:publish --provider="Itstructure\MultiMenu\MultiMenuServiceProvider"`

## 4 Usage

### 4.1 Simple variant

#### Config part

```php
return [
    'primaryKeyName' => 'id', // Editable
    'parentKeyName' => 'parent_id', // Editable
    'mainTemplate' => 'main', // Editable
    'itemTemplate' => 'item', // Editable
];
```

#### View template part

```php
@php
$multiOptions = [ // Editable
    'config' => config('multimenu'),
    'data' => $pages
];
@endphp
```

```php
@multiMenu($multiOptions)
```
    
Here, `$pages` - is from controller part, for example `$pages = Page::all();`. Must be instance of `Illuminate\Database\Eloquent\Collection`.

### 4.2 Addition config options and data

#### Config part

There is an example to set item blade templates for 3 levels:

```php
return [
    'primaryKeyName' => 'id',
    'parentKeyName' => 'parent_id',
    'mainTemplate' => 'main',
    'itemTemplate' => [
        'levels' => [
            'item',
            'item',
            'item_new',
        ]
    ],
];
```

You can set `mainTemplate` by analogy.

#### Blade templates

Example of a custom changed blade template file `item.blade`:

```php
<li>
    <a href="{{ $data->icon }}">
        Initial item Id {{ $data->id }} {{ isset($addition) ? ' | ' . $addition : '' }}
    </a>
</li>
```

Example of a custom changed blade template file `item_new.blade`:

```php
<li>
    <a href="{{ $data->icon }}" style="color: green; font-weight: bold;">
        New item Id {{ $data->id }} {{ isset($addition) ? ' | ' . $addition : '' }}
    </a>
</li>
```

#### Addition data

Example in a template file:

```php
@php
$multiOptions = [
    'config' => config('multimenu'),
    'data' => $pages,
    'additionData' => [
        'levels' => [
            0 => [],
            1 => ['addition' => 'addition string']
        ]
    ]
];
@endphp
```

```php
@multiMenu($multiOptions)
```

### 4.3 Database table structure example

`Table "catalogs"`

    | id  | parent_id |   title    | ... |
    |-----|-----------|------------|-----|
    |  1  |   NULL    |   item 1   | ... |
    |  2  |   NULL    |   item 2   | ... |
    |  3  |   NULL    |   item 3   | ... |
    |  4  |   NULL    |   item 4   | ... |
    |  5  |   NULL    |   item 5   | ... |
    |  6  |     2     |  item 2.1  | ... |
    |  7  |     2     |  item 2.2  | ... |
    |  8  |     7     | item 2.2.1 | ... |
    |  9  |     7     | item 2.2.2 | ... |
    |  10 |     7     | item 2.2.3 | ... |
    | ... |    ...    |     ...    | ... |


## 5 Prevention of collisions

### 5.1 Before save model

To prevent the entry of the wrong parent identifier (for example, the new number that is a child in a subordinate chain of nested records):

Use static method `checkNewParentId(Model $mainModel, int $newParentId... e.t.c)`

Here are the required parameters:

**$mainModel** - current model record, in which the parent id will be changed for new value.

**$newParentId** - new parent id, which must be verified.

### 5.2 After delete model

To prevent breaks in the chain of subject submissions:

Use static method `afterDeleteMainModel(Model $mainModel... e.t.c)`

Here is the required parameter:

**$mainModel** - deleted model record.

This function will rebuild the chain.

## License

Copyright © 2018-2022 Andrey Girnik girnikandrey@gmail.com.

Licensed under the [MIT license](http://opensource.org/licenses/MIT). See LICENSE.txt for details.
