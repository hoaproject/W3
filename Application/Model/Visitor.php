<?php

namespace {

from('Hoa')
-> import('Locale.~')
-> import('Locale.Localizer.Http');

}

namespace Application\Model {

class Visitor {

    protected $_locale = null;



    public function setLocale ( \Hoa\Locale $locale ) {

        $old           = $this->_locale;
        $this->_locale = $locale;

        return $old;
    }

    protected function getLocale ( ) {

        if(null === $this->_locale)
            $this->_locale = new \Hoa\Locale(new \Hoa\Locale\Localizer\Http());

        return $this->_locale;
    }

    public function getLanguage ( ) {

        $language = 'en';

        try {

            $language = $this->getLocale()->getLanguage();
        }
        catch ( \Hoa\Locale\Exception $e ) { }

        return $language;
    }
}

}
