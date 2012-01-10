<?php

namespace {

from('Hoa')
-> import('StringBuffer.Read')
-> import('Compiler.Llk')
-> import('Compiler.Visitor.Meta')
-> import('Test.Sampler.Random');

}

namespace Application\Controller {

class Research extends \Hoa\Dispatcher\Kit {

    public function DefaultAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Research/Research.xyl');
        $this->view->render();

        return;
    }

    public function ExperimentationAction ( $article ) {

        $this->ebgd12Xp();

        return;
    }

    public function ExperimentationActionAsync ( $article ) {

        $this->ebgd12XpAsync();

        return;
    }

    public function ebgd12Xp ( ) {

        $this->view->addOverlay('hoa://Application/View/Research/Ebgd12/Experimentation.xyl');
        $this->data->ebgd12[0]->result = '(no result)';
        $this->view->interprete();

        $form = $this->view->xpath('//__current_ns:*[@id="experimentation"]');
        $form = $this->view->getConcrete()->getConcreteElement($form[0]);
        $data = $form->getFormData();

        if($form->isValid() && !empty($data)) {

            dump(true);
            $grammar  = new \Hoa\StringBuffer\Read();
            $grammar->initializeWith($data['grammar']);
            $n        = $data['size'];

            try {

                $_grammar = \Hoa\Compiler\Llk::load($grammar);
                $_meta    = new \Hoa\Compiler\Visitor\Meta(
                    $_grammar,
                    new \Hoa\Test\Sampler\Random(),
                    $n
                );
                $this->data->ebgd12[0]->result[0] = $_meta->visit(
                    $_meta->getRuleAst($_grammar->getRootRule())
                );
            }
            catch ( \Exception $e ) {

                $this->data->ebgd12[0]->result[0] = $e->getFormattedMessage();
            }
        }

        $this->view->render();

        return;
    }

    public function ebgd12XpAsync ( ) {

        $rule      = $this->router->getTheRule();
        $variables = $rule[\Hoa\Router\Http::RULE_VARIABLES];
        $request   = $variables['_request'];

        if(empty($request) || !isset($request['grammar'])) {

            $this->view->getOutputStream()->writeAll('No.');

            return;
        }

        switch(strtolower($request['grammar'])) {

            case 'pcre':
                $this->view->getOutputStream()->writeAll(
                    file_get_contents('hoa://Library/Regex/Grammar.pp')
                );
              break;
        }

        return;
    }
}

}
