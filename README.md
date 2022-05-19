# Laravel Alert

[![Tests](https://github.com/SynergiTech/laravel-alert/actions/workflows/test.yml/badge.svg)](https://github.com/SynergiTech/laravel-alert/actions/workflows/test.yml)

Inspired by [Easy Sweet Alert Messages for Laravel](https://github.com/uxweb/sweet-alert), this package provides a way of constructing alerts for the UI of your app.

Out of the box, this package supports [SweetAlert2](https://sweetalert2.github.io/) but its output can be tailored to support other plugins if you wish to create toasts alongside your SweetAlerts for example.

## Installation

```
composer require synergitech/laravel-alert
```

Laravel should be able to automatically detect the package and include it.

You should also make sure you have appropriately installed SweetAlert2 and/or any other notification package into your apps UI.

## Quick Start

You can use the facade or the helper function in your app to generate alerts containing a simple title, text, and type element.

```php
use Alert;

...

Alert::message('Message', 'Optional Title');
Alert::info('Info Message', 'Optional Title');
Alert::success('Success Message', 'Optional Title');
Alert::error('Error Message', 'Optional Title');
Alert::warning('Warning Message', 'Optional Title');
```

```php
alert()->message('Message', 'Optional Title');
alert()->info('Info Message', 'Optional Title');
alert()->success('Success Message', 'Optional Title');
alert()->error('Error Message', 'Optional Title');
alert()->warning('Warning Message', 'Optional Title');
```

To actually display the alert you will need to include a short snippet of code in your main view file. This package only outputs complete JSON objects into your session.

**Please note** this data is _put_ into your session so you have to _pull_ it to clear it out of the session. This allows you to not lose alerts from background ajax calls.

### Blade
```php
@if (Session::has('alert.sweetalert'))
    <script>
        Swal.fire({!! Session::pull('alert.sweetalert') !!});
    </script>
@endif
```

### Twig
```twig
{% if session_has('alert.sweetalert') %}
    <script>
        Swal.fire({{ session_pull('alert.sweetalert')|raw }});
    </script>
{% endif %}
```

## Advanced Usage

This package provides a builder-like syntax allowing you to customise the alert further. The following examples provide identical output.

```php
alert()->warning('You need to complete extra fields', 'Unable to submit');

\Alert::warning('You need to complete extra fields')
    ->title('Unable to submit');

alert()->type('warning')
    ->message('You need to complete extra fields')
    ->title('Unable to submit');
```

You can customise the fields available by publishing the config to your application. Read the config file for more details.

```sh
php artisan vendor:publish --provider="SynergiTech\Alert\ServiceProvider"
```

### Multiple Output Types

If you specify a second output, for example to create a toast option, this library will always add **both** to the session _unless_ you ask for a specific type (v2 only).

```php
// in your controller

alert()->output('toast')->info('Please check your settings.');

// OR

alert()->as('toast')->info('Please check your settings.');
```

Example config:

```php
// config/alert.php

'output' => [
    'sweetalert' => [
        'title' => 'title',
        'text' => 'text',
        'icon' => 'type',
    ],
    'toast' => [
        'text' => 'text',
        'icon' => 'type',
    ],
],
```
