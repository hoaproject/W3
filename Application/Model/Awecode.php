<?php

namespace {

from('Application')
-> import('Model.Exception');

from('Hoa')
-> import('Model.~');

}

namespace Application\Model {

class Awecode extends \Hoa\Model {

    public $_id;
    public $_title;
    public $_vimeoId;
    public $_declare;

    public function construct ( ) {

        $this->setMappingLayer(\Hoa\Database\Dal::getLastInstance());
    }

    public function open ( Array $constraints = array() ) {

        $constraints = array_merge($this->getConstraints(), $constraints);

        $data = $this->getMappingLayer()
                     ->prepare(
                        'SELECT * FROM awecode WHERE id = :id'
                     )
                     ->execute($constraints)
                     ->fetchAll();

        if(!isset($data[0]))
            throw new \Application\Model\Exception(
                'Unknown awecode.', 0);

        $this->map($data[0]);

        return;
    }
}

}
