<?php

require_once dirname(dirname(__DIR__)) .
             DIRECTORY_SEPARATOR . 'Data' .
             DIRECTORY_SEPARATOR . 'Core.link.php';

\Hoa\Core::enableErrorHandler();
\Hoa\Core::enableExceptionHandler();

from('Hoa')
-> import('Dispatcher.Basic')
-> import('Router.Http')
-> import('Locale.~')
-> import('Locale.Localizer.Coerce');

from('Application')
-> import('Controller.Generic');

$dispatcher = new \Hoa\Dispatcher\Basic(array(
    'asynchronous.action' => '(:%synchronous.action:)'
));
$router = new \Hoa\Router\Http();

$router
    ->get(
        'nolanguage',
        '/(?<tail>$|(?!\w{2}/).{3,})',
        'index',
        'nolanguage'
    )
    ->get(
        'language',
        '/(?<language>\w{2}).*',
        function ( $language ) use ( $router, $dispatcher ) {

            $router->removeRule('language');
            \Application\Controller\Generic::getVisitor()->setLocale(
                new \Hoa\Locale(
                    new \Hoa\Locale\Localizer\Coerce($language)
                )
            );

            $language         = ucfirst($language);
            $defaultVariables = array('language' => $language);

            $router
                ->get(
                    'l',
                    '/(?<language>\w{2})/Literature\.html',
                    'literature',
                    'default',
                    $defaultVariables
                )
                ->get(
                    'lt',
                    '/(?<language>\w{2})/Literature/Mini-tutorial\.html',
                    'literature',
                    'minitutorial',
                    $defaultVariables
                )
                ->get(
                    'll',
                    '/(?<language>\w{2})/Literature/Learn/(?<chapter>\w+)\.html',
                    'literature',
                    'learn',
                    $defaultVariables
                )
                ->get(
                    'lh',
                    '/(?<language>\w{2})/Literature/Hack/(?<chapter>[\w ]+)\.html',
                    'literature',
                    'hack',
                    $defaultVariables
                )
                ->get(
                    'lr',
                    '/(?<language>\w{2})/Literature/Research/(?<article>[\w\d]+)\.html',
                    'literature',
                    'research',
                    $defaultVariables
                )
                ->get(
                    'lc',
                    '/(?<language>\w{2})/Literature/Contributor/Guide\.html',
                    'literature',
                    'contributor',
                    $defaultVariables
                )
                ->get(
                    'r',
                    '/(?<language>\w{2})/Research\.html',
                    'research',
                    'default',
                    $defaultVariables
                )
                ->get_post(
                    'rx',
                    '/(?<language>\w{2})/Research/(?<article>\w+)/Experimentation\.html',
                    'research',
                    'experimentation',
                    $defaultVariables
                )
                ->get(
                    's',
                    '/(?<language>\w{2})/Source\.html',
                    'index',
                    'source',
                    $defaultVariables
                )
                ->get(
                    'v-',
                    '/(?<language>\w{2})/Awecode\.html',
                    'awecode',
                    'redirect'
                )
                ->get(
                    'v',
                    '/(?<language>\w{2})/Video\.html',
                    'video',
                    'default',
                    $defaultVariables
                )
                ->get(
                    'v+',
                    '/(?<language>\w{2})/Awecode/(?<id>[\w\-_]+)\.html',
                    'video',
                    'awecode',
                    $defaultVariables
                )
                ->get(
                    'ev',
                    '/(?<language>\w{2})/Event\.html',
                    'event',
                    'default',
                    $defaultVariables
                )
                ->get(
                    'ev+',
                    '/(?<language>\w{2})/Event/(?<_able>\w+)\.html',
                    'event',
                    null,
                    $defaultVariables
                )
                ->get(
                    'c',
                    '/(?<language>\w{2})/Community\.html',
                    'index',
                    'community',
                    $defaultVariables
                )
                ->get(
                    'contact',
                    '/(?<language>\w{2})/Contact\.html',
                    'index',
                    'contact',
                    $defaultVariables
                )
                ->get(
                    'a',
                    '/(?<language>\w{2})/About\.html',
                    'index',
                    'about',
                    $defaultVariables
                )
                ->get(
                    'f',
                    '/(?<language>\w{2})/Foundation.html',
                    'foundation',
                    'index',
                    $defaultVariables
                )
                ->get(
                    'f+',
                    '/(?<language>\w{2})/Foundation/(?<_able>\w+)\.html',
                    'foundation',
                    null,
                    $defaultVariables
                )
                ->get(
                    'u',
                    '/(?<language>\w{2})/Whouse/(?<who>\w+)\.html',
                    'index',
                    'whouse',
                    $defaultVariables
                )
                ->get(
                    'e',
                    '/(?<language>\w{2})/Error\.html',
                    'index',
                    'error',
                    $defaultVariables
                )
                ->get(
                    'g',
                    '/(?<language>\w{2})/?',
                    'index',
                    'default',
                    $defaultVariables
                )
                ->get(
                    'short',
                    '/s/(?<short>\w+)',
                    'short',
                    'default',
                    $defaultVariables
                );

            $router->route();
            $dispatcher->dispatch($router);
        }
    );

$router
    ->_get('_resource', 'http://static.hoa-project.net/(?<resource>)')
    ->_get('b',      '/', null, null, array('_subdomain' => 'blog'))
    ->_get('b_post', '/posts/(?<id>)-(?<normalized_title>).html', null, null, array('_subdomain' => 'blog'))
    ->_get('dl',     'http://download.hoa-project.net/(?<file>)')
    ->_get('forum',  'http://forum.hoa-project.net/')
    ->_get('lists',  'http://lists.hoa-project.net/index.cgi/lists')
    ->_get('list-subscribe', 'http://lists.hoa-project.net/index.cgi/subscribe/(?<list>)')
    ->_get('nabble-development', 'http://hoa-development.53756.x6.nabble.com/')
    ->_get('nabble-support', 'http://hoa-support.53758.x6.nabble.com/')
    ->_get('git',    'http://git.hoa-project.net/(?<repository>).git/')
    ->_get('github', 'https://github.com/hoaproject/(?<repository>)')
    ->_get('twitter', 'https://twitter.com/hoaproject')
    ->_get('key.',   'http://keynote.hoa-project.net/(?<keynote>)')
    ->_get('key',    'http://keynote.hoa-project.net/Shells/(?<shell>).html#http://keynote.hoa-project.net/(?<keynote>).html');

try {

    $dispatcher->dispatch($router);
}
catch ( \Hoa\Core\Exception $e ) {

    $router->route('/En/Error.html');
    $rule                                                = &$router->getTheRule();
    $rule[\Hoa\Router\Http::RULE_VARIABLES]['exception'] = $e;
    $dispatcher->dispatch($router);
}
