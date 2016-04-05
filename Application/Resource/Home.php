<?php

namespace Application\Resource;

use Application\Dispatcher\Kit;
use Hoa\Promise;

class Home extends Resource
{
    public function get(Kit $_this)
    {
        $_this
            ->promise
            ->then(curry([$this, 'doTranslation'], …, 'Index', 'Index'))
            ->then(function (Kit $kit) {

                return $this->doTitle(
                    $kit,
                    $kit->view->getTranslation('Index')
                              ->_('Index')
                );
            })
            ->then(curry([$this, 'doMainOverlay'], …, 'Welcome'))
            ->then(function (Kit $kit) {

                $blogApi = $kit->router->unroute('blog') . 'api/posts?limit=5';

                if (false !== $json = @file_get_contents($blogApi)) {
                    if (null !== $handle = json_decode($json, true)) {
                        $kit->data->blog = $handle;
                    }
                }

                return $kit;
            })
            ->then([$this, 'doFooter'])
            ->then([$this, 'doRender']);
    }
}
