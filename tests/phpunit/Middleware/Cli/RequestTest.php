<?php declare(strict_types=1);

/**
 * Pavlakis PHP Middleware CLI Request
 *
 * @link        https://github.com/pavlakis/php-middleware-cli-request
 * @copyright   Copyright © 2017 Antonis Pavlakis
 * @license     https://github.com/pavlakis/php-middleware-cli-request/blob/master/LICENSE (BSD 3-Clause License)
 */
namespace Pavlakis\Tests\Middleware\Cli;

use Pavlakis\Middleware\Cli\Request as CliRequest,
    Psr\Http\Message\RequestInterface,
    Psr\Http\Message\ResponseInterface,
    PHPUnit\Framework\TestCase,
    Slim\Http\Response,
    Slim\Http\Headers,
    Slim\Http\Request,
    Slim\Http\Body,
    Slim\Http\Uri;


class CliRequestTest extends TestCase
{

    public function setUp()
    {
        global $argv;

        $argv[0] = 'cli.php';
        $argv[1] = '/status';
        $argv[2] = 'GET';
        $argv[3] = 'event=true';
    }

    /**
     * Taken from: https://github.com/slimphp/Slim-HttpCache/blob/master/tests/CacheTest.php
     * @return Request
     */
    public function requestFactory()
    {
        $uri = Uri::createFromString('https://example.com:443/foo/bar?abc=123');
        $headers = new Headers();
        $cookies = [];
        $serverParams = [];
        $body = new Body(fopen('php://temp', 'r+'));
        return new Request('GET', $uri, $headers, $cookies, $serverParams, $body);
    }

    public function testCorrectRequestParametersArePassed()
    {
        $req = $this->requestFactory();
        $res = new Response();
        $next = function (Request $req, Response $res) {
            return $res;
        };

        /** @var CliRequest $cliRequest */
        $cliRequest = new CliRequest();

        /** @var  ResponseInterface $res */
        $res = $cliRequest($req, $res, $next);

        static::assertEquals('event=true', $cliRequest->getRequest()->getUri()->getQuery());
    }

    public function testMinimalCorrectRequestParametersArePassed()
    {
        $req = $this->requestFactory();
        $res = new Response();
        $next = function (Request $req, Response $res) {
            return $res;
        };

        unset($GLOBALS['argv'][3]);

        /** @var CliRequest $cliRequest */
        $cliRequest = new CliRequest();

        /** @var  ResponseInterface $res */
        $res = $cliRequest($req, $res, $next);

        static::assertEquals('', $cliRequest->getRequest()->getUri()->getQuery());
    }

    public function testRequestPathHasBeenUpdated()
    {
        $req = $this->requestFactory();
        $res = new Response();
        $next = function (Request $req, Response $res) {
            return $res;
        };

        /** @var CliRequest $cliRequest */
        $cliRequest = new CliRequest();

        /** @var  ResponseInterface $res */
        $res = $cliRequest($req, $res, $next);

        static::assertEquals('/status', $cliRequest->getRequest()->getUri()->getPath());
    }

    public function testRequestRemainsSameIfNoArgvIsPassed()
    {
        unset($GLOBALS['argv']);

        $req = $this->requestFactory();
        $res = new Response();
        $next = function (Request $req, Response $res) {
            return $res;
        };

        /** @var CliRequest $cliRequest */
        $cliRequest = new CliRequest();

        /** @var  ResponseInterface $res */
        $res = $cliRequest($req, $res, $next);

        static::assertEquals($req, $cliRequest->getRequest());
    }

    public function testRequestWhenNoParamsArePassed()
    {
        unset($GLOBALS['argv'][3]);

        $req = $this->requestFactory();
        $res = new Response();
        $next = function (Request $req, Response $res) {
            return $res;
        };

        /** @var CliRequest $cliRequest */
        $cliRequest = new CliRequest();

        /** @var  ResponseInterface $res */
        $res = $cliRequest($req, $res, $next);

        static::assertEquals('/status', $cliRequest->getRequest()->getUri()->getPath());
    }
}