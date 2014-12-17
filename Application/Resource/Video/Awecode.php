<?php

namespace Application\Resource\Video;

use Hoa\Promise;
use Application\Dispatcher\Kit;
use Application\Model;
use Application\Resource;

class Awecode extends Resource {

    public function get ( Kit $_this, $id ) {

        $_this
            ->promise
            ->then(curry([$this, 'doTitle'], …, 'Awecode'))
            ->then(function ( Kit $kit ) use ( $id ) {

                Video::openDatabase();
                $language = $kit->user->getLocale()->getLanguage();

                $awecode = new Model\Awecode();
                $awecode->id = $id;
                $awecode->open();

                $kit->data->awecode              = $awecode;
                $kit->data->awecode[0]->subtitle = 'hoa://Application/Public/Subtitle/Awecode/' .
                                                   $language .
                                                   '/' .
                                                   ucfirst($awecode->id) .
                                                   '.srt';

                if(false !== $pos = strpos($awecode->id, '-'))
                    $libraryName = substr($awecode->id, 0, $pos);
                else
                    $libraryName = $awecode->id;

                $kit->data->awecode[0]->library = ucfirst($libraryName);

                return $kit;
            })
            ->then(curry([$this, 'doMainOverlay'], …, 'Awecode/Awecode'))
            ->then([$this, 'doFooter'])
            ->then([$this, 'doRender']);

        return;
    }
}
