Laravel MultiMenu widget
==============

1 Introduction
----------------------------

**MultiMenu** -- package for the Laravel 5 framework.

2 Dependencies
----------------------------

- php >= 7.1
- composer

3 Installation
----------------------------

### 3.1 General from remote repository

Via composer:

```composer require "itstructure/laravel-multi-menu": "^1.0.0"```

or in section **require** of composer.json file set the following:
```
"require": {
    "itstructure/laravel-multi-menu": "^1.0.0"
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
        "url": "../laravel-multi-menu"
    }
],
```

Here,

**laravel-multi-menu** - directory name, which hase the same directory level like application and contains multi menu package.

Then run command:

```composer require itstructure/laravel-multi-menu:dev-master --prefer-source```

### 3.3 Publish in application

Run command:

```php artisan vendor:publish --provider="itstructure\MultiMenu\MultiMenuServiceProvider"```

## 4 Usage

### 4.1 Usage in view template

Base application config must be like in example below:

```blade
{!! app('multiMenuWidget')->run($models) !!}
```

### 4.2 Database table structure example

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

License
----------------------------

Copyright © 2018 Andrey Girnik girnikandrey@gmail.com.

Licensed under the [MIT license](http://opensource.org/licenses/MIT). See LICENSE.txt for details.
