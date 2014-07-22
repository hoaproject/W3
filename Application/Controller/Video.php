<?php

namespace {

from('Application')
-> import('Controller.Generic')
-> import('Model.Awecode');

from('Hoa')
-> import('Database.Dal');

}

namespace Application\Controller {

class Video extends Generic {

    public function DefaultAction ( $language ) {

        $this->computeLanguage($language, 'Awecode');
        $tr = $this->getTranslation('Awecode');

        $this->openDatabase();
        $awecodes = \Application\Model\Awecode::getAll();

        foreach($awecodes as &$awecode) {

            $awecode['id']          = ucfirst($awecode['id']);
            $awecode['description'] = $awecode['description_' . $language];
        }

        $this->data->title    = $tr->_('Awecode, when the code meets the video');
        $this->data->awecodes = $awecodes;
        $this->view->addOverlay('hoa://Application/View/Shared/Awecode/List.xyl');

        $this->view->addOverlay('hoa://Application/View/Shared/Video/Live.xyl');

        $this->render();

        return;
    }

    public function AwecodeAction ( $language, $id )  {

        $language = $this->computeLanguage($language, 'Awecode');
        $tr = $this->getTranslation('Awecode');

        $this->openDatabase();
        $awecode = new \Application\Model\Awecode();
        $awecode->id = $id;
        $awecode->open();

        $this->data->title                = $tr->_('Awecode about %s', strip_tags($awecode->title));
        $this->data->awecode              = $awecode;
        $this->data->awecode[0]->subtitle = 'hoa://Application/Public/Subtitle/Awecode/' .
                                            $language .
                                            '/' . ucfirst($awecode->id) . '.srt';

        if(false !== $pos = strpos($awecode->id, '-'))
            $libraryName = substr($awecode->id, 0, $pos);
        else
            $libraryName = $awecode->id;

        $this->data->awecode[0]->library = ucfirst($libraryName);

        $this->view->addOverlay('hoa://Application/View/Shared/Awecode/Awecode.xyl');
        $this->render();

        return;
    }

    protected function openDatabase ( ) {

        \Hoa\Database\Dal::initializeParameters(array(
            'connection.list.awecode.dal' => \Hoa\Database\Dal::PDO,
            'connection.list.awecode.dsn' => 'sqlite:hoa://Data/Variable/Database/Awecode.sqlite'
        ));
        \Hoa\Database\Dal::getInstance('awecode');

        return;
    }
}

}

