<?php

require_once dirname(dirname(__DIR__)) .
             DIRECTORY_SEPARATOR . 'Data' .
             DIRECTORY_SEPARATOR . 'Core.link.php';

from('Hoa')
-> import('Dispatcher.Basic')
-> import('Router.Http');

$dispatcher = new \Hoa\Dispatcher\Basic(array(
    'asynchronous.action' => '(:%synchronous.action:)'
));
$router     = new \Hoa\Router\Http();
$router
    ->get('l',   '/Literature\.html', 'literature', 'default')
    ->get('lt',  '/Literature/Mini-tutorial\.html', 'literature', 'minitutorial')
    ->get('ll',  '/Literature/Learn/(?<chapter>\w+)\.html', 'literature', 'learn')
    ->get('lh',  '/Literature/Hack/(?<chapter>\w+)\.html', 'literature', 'hack')
    ->get('lp',  '/Literature/Popcode/(?<code>\w+)\.html', 'literature', 'popcode')
    ->get('lc',  '/Literature/Contributor/Guide\.html', 'literature', 'contributor')
    ->get('r',   '/Research\.html', 'research', 'default')
    ->get_post('rx', '/Research/(?<article>\w+)/Experimentation\.html', 'research', 'experimentation')
    ->get('s',   '/Source\.html', 'index', 'source')
    ->get('v',   '/Video\.html', 'video', 'default')
    ->get('v+',  '/Video/(?<_able>\w+)\.html', 'video')
    ->get('ev',  '/Event\.html', 'event', 'default')
    ->get('ev+', '/Event/(?<_able>\w+)\.html', 'event')
    ->get('a',   '/About\.html', 'index', 'about')
    ->get('c',   '/Contact\.html', 'index', 'contact')
    ->get('u',   '/Whouse/(?<who>\w+)\.html', 'index', 'whouse')
    ->get('p',   '/Project\.html', 'index', 'project')
    ->get('e',   '/Error\.html', 'index', 'error')
    ->get('g',   '/', 'index', 'default')
    // --
    ->_get('_resource', 'http://static.hoa-project.net/(?<resource>)')
    ->_get('b',      '/', null, null, array('_subdomain' => 'blog'))
    ->_get('dl',     'http://download.hoa-project.net/(?<file>)')
    ->_get('forum',  'http://forum.hoa-project.net/')
    ->_get('git',    'http://git.hoa-project.net/?p=(?<repository>).git')
    ->_get('github', 'https://github.com/hoaproject/(?<repository>)')
    ->_get('key.',   'http://keynote.hoa-project.net/(?<keynote>)')
    ->_get('key',    'http://keynote.hoa-project.net/Shells/(?<shell>).html#http://keynote.hoa-project.net/(?<keynote>).html');

try {

    $dispatcher->dispatch($router);
}
catch ( \Hoa\Core\Exception $e ) {

    $router->route('/Error.html');
    $rule                                                = &$router->getTheRule();
    $rule[\Hoa\Router\Http::RULE_VARIABLES]['exception'] = $e;
    $dispatcher->dispatch($router);
}
