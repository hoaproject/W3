<?php

namespace {

from('Application')
-> import('Controller.Generic');

}

namespace Application\Controller {

class Literature extends Generic {

    public function DefaultAction ( )  {

        $this->data->title = 'Littérature : documentation, manuel, tutoriel…';
        $this->view->addOverlay('hoa://Application/View/Literature/Literature.xyl');
        $this->render();

        return;
    }

    public function MinitutorialAction ( ) {

        $this->data->title = 'Mini-tutoriel';
        $this->view->addOverlay(
            'hoa://Application/External/Literature/MiniTutorial/Index.xyl'
        );
        $this->render();

        return;
    }

    public function LearnAction ( $chapter ) {

        $this->data->title = 'Manuel d\'apprentissage';
        $this->view->addUse('hoa://Application/External/Literature/Learn/' . ucfirst($chapter) . '.xyl');
        $this->view->addOverlay('hoa://Application/View/Literature/Learn.xyl');
        $this->render();

        return;
    }

    public function HackAction ( $chapter ) {

        $chapter             = ucfirst($chapter);
        $this->data->title   = 'Hoa\\' . $chapter . ', hack book';
        $this->data->chapter = $chapter;
        $this->view->addOverlay('hoa://Application/View/Literature/Hack.xyl');
        $this->view->addOverlay('hoa://Library/' . $chapter . '/Documentation/Fr/Index.xyl');
        $this->render();

        return;
    }

    public function ContributorAction ( ) {

        $this->view->addUse('hoa://Application/External/Literature/Contributor/Guide.xyl');
        $this->view->addOverlay('hoa://Application/View/Literature/Learn.xyl');
        $this->render();

        return;
    }
}

}
