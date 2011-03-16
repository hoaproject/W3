<?php

class LiteratureController extends \Hoa\Controller\Application {

    public function LearnAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Literature/Learn.xyl');
        $this->view->render();

        return;
    }
}
