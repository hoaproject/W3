<?php

namespace Application\Resource\Literature;

use Application\Dispatcher\Kit;
use Application\Resource;

class Contributor extends Resource {

    public function get ( Kit $_this ) {

        $_this
            ->promise
            ->then(curry([$this, 'doTitle'], …, 'Contributor guide'))
            ->then(function ( Kit $kit ) {

                $kit->view->addOverlay(
                    'hoa://Application/External/Literature/Contributor/Guide.xyl'
                );

                return $kit;
            })
            ->then([$this, 'doFooter'])
            ->then([$this, 'doRender']);

        return;
    }
}
