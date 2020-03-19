# psr7-sessions-php

Implementation of a PSR-7 and PSR-15 compatible middleware that enables sessions in PSR-7 based applications.

## Requirements

This branch requires PHP 7.0 or up with built-in extensions:
- JSON
- PCRE

Followed packages are required too:
- [`sulaco-tech/base58`](https://github.com/sulaco-tech/base58-php)

## Status

This code is NOT fully tested. The first release coming soon. Please wait.

## Usage

You can use the `SulacoTech\PSR7Sessions\SessionMiddleware` in any PSR-15 compatible middleware.

In a [`slim/slim`](https://github.com/slimphp/Slim) application, this would look like following:
```php
use \Slim\Factory\AppFactory;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \SulacoTech\PSR7Sessions\SessionMiddleware;
use \SulacoTech\PSR7Sessions\SessionFileStorage;
use \SulacoTech\PSR7Sessions\SessionFileStorageConfiguration;

// create application
$app = AppFactory::create();

// prepare configuration
$sessionsDirectory = __DIR__ . '/../tmp/sessions';
$sessionName = 'example';
$sessionsExpirationTime = 300; // in seconds
$config = new SessionFileStorageConfiguration($sessionsDirectory, $sessionName, $sessionsExpirationTime);

// create storage with some configuration
$sessionStorage = new SessionFileStorage($config);

// call garbage collector
$sessionStorage->gc();

// create and add middleware
$app->add(new SessionMiddleware($sessionStorage));

// basic example
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {

	// get session
	$session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

	// read and update session's data
	$num = $session->get('counter', 0);
	$session['counter'] = ++ $num;

	// make a response
    $response = $response
		->withHeader('Content-Type', 'text/plain; charset=utf-8');
	$response
		->getBody()->write("Hello, {$args['name']}! This page is visited $num times.");
    return $response;
});

// run application
$app->run();```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.