<?php

namespace {

from('Hoa')
-> import('StringBuffer.Read')
-> import('Compiler.Llk')
-> import('Compiler.Visitor.Uniform')
-> import('Test.Sampler.Random');

from('Application')
-> import('Controller.Generic');

}

namespace Application\Controller {

class Research extends Generic {

    public function DefaultAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Research/Research.xyl');
        $this->render();

        return;
    }

    public function ExperimentationAction ( $article ) {

        $this->edgb12Xp();

        return;
    }

    public function ExperimentationActionAsync ( $article ) {

        $this->edgb12XpAsync();

        return;
    }

    public function edgb12Xp ( ) {

        $this->view->addOverlay('hoa://Application/View/Research/Edgb12/Experimentation.xyl');
        $this->data->edgb12->result  = '(no error)';
        $this->data->edgb12->data    = '(no result)';
        $this->data->edgb12->verdict = 'none';
        $this->data->edgb12->grammar = <<<GRAMMAR
%skip  space \s
%token add   \+
%token sub   \-
%token mult  \*
%token numb  [0-9]

root:
    number() ( ( ::add:: | ::sub:: | ::mult:: ) number() )*

number:
    <numb>
GRAMMAR;
        $this->view->interprete();

        $form = $this->view->xpath('//__current_ns:*[@id="experimentation"]');
        $form = $this->view->getConcrete()->getConcreteElement($form[0]);
        $data = $form->getFormData();

        if($form->isValid() && !empty($data)) {

            try {

                $this->data->edgb12[0]->grammar[0] = $data['grammar'];
                $this->data->edgb12[0]->data[0]    = $data['data'];
                $grammar  = new \Hoa\StringBuffer\Read();
                $grammar->initializeWith($data['grammar']);
                $_grammar = \Hoa\Compiler\Llk::load($grammar);

                if(isset($data['sample'])) {

                    $_sampler = new \Hoa\Compiler\Visitor\Uniform(
                        $_grammar,
                        null,
                        $data['size']
                    );
                    $this->data->edgb12[0]->data[0] = $_sampler->visit(
                        $_sampler->getRootRule()
                    );
                }
                elseif(isset($data['predicate'])) {

                    $_grammar->parse($data['data'], null, false);
                }

                $this->data->edgb12[0]->verdict[0] = 'true';
            }
            catch ( \Hoa\Compiler\Exception $e ) {

                $this->data->edgb12[0]->verdict[0] = 'false';
                $this->data->edgb12[0]->result[0]  = $e->getFormattedMessage();
            }
        }

        $this->render();

        return;
    }

    public function edgb12XpAsync ( ) {

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
