<?php

namespace Application\Model;

use Hoa\Database;
use Hoa\Model;

class Awecode extends Model
{
    public $_id;
    public $_title;
    public $_vimeoId;
    public $_declare;
    public $_description_en;
    public $_description_fr;

    public function construct()
    {
        $this->setMappingLayer(Database\Dal::getLastInstance());
    }

    public function open(array $constraints = [])
    {
        $constraints = array_merge($this->getConstraints(), $constraints);

        $data =
            $this
                ->getMappingLayer()
                ->prepare(
                    'SELECT * FROM awecode WHERE id = :id',
                    [\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY]
                )
                ->execute($constraints)
                ->fetchAll();

        if (!isset($data[0])) {
            throw new Exception(
                'Unknown awecode.', 0);
        }

        $this->map($data[0]);

        return;
    }

    public static function getAll()
    {
        return
            Database\Dal::getLastInstance()
                ->prepare(
                    'SELECT * FROM awecode',
                    [\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY]
                )
                ->execute()
                ->fetchAll();
    }
}
