<?php

require_once dirname(dirname(__DIR__)) .
             DIRECTORY_SEPARATOR . 'Data' .
             DIRECTORY_SEPARATOR . 'Core.link.php';

from('Hoa')
-> import('Router.Http')
-> import('Dispatcher.Basic')
-> import('File.Read')
-> import('Mime.~');

$router = new Hoa\Router\Http();
$router
    ->any('a', '.*', function ( Hoa\Dispatcher\Kit $_this ) {

        $uri  = $_this->router->getURI();
        $file = __DIR__ . DS . $uri;

        if(!empty($uri) && true === file_exists($file)) {

            $stream = new Hoa\File\Read($file);

            try {

                $mime  = new Hoa\Mime($stream);
                $_mime = $mime->getMime();
            }
            catch ( \Hoa\Mime\Exception $e ) {

                $_mime = 'text/plain';
            }

            header('Content-Type: ' . $_mime);
            echo $stream->readAll();

            return;
        }

        require 'index.php';
    });

$dispatcher = new Hoa\Dispatcher\Basic();
$dispatcher->dispatch($router);
