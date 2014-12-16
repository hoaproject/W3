<?php

namespace Application\Dispatcher;

use Application;
use Hoa;
use Hoa\File;
use Hoa\Http;
use Hoa\Promise;
use Hoa\Xyl;

class Kit extends Hoa\Dispatcher\Kit {

    public $promise = null;
    public $user    = null;

    public function construct ( ) {

        parent::construct();

        $self          = $this;
        $this->user    = new Application\Model\User();
        $this->promise = new Promise(function ( $fulfill ) use ( $self ) {

            if(false === $self->router->isAsynchronous())
                $main = 'hoa://Application/View/Shared/Main.xyl';
            else
                $main = 'hoa://Application/View/Shared/Main.fragment.xyl';

            $xyl = new Xyl(
                new File\Read($main),
                new Http\Response(false),
                new Xyl\Interpreter\Html(),
                $self->router
            );
            $xyl->setTheme('');

            $self->view = $xyl;
            $self->data = $xyl->getData();

            $fulfill($self);

            return;
        });

        return;
    }
}
