<?php

namespace {

from('Hoa')
-> import('Router.Exception.NotFound');

}

namespace Application\Controller {

class Exception extends \Hoa\Router\Exception\NotFound { }

}
