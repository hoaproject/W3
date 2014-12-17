<?php

namespace Application\Resource\Literature;

use Application\Dispatcher\Kit;
use Application\Resource;
use Hoa\Router;

class Research extends Resource {

    public function get ( Kit $_this, $article ) {

        $_this
            ->promise
            ->then(function ( Kit $kit ) use ( $article ) {

                $article = ucfirst($article);
                $file    = 'hoa://Application/External/Literature/Research/' .
                           $article . '.pdf';

                if(false === file_exists($file))
                    throw new Router\Exception\NotFound(
                        'Article %s is not found', 0, $article);

                $response = $kit->view->getOutputStream();
                $response->sendHeader('Content-Type', 'application/pdf');
                $response->writeAll(file_get_contents($file));

                return $kit;
            });

        return;
    }
}
