<?php

namespace Application\Model;

use Hoa\Locale;

class User
{
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


    public function __construct()
    {
        $this->_locale = new Locale(new Locale\Localizer\Coerce('en'));

        return;
    }

    public function guessLanguage($language)
    {
        if (true === $this->isValidLanguage($language)) {
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
        } catch (Locale\Exception $e) {
        }

        if (false === $this->isValidLanguage($language)) {
            $language = 'en';
            $this->_locale->setLocalizer(new Locale\Localizer\Coerce($language));
        }

        setlocale(LC_ALL, $this->getRegions($language));

        return;
    }

    public function isValidLanguage($language)
    {
        return true === array_key_exists($language, static::$_languages);
    }

    public function getLocale()
    {
        return $this->_locale;
    }

    public function getLanguageName()
    {
        return static::$_languages[$this->_locale->getLanguage()]['name'];
    }

    public function getRegions()
    {
        return static::$_languages[$this->_locale->getLanguage()]['regions'];
    }

    public function getAvailableLanguages()
    {
        $out = [];

        foreach (static::$_languages as $short => $details) {
            $out[$short] = $details['name'];
        }

        return $out;
    }
}
