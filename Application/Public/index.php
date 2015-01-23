<?php

require_once dirname(dirname(__DIR__)) .
             DIRECTORY_SEPARATOR . 'Data' .
             DIRECTORY_SEPARATOR . 'Core.link.php';

use Hoa\Core;
use Hoa\Dispatcher;
use Hoa\Router;

Core::enableErrorHandler();
Core::enableExceptionHandler();

$dispatcher = new Dispatcher\ClassMethod([
    'synchronous.call'  => 'Application\Resource\(:call:U:)',
    'synchronous.able'  => '(:%variables._method:U:)',
    'asynchronous.call' => '(:%synchronous.call:)',
    'asynchronous.able' => '(:%synchronous.able:)',
]);
$dispatcher->setKitName('Application\Dispatcher\Kit');
$router = new Router\Http();

$router
    // Private rules.
    ->_get(
        '_resource',
        'http://127.0.0.1:8888/Static/(?<resource>)'
    )
    ->_get(
        'blog',
        '/',
        null,
        null,
        ['_subdomain' => 'blog']
    )
    ->_get(
        'blog_post',
        '/posts/(?<id>)-(?<normalized_title>).html',
        null,
        null,
        ['_subdomain' => 'blog']
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
        'http://git.hoa-project.net/(?<repository>).git/'
    )
    ->_get(
        'github',
        'https://github.com/hoaproject/(?<repository>)'
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
        'http://central.hoa-project.net/Resource/(?<path>)'
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
