<?php

namespace {

from('Hoa')
-> import('Locale.~')
-> import('Locale.Localizer.Http');

}

namespace Application\Model {

class Visitor {

    protected $_locale = null;



    public function getLanguage ( ) {

        $language = 'en';

        try {

            $locale   = new \Hoa\Locale(new \Hoa\Locale\Localizer\Http());
            $language = $locale->getLanguage();
        }
        catch ( \Hoa\Locale\Exception $e ) { }

        return $language;
    }
}

}
