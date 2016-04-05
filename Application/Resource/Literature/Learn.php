<?php

namespace Application\Resource\Literature;

use Application\Dispatcher\Kit;
use Application\Resource;

class Learn extends Resource
{
    public function get(Kit $_this, $chapter)
    {
        $_this
            ->promise
            ->then(curry([$this, 'doTitle'], …, 'Manuel d\'apprentissage'))
            ->then(function (Kit $kit) use ($chapter) {

                $chapter = ucfirst($chapter);

                $kit->view->addUse(
                    'hoa://Application/External/Literature/Learn/' .
                    $chapter . '.xyl'
                );

                return $kit;
            })
            ->then(curry([$this, 'doMainOverlay'], …, 'Literature/Learn'))
            ->then([$this, 'doRender']);

        return;
    }
}
