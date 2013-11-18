<?php

namespace {

from('Application')
-> import('Controller.Generic');

}

namespace Application\Controller {

class Index extends Generic {

    public function DefaultAction ( )  {

        $blogApi = $this->router->unroute('b') . 'api/posts?limit=5';

        if(false !== $json = @file_get_contents($blogApi))
            if(null !== $handle = json_decode($json, true))
                $this->data->blog = $handle;

        $this->data->title = 'Un ensemble de bibliothèques PHP';
        $this->view->addOverlay('hoa://Application/View/Welcome.xyl');
        $this->render();

        return;
    }

    public function SourceAction ( ) {

        $this->data->title = 'Sources : Git, Github, Composer, archives…';
        $this->view->addOverlay('hoa://Application/View/Source.xyl');
        $this->render();

        return;
    }

    public function CommunityAction ( ) {

        $this->data->title = 'Communauté : mailing-lists, IRC, IRL…';
        $this->view->addOverlay('hoa://Application/View/Community.xyl');
        $this->render();

        return;
    }

    public function ContactAction ( ) {

        $response = $this->view->getOutputStream();
        $response->sendHeader(
            'Status',
            \Hoa\Http\Response::STATUS_MOVED_PERMANENTLY
        );
        $response->sendHeader(
            'Location',
            $this->router->unroute('c')
        );

        return;
    }

    public function AboutAction ( ) {

        $this->data->title = 'À propos';
        $this->view->addOverlay('hoa://Application/View/About.xyl');
        $this->render();

        return;
    }

    public function WhouseAction ( $who ) {

        $who = ucfirst($who);
        $this->data->title = $who . ' nous utilise';
        $this->view->addOverlay('hoa://Application/View/Whouse/' . $who . '.xyl');
        $this->render();

        return;
    }

    public function NolanguageAction ( $tail ) {

        $visitor = new \Application\Model\Visitor();

        $this->view->getOutputStream()->sendHeader(
            'Location',
            '/' . ucfirst($visitor->getLanguage()) . '/' . $this->router->getURI(),
            true,
            \Hoa\Http\Response::STATUS_MOVED_PERMANENTLY
        );

        return;
    }

    public function ErrorAction ( \Hoa\Core\Exception $exception ) {

        switch(get_class($exception)) {

            case 'Hoa\Router\Exception\NotFound':
                $this->view->getOutputStream()->sendStatus(
                    \Hoa\Http\Response::STATUS_NOT_FOUND
                );
              break;

            default:
                $this->view->getOutputStream()->sendStatus(
                    \Hoa\Http\Response::STATUS_INTERNAL_SERVER_ERROR
                );
        }

        $this->data->title = 'Erreur 😢';
        $this->view->addOverlay('hoa://Application/View/Error.xyl');
        $this->render();

        return;
    }
}

}
