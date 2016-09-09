<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Bootstrap.php';

use Hoa\Consistency;
use Hoa\Dispatcher;
use Hoa\Protocol;
use Hoa\Router;

$autoloader = new Consistency\Autoloader();
$autoloader->addNamespace('Application', dirname(__DIR__));
$autoloader->register();

Protocol::getInstance()['Application']->setReach(dirname(__DIR__) . DS);
Protocol::getInstance()['Data']->setReach(dirname(dirname(__DIR__)) . DS . 'Data' . DS);

$dispatcher = new Dispatcher\ClassMethod([
    'synchronous.call'  => 'Application\Resource\(:call:U:)',
    'synchronous.able'  => '(:%variables._method:U:)',
    'asynchronous.call' => '(:%synchronous.call:)',
    'asynchronous.able' => '(:%synchronous.able:)'
]);
$dispatcher->setKitName('Application\Dispatcher\Kit');
$router = new Router\Http();

$router
    // Private rules.
    ->_get(
        '_resource',
        'https://static.hoa-project.net/(?<resource>)'
    )
    ->_get(
        'blog',
        'https://blog.hoa-project.net/'
    )
    ->_get(
        'download',
        'http://download.hoa-project.net/(?<file>)'
    )
    ->_get(
        'lists',
        'http://lists.hoa-project.net/lists'
    )
    ->_get(
        'list-subscribe',
        'http://lists.hoa-project.net/subscribe/(?<list>)'
    )
    ->_get(
        'git',
        'https://git.hoa-project.net/(?<repository>).git/'
    )
    ->_get(
        'github',
        'https://github.com/hoaproject/(?<repository>)'
    )
    ->_get(
        'board',
        'https://waffle.io/hoaproject/(?<repository>)'
    )
    ->_get(
        'twitter',
        'https://twitter.com/hoaproject'
    )
    ->_get(
        'keynote',
        'http://keynote.hoa-project.net/(?<keynote>)'
    )
    ->_get(
        'central_resource',
        'https://central.hoa-project.net/Resource/(?<path>)'
    )
    ->_get(
        'comments',
        'http://comments.hoa-project.net/(?<file>)'
    )

    // Public rules.
    ->get(
        'choose-language',
        '/(?<language>\w{2})(?<uri>$|/(?-i).*)',
        'Language'
    )
    ->get(
        'error',
        '/Error.html',
        'Error'
    )
    ->get(
        'fallback',
        '/.*',
        'Fallback'
    );

$dispatcher->dispatch($router);
