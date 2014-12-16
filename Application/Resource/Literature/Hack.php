<?php

namespace Application\Resource\Literature;

use Hoa\Promise;
use Application\Dispatcher\Kit;
use Application\Resource;

class Hack extends Resource {

    public function get ( Kit $_this, $chapter ) {

        $_this
            ->promise
            ->then(function ( Kit $kit ) use ( $chapter ) {

                $subPromise = new Promise(function ( $fulfill ) use ( $kit ) {

                    $fulfill($kit);
                });
                $subPromise
                    ->then(curry([$this, 'doTranslation'], …, 'Literature', 'Literature'))
                    ->then(function ( Kit $kit ) use ( $chapter ) {

                        return $this->doTitle(
                            $kit,
                            'Hoa\\' . ucfirst($chapter) .
                            $kit->view->getTranslation('Literature')
                                      ->_(', hack book')
                        );
                    });

                return $subPromise;
            })
            ->then(curry([$this, 'doMainOverlay'], …, 'Literature/Hack'))
            ->then(function ( Kit $kit ) use ( $chapter ) {

                $chapter  = ucfirst($chapter);
                $language = ucfirst($kit->user->getLocale()->getLanguage());

                $kit->data->chapter = $chapter;
                $kit->view->addOverlay(
                    'hoa://Library/' . $chapter .
                    '/Documentation/' . $language . '/Index.xyl'
                );

                return $kit;
            })
            ->then([$this, 'doFooter'])
            ->then([$this, 'doRender']);

        return;
    }
}
