<?php
/**
 * Pavlakis PHP Middleware CLI Request
 *
 * @link        https://github.com/pavlakis/php-middleware-cli-request
 * @copyright   Copyright © 2017 Antonis Pavlakis
 * @license     https://github.com/pavlakis/php-middleware-cli-request/blob/master/LICENSE (BSD 3-Clause License)
 */
$autoloader = require dirname(dirname(__DIR__)) . '/vendor/autoload.php';
$autoloader->add('Pavlakis\\Tests\\Middleware\\Cli\\', __DIR__);