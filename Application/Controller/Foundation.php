<?php

namespace {

from('Application')
-> import('Controller.Generic');

}

namespace Application\Controller {

class Foundation extends Generic {

    public function IndexAction ( ) {

        $this->data->title = 'L\'association';
        $this->view->addOverlay('hoa://Application/View/Fr/Foundation/Foundation.xyl');
        $this->render();

        return;
    }

    public function StatutesAction ( ) {

        $this->data->title = 'Statuts de l\'assocation Hoa Project';
        $this->view->addOverlay('hoa://Application/View/Fr/Foundation/Statutes.xyl');
        $this->render();

        return;
    }

    public function SupportAction ( ) {

        $this->data->title = 'Nous soutenir !';
        $this->view->addOverlay('hoa://Application/View/Fr/Foundation/Support.xyl');
        $this->render();

        return;
    }
}

}
