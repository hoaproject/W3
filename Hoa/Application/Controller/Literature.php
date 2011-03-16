<?php

class LiteratureController extends \Hoa\Controller\Application {

    public function DefaultAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Literature/Literature.xyl');
        $this->view->render();

        return;
    }

    public function LearnAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Literature/Learn.xyl');
        $this->view->render();

        return;
    }
}
