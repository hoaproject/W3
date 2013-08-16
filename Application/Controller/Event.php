<?php

namespace {

from('Application')
-> import('Controller.Generic');

}

namespace Application\Controller {

class Event extends Generic {

    public function DefaultAction ( )  {

        $this->data->title = 'Événements';
        $this->view->addOverlay('hoa://Application/View/Event/Event.xyl');
        $this->render();

        return;
    }

    public function Hoaapex13Action ( ) {

        $this->data->title = 'Apex 2013';
        $this->view->addOverlay('hoa://Application/View/Event/Hoaapex13.xyl');
        $this->render();

        return;
    }

    public function Cstva13Action ( ) {

        $this->data->title = 'CSTVA 2013';
        $this->view->addOverlay('hoa://Application/View/Event/Cstva13.xyl');
        $this->render();

        return;
    }

    public function Forumphp12Action ( ) {

        $this->data->title = 'ForumPHP 2012';
        $this->view->addOverlay('hoa://Application/View/Event/Forumphp12.xyl');
        $this->render();

        return;
    }

    public function Phptour11Action ( ) {

        $this->data->title = 'PHPTour 2011';
        $this->view->addOverlay('hoa://Application/View/Event/Phptour11.xyl');
        $this->render();

        return;
    }
}

}
