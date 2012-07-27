<?php

namespace {

from('Application')
-> import('Controller.Generic');

}

namespace Application\Controller {

class Literature extends Generic {

    public function DefaultAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Literature/Literature.xyl');
        $this->render();

        return;
    }

    public function MinitutorialAction ( ) {

        $this->view->addOverlay(
            'hoa://Application/External/Literature/MiniTutorial/Index.xyl'
        );
        $this->render();

        return;
    }

    public function LearnAction ( $chapter ) {

        $this->view->addUse('hoa://Application/External/Literature/Learn/' . ucfirst($chapter) . '.xyl');
        $this->view->addOverlay('hoa://Application/View/Literature/Learn.xyl');
        $this->render();

        return;
    }

    public function HackAction ( $chapter ) {

        $this->view->addUse('hoa://Application/External/Literature/Hack/' . ucfirst($chapter) . '.xyl');
        $this->view->addOverlay('hoa://Application/View/Literature/Learn.xyl');
        $this->render();

        return;
    }

    public function KeynoteAction ( $keynote ) {

        $this->data->keynote[0]->id    = 'PHPTour11';
        $this->data->keynote[0]->title = 'PHPTour\'11';
        $this->view->addOverlay('hoa://Application/View/Literature/Keynote.xyl');
        $this->render();

        return;
    }

    public function PopcodeAction ( $code ) {

        $this->view->addUse('hoa://Application/External/Literature/Popcode/' .  ucfirst($code) . '.xyl');
        $this->view->addOverlay('hoa://Application/View/Literature/Popcode.xyl');
        $this->render();

        return;
    }
}

}
