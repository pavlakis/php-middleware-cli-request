<?php declare(strict_types=1);

/**
 * Pavlakis PHP Middleware CLI Request
 *
 * @link        https://github.com/pavlakis/php-middleware-cli-request
 * @copyright   Copyright © 2017 Antonis Pavlakis
 * @license     https://github.com/pavlakis/php-middleware-cli-request/blob/master/LICENSE (BSD 3-Clause License)
 */
namespace Pavlakis\Middleware\Cli;

use Psr\Http\Message\ServerRequestInterface,
    Psr\Http\Message\ResponseInterface;

/**
 * Class Request
 * @package Pavlakis\Middleware\Cli
 */
class Request
{

    /**
     * @var ServerRequestInterface
     */
    protected $request = null;

    /**
     * Exposed for testing.
     * @return ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get a value from an array if exists otherwise return a default value
     *
     * @param   array   $argv
     * @param   integer $key
     * @param   mixed   $default
     * @return  string
     */
    private function get($argv, $key, $default = '') : string
    {
        if (!array_key_exists($key, $argv)) {
            return $default;
        }

        return $argv[$key];
    }

    /**
     * Construct the URI if path and params are being passed
     *
     * @param string $path
     * @param string $params
     * @return string
     */
    private function getUri($path, $params) : string
    {
        $uri = '/';
        if (strlen($path) > 0) {
            $uri = $path;
        }

        if (strlen($params) > 0) {
            $uri .= '?' . $params;
        }

        return $uri;
    }

    /**
     * Invoke middleware
     *
     * @param  ServerRequestInterface   $request  PSR7 request object
     * @param  ResponseInterface        $response PSR7 response object
     * @param  callable                 $next     Next middleware callable
     *
     * @return ResponseInterface PSR7 response object
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) : ResponseInterface
    {
        global $argv;

        $this->request = $request;

        if (isset($argv)) {

            $path   = $this->get($argv, 1);
            $method = $this->get($argv, 2);
            $params = $this->get($argv, 3);

            if (strtoupper($method) === 'GET') {
                $this->request = \Slim\Http\Request::createFromEnvironment(\Slim\Http\Environment::mock([
                    'REQUEST_METHOD'    => 'GET',
                    'REQUEST_URI'       => $this->getUri($path, $params),
                    'QUERY_STRING'      => $params
                ]));
            }

            unset($argv);
        }

        return $next($this->request, $response);
    }
}