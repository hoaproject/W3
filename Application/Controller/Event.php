<?php

namespace {

from('Application')
-> import('Controller.Generic');

}

namespace Application\Controller {

class Event extends Generic {

    public function DefaultAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Event/Event.xyl');
        $this->render();

        return;
    }

    public function Hoaapex13Action ( ) {

        $this->view->addOverlay('hoa://Application/View/Event/HoaApex13.xyl');
        $this->render();

        return;
    }

    public function Forumphp12Action ( ) {

        $this->view->addOverlay('hoa://Application/View/Event/Forumphp12.xyl');
        $this->render();

        return;
    }

    public function Phptour11Action ( ) {

        $this->view->addOverlay('hoa://Application/View/Event/Phptour11.xyl');
        $this->render();

        return;
    }
}

}
