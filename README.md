# PHP WebDAV client

[![Latest Stable Version](https://poser.pugx.org/ngmy/l4-dav/v)](//packagist.org/packages/ngmy/l4-dav)
[![Total Downloads](https://poser.pugx.org/ngmy/l4-dav/downloads)](//packagist.org/packages/ngmy/l4-dav)
[![Latest Unstable Version](https://poser.pugx.org/ngmy/l4-dav/v/unstable)](//packagist.org/packages/ngmy/l4-dav)
[![License](https://poser.pugx.org/ngmy/l4-dav/license)](//packagist.org/packages/ngmy/l4-dav)
[![composer.lock](https://poser.pugx.org/ngmy/l4-dav/composerlock)](//packagist.org/packages/ngmy/l4-dav)<br>
[![PHP CI](https://github.com/ngmy/l4-dav/workflows/PHP%20CI/badge.svg)](https://github.com/ngmy/l4-dav/actions?query=workflow%3A%22PHP+CI%22)
[![Coverage Status](https://coveralls.io/repos/github/ngmy/l4-dav/badge.svg?branch=master)](https://coveralls.io/github/ngmy/l4-dav?branch=master)

A PHP WebDAV client.

## Supported WebDAV Methods

- [x] GET
- [x] PUT
- [x] DELETE
- [x] COPY
- [x] MOVE
- [x] MKCOL
- [x] HEAD
- [x] PROPFIND
- [x] PROPPATCH
- [ ] LOCK
- [ ] UNLOCK

## Requirements

The L4Dav has the following requirements:

* PHP >= 7.3
* Laravel >= 6.0

## Installation

Add the package to your `composer.json` and run `composer update`:

```console
composer require ngmy/l4-dav
```

## Examples

### Basic Usage
#### Download a file from the WebDAV server

```php
L4Dav::download('path/to/remote/file', '/path/to/local/file');
```

#### Upload a file to the WebDAV server

```php
L4Dav::upload('/path/to/local/file', 'path/to/remote/file');
```

#### Delete a file on the WebDAV server

```php
L4Dav::delete('path/to/remote/file');
```

#### Copy a file on the WebDAV server

```php
L4Dav::copy('path/to/source/file', 'path/to/dest/file');
```

#### Rename a file on the WebDAV server

```php
L4Dav::move('path/to/source/file', 'path/to/dest/file');
```

#### Make a directory on the WebDAV server

```php
L4Dav::makeDirectory('path/to/remote/directory/');
```

#### Check the existence of a directory on the WebDAV server

```php
L4Dav::exists('path/to/remote/directory/');
```

#### List contents of a directory on the WebDAV server

```php
L4Dav::list('path/to/remote/directory/');
```

### Get Response
#### Get the status code
```php
$response = L4Dav::upload('/path/to/local/file', 'path/to/remote/file');
$response->getStatus();
```

#### Get the status message
```php
$response = L4Dav::upload('/path/to/local/file', 'path/to/remote/file');
$response->getMessage();
```

#### Get the response body
```php
$response = L4Dav::upload('/path/to/local/file', 'path/to/remote/file');
$response->getBody();
```
