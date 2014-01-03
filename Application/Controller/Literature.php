<?php

namespace {

from('Application')
-> import('Controller.Generic');

}

namespace Application\Controller {

class Literature extends Generic {

    public function DefaultAction ( $language )  {

        $language = $this->computeLanguage($language, 'Literature');
        $tr = $this->getTranslation('Literature');

        $this->data->title = $tr->_('Literature: documentation, manual, tutorial…');
        $this->view->addOverlay('hoa://Application/View/' . $language . '/Literature/Literature.xyl');
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
        $this->view->addOverlay('hoa://Application/View/Shared/Literature/Learn.xyl');
        $this->render();

        return;
    }

    public function HackAction ( $language, $chapter ) {

        $language = $this->computeLanguage($language, 'Literature');
        $tr = $this->getTranslation('Literature');

        $chapter             = ucfirst($chapter);
        $this->data->title   = 'Hoa\\' . $chapter . $tr->_(', hack book');
        $this->data->chapter = $chapter;
        $this->view->addOverlay('hoa://Application/View/' . $language . '/Literature/Hack.xyl');
        $this->view->addOverlay('hoa://Library/' . $chapter . '/Documentation/' . $language . '/Index.xyl');
        $this->render();

        return;
    }

    public function ResearchAction ( $article ) {

        $article = ucfirst($article);
        $file    = 'hoa://Application/External/Literature/Research/' .
                   $article . '.pdf';

        if(false === file_exists($file))
            throw new \Hoa\Router\Exception\NotFound(
                'Article %s is not found', 0, $article);

        $outputStream = $this->view->getOutputStream();
        $outputStream->sendHeader('Content-Type', 'application/pdf');
        $outputStream->writeAll(file_get_contents($file));

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
