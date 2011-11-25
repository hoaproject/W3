<?php

namespace Application\Controller {

class Literature extends \Hoa\Dispatcher\Kit {

    public function DefaultAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Literature/Literature.xyl');
        $this->view->render();

        return;
    }

    public function MinitutorialAction ( ) {

        $this->view->addOverlay(
            'hoa://Application/External/Literature/MiniTutorial/Index.xyl'
        );
        $this->view->render();

        return;
    }

    public function LearnAction ( $chapter ) {

        $this->view->addUse('hoa://Application/External/Literature/Learn/' . ucfirst($chapter) . '.xyl');
        $this->view->addOverlay('hoa://Application/View/Literature/Learn.xyl');
        $this->view->render();

        return;
    }

    public function KeynoteAction ( $keynote ) {

        $this->data->keynote[0]->id    = 'PHPTour11';
        $this->data->keynote[0]->title = 'PHPTour\'11';
        $this->view->addOverlay('hoa://Application/View/Literature/Keynote.xyl');
        $this->view->render();

        return;
    }
}

}
