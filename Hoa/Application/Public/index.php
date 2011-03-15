<?php

require_once '/var/lib/hoa/Core/Core.php';

from('Hoa')
-> import('Xyl.~')
-> import('Xyl.Interpreter.Html.~')
-> import('File.Read')
-> import('Http.Response')
-> import('Controller.Dispatcher.Basic')
-> import('Controller.Router');

\Hoa\Core::getInstance()->initialize(array(
    'protocol.Application'                    => '../',
    'protocol.Application/Public/Classic/Css' => 'Css/',
    'protocol.Application/External'           => '../External/'
));


$router     = new \Hoa\Controller\Router();
$router->setParameter('rewrited', true);
$router
   ->addRule('v', '/Video/Praspel\.html', 'video',   'praspel')
   ->addRule('l', '/Literature/(?<action>\w+)\.html', 'literature')
   ->addRule('g', '(?<all>.*)',          'default', 'default')
   // --
   ->addPrivateRule('_css', '/Css/(?<sheet>)')
   ->addPrivateRule('dl',   'http://download.hoa-project.net/(?<file>)');

$dispatcher = new \Hoa\Controller\Dispatcher\Basic();
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

    $xyl->addUse('hoa://Application/View/Error.xyl');
    $xyl->render();
}
