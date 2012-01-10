<?php

require dirname(dirname(__DIR__)) .
        DIRECTORY_SEPARATOR . 'Data' .
        DIRECTORY_SEPARATOR . 'Core.link.php';

from('Hoa')
-> import('Xyl.~')
-> import('Xyl.Interpreter.Html.~')
-> import('File.Read')
-> import('Http.Response.~')
-> import('Dispatcher.Basic')
-> import('Router.Http');

\Hoa\Core::getInstance()->initialize(array(
    'protocol.Application/Public/Classic' => './'
));

$dispatcher = new \Hoa\Dispatcher\Basic();
$router     = new \Hoa\Router\Http();
$router
   ->get('l',  '/Literature\.html', 'literature', 'default')
   ->get('lt', '/Literature/Mini-tutorial\.html', 'literature', 'minitutorial')
   ->get('ll', '/Literature/Learn/(?<chapter>\w+)\.html', 'literature', 'learn')
   ->get('lk', '/Literature/Keynote/(?<keynote>\w+)\.html', 'literature', 'keynote')
   ->get('r',  '/Research\.html', 'research', 'default')
   ->get_post('rx', '/Research/(?<article>\w+)/Experimentation\.html', 'research', 'experimentation')
   ->get('v',  '/Video\.html', 'video', 'default')
   ->get('v+', '/Video/(?<_able>\w+)\.html', 'video')
   ->get('c',  '/Contact\.html', 'index', 'contact')
   ->get('g',  '/(?<all>.*)', 'index', 'default')
   // --
   ->_get('_css', '/Css/(?<sheet>)')
   ->_get('dl',   'http://download\.hoa-project\.net/(?<file>)')
   ->_get('key',  'http://keynote\.hoa-project\.net/Shells/(?<shell>)\.html#http://keynote\.hoa-project\.net/(?<keynote>)\.html');

$xyl        = new \Hoa\Xyl(
    new \Hoa\File\Read('hoa://Application/View/Main.xyl'),
    new \Hoa\Http\Response(),
    new \Hoa\Xyl\Interpreter\Html(),
    $router
);

try {

    $dispatcher->dispatch($router, $xyl);
}
catch ( \Hoa\Core\Exception $e ) {

    $xyl->addOverlay('hoa://Application/View/Error.xyl');
    $xyl->render();
}
