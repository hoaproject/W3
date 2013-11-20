<?php

namespace {

from('Application')
-> import('Controller.Generic');

}

namespace Application\Controller {

class Index extends Generic {

    public function DefaultAction ( $language )  {

        $language = $this->computeLanguage($language, 'Index');
        $tr = $this->getTranslation('Index');

        $blogApi = $this->router->unroute('b') . 'api/posts?limit=5';

        if(false !== $json = @file_get_contents($blogApi))
            if(null !== $handle = json_decode($json, true))
                $this->data->blog = $handle;

        $this->data->title = $tr->_('A set of PHP libraries');
        $this->view->addOverlay('hoa://Application/View/Shared/Welcome.xyl');
        $this->render();

        return;
    }

    public function SourceAction ( $language ) {

        $language = $this->computeLanguage($language, 'Source');
        $tr = $this->getTranslation('Source');

        $this->data->title = $tr->_('Sources: Git, Github, Composer, archives…');
        $this->view->addOverlay('hoa://Application/View/' . $language . '/Source.xyl');
        $this->render();

        return;
    }

    public function CommunityAction ( $language ) {

        $language = $this->computeLanguage($language, 'Community');
        $tr = $this->getTranslation('Community');

        $this->data->title = $tr->_('Community: mailing-lists, IRC, IRL…');
        $this->view->addOverlay('hoa://Application/View/' . $language . '/Community.xyl');
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

    public function AboutAction ( $language ) {

        $language = $this->computeLanguage($language, 'About');
        $tr = $this->getTranslation('About');

        $this->data->title = $tr->_('About');
        $this->view->addOverlay('hoa://Application/View/' . $language . '/About.xyl');
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

        $this->view->getOutputStream()->sendHeader(
            'Location',
            '/' . ucfirst(static::getVisitor()->getLanguage()) .
            '/' . $this->router->getURI(),
            true,
            \Hoa\Http\Response::STATUS_MOVED_PERMANENTLY
        );

        return;
    }

    public function ErrorAction ( $language, \Hoa\Core\Exception $exception ) {

        $language = $this->computeLanguage($language, 'Error');
        $tr = $this->getTranslation('Error');

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

        $this->data->title = $tr->_('Error');
        $this->view->addOverlay('hoa://Application/View/Shared/Error.xyl');
        $this->render();

        exit;
    }
}

}
