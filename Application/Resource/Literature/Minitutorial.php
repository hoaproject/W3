<?php

namespace Application\Resource\Literature;

use Application\Dispatcher\Kit;
use Application\Resource;

class Minitutorial extends Resource {

    public function get ( Kit $_this ) {

        $_this
            ->promise
            ->then(curry([$this, 'doTitle'], …, 'Mini-tutorial'))
            ->then(function ( Kit $kit ) {

                $kit->view->addOverlay(
                    'hoa://Application/External/Literature/MiniTutorial/Index.xyl'
                );

                return $kit;
            })
            ->then([$this, 'doFooter'])
            ->then([$this, 'doRender']);

        return;
    }
}
