# PHP WebDAV client
[![Latest Stable Version](https://poser.pugx.org/ngmy/l4-dav/v)](//packagist.org/packages/ngmy/l4-dav)
[![Total Downloads](https://poser.pugx.org/ngmy/l4-dav/downloads)](//packagist.org/packages/ngmy/l4-dav)
[![Latest Unstable Version](https://poser.pugx.org/ngmy/l4-dav/v/unstable)](//packagist.org/packages/ngmy/l4-dav)
[![License](https://poser.pugx.org/ngmy/l4-dav/license)](//packagist.org/packages/ngmy/l4-dav)
[![composer.lock](https://poser.pugx.org/ngmy/l4-dav/composerlock)](//packagist.org/packages/ngmy/l4-dav)
[![PHP CI](https://github.com/ngmy/l4-dav/actions/workflows/php.yml/badge.svg)](https://github.com/ngmy/l4-dav/actions/workflows/php.yml)
[![Coverage Status](https://coveralls.io/repos/github/ngmy/l4-dav/badge.svg?branch=master)](https://coveralls.io/github/ngmy/l4-dav?branch=master)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)

The PHP WebDAV client that makes it easy to send WebDAV requests and trivial to integrate with web services.

- Simple and object-oriented interface for sending WebDAV requests
- Can use any implementation of the PSR-7 UriInterface to specify the request URL
- Response class wraps any implementation of the PSR-7 ResponseInterface, and itself implements the PSR-7 ResponseInterface
- Uses PSR-7 interfaces for requests, responses, and streams. This allows you to utilize other PSR-7 compatible libraries with the PHP WebDAV client
- Uses PSR-18 interface for the HTTP client. This allows you to utilize other PSR-18 compatible libraries with the PHP WebDAV client
- Uses the [cURL client](https://github.com/php-http/curl-client) for the default HTTP client. This allows you to fine control of the HTTP client using the [cURL option](https://www.php.net/manual/en/function.curl-setopt.php)
- Streaming large uploads, streaming large downloads via PSR-7 responses and streams

```php
$options = (new WebDavClientOptionsBuilder())
    ->setBaseUrl('https://webdav.example.com')
    ->setUserName('username');
    ->setPassword('password')
    ->build();
$client = new WebDavClient($options);

// PUT
$parameters = (new PutParameters())
    ->setSourcePath('/path/to/file')
    ->build();
$client->put('/file', $parameters);

// GET
$response = $client->get('/file');

// Download file
file_put_contents($path, $response->getBody());

// Streaming file
$response->getBody()->rewind();
$fh = fopen($path, 'w');
$stream = $response->getBody();
while (!$stream->eof()) {
    fwrite($fh, $stream->read(2048));
}
fclose($fh);
```

## Supported WebDAV Features
The PHP WebDAV client supports the following WebDAV features as defined in [RFC 4918](https://tools.ietf.org/html/rfc4918):

- [x] PUT - Stores the resource
- [x] GET - Retrieves the resource
- [x] HEAD - Retrieves the header information of the resource
- [x] DELETE - Deletes the resource
- [x] MKCOL - Creates a new collection
- [x] COPY - Creates a duplicate of the resource
- [x] MOVE - Moves the resource
- [x] PROPFIND - Retrieves properties
- [x] PROPPATCH - Set and/or removes properties
- [ ] LOCK - Locks the resource
- [ ] UNLOCK - Unlocks the resource

## Requirements
The PHP WebDAV client has the following requirements:

* PHP >= 7.3
* DOM PHP extension
* libxml PHP library
* [PSR-17 implementation](https://packagist.org/providers/psr/http-factory-implementation)
* [PSR-18 implementation](https://packagist.org/providers/psr/http-client-implementation)

## Installation
Execute the Composer `require` command:
```console
composer require ngmy/webdav-client
```

## Documentation
Please see the [documentation](https://ngmy.github.io/l4-dav/api/).

## License
The PHP WebDAV client is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
