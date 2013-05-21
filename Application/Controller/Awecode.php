<?php

namespace {

from('Application')
-> import('Controller.Generic')
-> import('Model.Awecode');

from('Hoa')
-> import('Database.Dal');

}

namespace Application\Controller {

class Awecode extends Generic {

    public function DefaultAction ( ) {

        $this->openDatabase();
        $awecodes = \Application\Model\Awecode::getAll();

        foreach($awecodes as &$awecode)
            $awecode['id'] = ucfirst($awecode['id']);

        $this->data->awecodes = $awecodes;
        $this->view->addOverlay('hoa://Application/View/Awecode/List.xyl');
        $this->render();

        return;
    }

    public function PraspelAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Awecode/Praspel.xyl');
        $this->render();

        return;
    }

    public function AwecodeAction ( $id )  {

        $this->openDatabase();
        $awecode = new \Application\Model\Awecode();
        $awecode->id = $id;
        $awecode->open();

        $this->view->addOverlay('hoa://Application/View/Awecode/Awecode.xyl');
        $this->data->awecode = $awecode;
        $this->render();

        return;
    }

    public function openDatabase ( ) {

        \Hoa\Database\Dal::initializeParameters(array(
            'connection.list.awecode.dal' => \Hoa\Database\Dal::PDO,
            'connection.list.awecode.dsn' => 'sqlite:hoa://Data/Variable/Database/Awecode.sqlite'
        ));
        \Hoa\Database\Dal::getInstance('awecode');

        return;
    }
}

}
