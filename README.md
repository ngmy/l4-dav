# L4Dav

[![Build Status](https://travis-ci.org/ngmy/l4-dav.png?branch=master)](https://travis-ci.org/ngmy/l4-dav)
[![Coverage Status](https://coveralls.io/repos/ngmy/l4-dav/badge.png?branch=master)](https://coveralls.io/r/ngmy/l4-dav?branch=master)

A simple WebDAV client library for Laravel 4.

## Requirements

The L4Dav has the following requirements:

  * PHP 5.3+

  * Laravel 4.0+

## Dependencies

The L4Dav has the following dependencies:

  * [anlutro/php-curl](https://github.com/anlutro/php-curl)

## Installation

Add the package to your `composer.json` and run `composer update`:

```json
{
    "require": {
        "ngmy/l4-dav": "dev-master"
    }
}
```

Add the following to the list of service providers in `app/config/app.php`:

```php
'Ngmy\L4Dav\L4DavServiceProvider',
```

Add the following to the list of class aliases in `app/config/app.php`:

```php
'L4Dav' => 'Ngmy\L4Dav\Facades\L4Dav',
```

## Configuration

After installing, you can publish the package's configuration file into your application, by running the following command:

```
php artisan config:publish ngmy/l4-dav
```

This will publish the config file to `app/config/packages/ngmy/l4-dav/config.php` where you modify the package configuration.

## Examples

### Basic Usage

**Download a file from the WebDAV server**

```php
L4Dav::get('path/to/remote/file', '/path/to/local/file');
```

**Upload a file to the WebDAV server**

```php
L4Dav::put('/path/to/local/file', 'path/to/remote/file');
```

**Delete a file on the WebDAV server**

```php
L4Dav::delete('path/to/remote/file');
```

**Copy a file on the WebDAV server**

```php
L4Dav::copy('path/to/source/file', 'path/to/dest/file');
```

**Rename a file on the WebDAV server**

```php
L4Dav::move('path/to/source/file', 'path/to/dest/file');
```

**Make a directory on the WebDAV server**

```php
L4Dav::mkdir('path/to/remote/directory/');
```

### Get Response

**Get the status code**
```php
$response = L4Dav::put('/path/to/local/file', 'path/to/remote/file');
$response->getStatus();
```

**Get the status message**
```php
$response = L4Dav::put('/path/to/local/file', 'path/to/remote/file');
$response->getMessage();
```

**Get the response body**
```php
$response = L4Dav::put('/path/to/local/file', 'path/to/remote/file');
$response->getBody();
```
