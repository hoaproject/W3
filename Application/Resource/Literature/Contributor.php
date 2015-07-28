<?php

namespace Application\Resource\Literature;

use Hoa\Promise;
use Application\Dispatcher\Kit;
use Application\Resource;

class Contributor extends Resource {

    public function get ( Kit $_this ) {

        $_this
            ->promise
            ->then(function ( Kit $kit ) {

                $subPromise = new Promise(function ( $fulfill ) use ( $kit ) {

                    $fulfill($kit);
                });
                $subPromise
                    ->then(curry([$this, 'doTranslation'], …, 'Literature', 'Literature'))
                    ->then(function ( Kit $kit ) {

                        return $this->doTitle(
                            $kit,
                            $kit
                                ->view
                                ->getTranslation('Literature')
                                ->_('Contributor guide')
                        );
                    });

                return $subPromise;
            })
            ->then(function ( Kit $kit ) {

                $language = ucfirst($kit->user->getLocale()->getLanguage());

                $kit->view->addOverlay(
                    'hoa://Application/External/Literature/Contributor/' .
                    $language .
                    '/Guide.xyl'
                );

                return $kit;
            })
            ->then([$this, 'doFooter'])
            ->then([$this, 'doRender']);

        return;
    }
}
