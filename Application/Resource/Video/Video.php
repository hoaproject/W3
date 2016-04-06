<?php

namespace Application\Resource\Video;

use Application\Dispatcher\Kit;
use Application\Model;
use Application\Resource;
use Hoa\Database;
use Hoa\Promise;

class Video extends Resource
{
    public function get(Kit $_this)
    {
        $self = $this;

        $_this
            ->promise
            ->then(curry([$this, 'doTranslation'], …, 'Video', 'Video'))
            ->then(function (Kit $kit) {
                return $this->doTitle(
                    $kit,
                    $kit->view->getTranslation('Video')->_('Videos')
                );
            })
            ->then(function (Kit $kit) use ($self) {
                $self->openDatabase();
                $language = $kit->user->getLocale()->getLanguage();
                $awecodes = Model\Awecode::getAll();

                foreach ($awecodes as &$awecode) {
                    $awecode['id']          = ucfirst($awecode['id']);
                    $awecode['description'] = $awecode['description_' . $language];
                }

                $kit->data->awecodes = $awecodes;

                return $kit;
            })
            ->then(curry([$this, 'doMainOverlay'], …, 'Awecode/List'))
            ->then(curry([$this, 'doMainOverlay'], …, 'Video/Live'))
            ->then([$this, 'doFooter'])
            ->then([$this, 'doRender']);

        return;
    }

    public static function openDatabase()
    {
        Database\Dal::initializeParameters([
            'connection.list.awecode.dal' => Database\Dal::PDO,
            'connection.list.awecode.dsn' => 'sqlite:hoa://Data/Variable/Database/Awecode.sqlite'
        ]);
        Database\Dal::getInstance('awecode');

        return;
    }
}
