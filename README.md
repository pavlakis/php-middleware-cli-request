[![Build Status](https://travis-ci.org/pavlakis/php-middleware-cli-request.svg)](https://travis-ci.org/pavlakis/php-middlware-cli-request)
[![Total Downloads](https://img.shields.io/packagist/dt/pavlakis/php-middleware-cli-request.svg)](https://packagist.org/packages/pavlakis/php-middlware-cli-request)
[![Latest Stable Version](https://img.shields.io/packagist/v/pavlakis/php-middleware-cli-request.svg)](https://packagist.org/packages/pavlakis/php-middlware-cli-request)

# A PHP CLI Request Middleware

### A copy from [slim-cli](https://github.com/pavlakis/slim-cli) of the same author.

This middleware will transform a CLI call into a GET Request.

### Add it with composer
```
composer require pavlakis/php-middleware-cli-request
```

### Pass the parameters in this order
`route / method / query string`
```php
php public/index.php /status GET event=true
```

### Add it in the middleware section of your application
```
$app->add(new \Pavlakis\Middleware\Cli\Request());
```

### Pass a route to test it with

```php

$app->get('/status', 'PHPMinds\Action\EventStatusAction:dispatch')
    ->setName('status');

```

### Check you're only using a CLI call

```php
final class EventStatusAction
{
    ...

    public function dispatch(Request $request, Response $response, $args)
    {

        // ONLY WHEN CALLED THROUGH CLI
        if (PHP_SAPI !== 'cli') {
            return $response->withStatus(404)->withHeader('Location', '/404');
        }

        if (!$request->getParam('event')) {
            return $response->withStatus(404)->withHeader('Location', '/404');
        }

        ...

    }

}
```

Or we can use a [PHP Server Interface (SAPI) Middleware](https://github.com/pavlakis/php-server-interface-middleware) to do the SAPI check adding by adding it to a route:

```php
// By default returns a 403 if SAPI not part of the whitelist
$app->get('/status', 'PHPMinds\Action\EventStatusAction:dispatch')
    ->add(new Pavlakis\Middleware\Server\Sapi(["cli"]))
```
