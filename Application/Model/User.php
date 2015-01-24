<?php

namespace Application\Model;

use Hoa\Locale;

class User {

    protected $_locale           = null;
    protected static $_languages = [
        'en' => [
            'name'    => 'english',
            'regions' => ['en_GB.UTF-8', 'en_GB.utf8', 'en_GB']
        ],
        'fr' => [
            'name'    => 'français',
            'regions' => ['fr_FR.UTF-8', 'fr_FR.utf8', 'fr_FR']
        ]
    ];


    public function __construct ( ) {

        $this->_locale = new Locale(new Locale\Localizer\Coerce($this->guessDefaultLanguage()));

        return;
    }

    public function isUserLanguage($acceptedLanguage)
    {
        if (preg_match('/(\W)/', $acceptedLanguage, $matches)) {
            $acceptedLanguage = strstr($acceptedLanguage, $matches[0], true);
        }

        if (in_array($acceptedLanguage, array_keys($this::$_languages))) {
            return $acceptedLanguage;
        }

        return '';
    }

    public function guessDefaultLanguage()
    {
        $acceptedLanguages = array_unique(array_map(
                                              array($this, 'isUserLanguage'),
                                              explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])
                                          ));

        return (!$acceptedLanguages[0]) ? key($this::$_languages) : $acceptedLanguages[0];
    }

    public function guessLanguage ( $language ) {

        if(true === $this->isValidLanguage($language)) {

            $this->_locale->setLocalizer(
                new Locale\Localizer\Coerce($language)
            );
            setlocale(LC_ALL, $this->getRegions($language));

            return;
        }

        $language = null;

        try {

            $this->_locale->setLocalizer(new Locale\Localizer\Http());
            $language = $this->_locale->getLanguage();
        }
        catch ( Locale\Exception $e ) { }

        if(false === $this->isValidLanguage($language)) {

            $language = 'en';
            $this->_locale->setLocalizer(new Locale\Localizer\Coerce($language));
        }

        setlocale(LC_ALL, $this->getRegions($language));

        return;
    }

    public function isValidLanguage ( $language ) {

        return true === array_key_exists($language, static::$_languages);
    }

    public function getLocale ( ) {

        return $this->_locale;
    }

    public function getLanguageName ( ) {

        return static::$_languages[$this->_locale->getLanguage()]['name'];
    }

    public function getRegions ( ) {

        return static::$_languages[$this->_locale->getLanguage()]['regions'];
    }

    public function getAvailableLanguages ( ) {

        $out = [];

        foreach(static::$_languages as $short => $details)
            $out[$short] = $details['name'];

        return $out;
    }
}
