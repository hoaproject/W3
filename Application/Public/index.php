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
$router = new \Hoa\Router\Http();
$router
    ->get(
        'l',
        '/Literature\.html',
        'literature',
        'default'
    )
    ->get(
        'lt',
        '/Literature/Mini-tutorial\.html',
        'literature',
        'minitutorial'
    )
    ->get(
        'll',
        '/Literature/Learn/(?<chapter>\w+)\.html',
        'literature',
        'learn'
    )
    ->get(
        'lh',
        '/Literature/Hack/(?<chapter>[\w ]+)\.html',
        'literature',
        'hack'
    )
    ->get(
        'lr',
        '/Literature/Research/(?<article>[\w\d]+)\.html',
        'literature',
        'research'
    )
    ->get(
        'lp',
        '/Literature/Popcode/(?<code>\w+)\.html',
        'literature',
        'popcode'
    )
    ->get(
        'lc',
        '/Literature/Contributor/Guide\.html',
        'literature',
        'contributor'
    )
    ->get(
        'r',
        '/Research\.html',
        'research',
        'default'
    )
    ->get_post(
        'rx',
        '/Research/(?<article>\w+)/Experimentation\.html',
        'research',
        'experimentation'
    )
    ->get(
        's',
        '/Source\.html',
        'index',
        'source'
    )
    ->get(
        'v',
        '/Awecode\.html',
        'awecode',
        'default'
    )
    ->get(
        'v+',
        '/Awecode/(?<id>[\w\-_]+)\.html',
        'awecode',
        'awecode'
    )
    ->get(
        'ev',
        '/Event\.html',
        'event',
        'default'
    )
    ->get(
        'ev+',
        '/Event/(?<_able>\w+)\.html',
        'event'
    )
    ->get(
        'c',
        '/Community\.html',
        'index',
        'community'
    )
    ->get(
        'contact',
        '/Contact\.html',
        'index',
        'contact'
    )
    ->get(
        'a',
        '/About\.html',
        'index',
        'about'
    )
    ->get(
        'f',
        '/Foundation.html',
        'foundation',
        'index'
    )
    ->get(
        'f+',
        '/Foundation/(?<_able>\w+)\.html',
        'foundation'
    )
    ->get(
        'u',
        '/Whouse/(?<who>\w+)\.html',
        'index',
        'whouse'
    )
    ->get(
        'e',
        '/Error\.html',
        'index',
        'error'
    )
    ->get(
        'g',
        '/',
        'index',
        'default'
    )
    ->get(
        'short',
        '/s/(?<short>\w+)',
        'short',
        'default'
    )

    // --

    ->_get('_resource', 'http://static.hoa-project.net/(?<resource>)')
    ->_get('b',      '/', null, null, array('_subdomain' => 'blog'))
    ->_get('b_post', '/posts/(?<id>)-(?<normalized_title>).html', null, null, array('_subdomain' => 'blog'))
    ->_get('dl',     'http://download.hoa-project.net/(?<file>)')
    ->_get('forum',  'http://forum.hoa-project.net/')
    ->_get('lists',  'http://lists.hoa-project.net/index.cgi/lists')
    ->_get('list-subscribe', 'http://lists.hoa-project.net/index.cgi/subscribe/(?<list>)')
    ->_get('nabble-development', 'http://hoa-development.53756.x6.nabble.com/')
    ->_get('nabble-support', 'http://hoa-support.53758.x6.nabble.com/')
    ->_get('git',    'http://git.hoa-project.net/?p=(?<repository>).git')
    ->_get('github', 'https://github.com/hoaproject/(?<repository>)')
    ->_get('twitter', 'https://twitter.com/hoaproject')
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
