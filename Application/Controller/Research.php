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
        $this->data->ebgd12->result  = '(no error)';
        $this->data->ebgd12->data    = '(no result)';
        $this->data->ebgd12->verdict = 'none';
        $this->data->ebgd12->grammar = <<<GRAMMAR
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

                $this->data->ebgd12[0]->grammar[0] = $data['grammar'];
                $this->data->ebgd12[0]->data[0]    = $data['data'];
                $grammar  = new \Hoa\StringBuffer\Read();
                $grammar->initializeWith($data['grammar']);
                $_grammar = \Hoa\Compiler\Llk::load($grammar);

                if(isset($data['sample'])) {

                    $n     = $data['size'];
                    $_meta = new \Hoa\Compiler\Visitor\Meta(
                        $_grammar,
                        new \Hoa\Test\Sampler\Random(),
                        $n
                    );
                    $this->data->ebgd12[0]->data[0] = $_meta->visit(
                        $_meta->getRuleAst($_grammar->getRootRule())
                    );
                }
                elseif(isset($data['predicate'])) {

                    $_grammar->parse($data['data'], null, false);
                }

                $this->data->ebgd12[0]->verdict[0] = 'true';
            }
            catch ( \Hoa\Compiler\Exception $e ) {

                $this->data->ebgd12[0]->verdict[0] = 'false';
                $this->data->ebgd12[0]->result[0]  = $e->getFormattedMessage();
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
