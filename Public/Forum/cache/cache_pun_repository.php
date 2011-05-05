<?php

if (!defined('PUN_REPOSITORY_EXTENSIONS_LOADED')) define('PUN_REPOSITORY_EXTENSIONS_LOADED', 1);

$pun_repository_extensions = array (
  'pun_antispam' => 
  array (
    'content' => '
	',
    'attributes' => 
    array (
      'engine' => '1.0',
    ),
    'id' => 'pun_antispam',
    'title' => 'Antispam System',
    'version' => '1.2',
    'description' => 'Adds CAPTCHA to the register, login and guest post form.',
    'author' => 'PunBB Development Team',
    'minversion' => '1.3dev',
    'maxtestedon' => '1.3',
  ),
  'pun_bbcode' => 
  array (
    'content' => '
    ',
    'attributes' => 
    array (
      'engine' => '1.0',
    ),
    'id' => 'pun_bbcode',
    'title' => 'BBCode buttons',
    'version' => '1.2.1',
    'description' => 'Pretty buttons for easy BBCode formatting.',
    'author' => 'PunBB Development Team',
    'minversion' => '1.3dev',
    'maxtestedon' => '1.3',
  ),
  'pun_pm' => 
  array (
    'content' => '
	',
    'attributes' => 
    array (
      'engine' => '1.0',
    ),
    'id' => 'pun_pm',
    'title' => 'Private Messaging',
    'version' => '1.2.4',
    'description' => 'Allows users to send private messages. This is the first simple version with minimum functions.',
    'author' => 'PunBB Development Team',
    'minversion' => '1.3',
    'maxtestedon' => '1.3.1',
    'note' => 
    array (
      'content' => 'WARNING! All users\' messages will be removed during the uninstall process. It is strongly recommended you to disable \'Private Messages\' extension instead or to upgrade it without uninstalling.',
      'attributes' => 
      array (
        'type' => 'uninstall',
        'timing' => 'pre',
      ),
    ),
  ),
  'pun_poll' => 
  array (
    'content' => '
	',
    'attributes' => 
    array (
      'engine' => '1.0',
    ),
    'id' => 'pun_poll',
    'title' => 'Pun poll',
    'version' => '1.0.1',
    'description' => 'Adds polls feature for topics.',
    'author' => 'PunBB Development team',
    'minversion' => '1.3 SVN',
    'maxtestedon' => '1.3 SVN',
  ),
  'pun_quote' => 
  array (
    'content' => '
	',
    'attributes' => 
    array (
      'engine' => '1.0',
    ),
    'id' => 'pun_quote',
    'title' => 'JS post quote',
    'version' => '2.0',
    'description' => 'Select the text you want to quote right in the topic view. Click "Quote" for multiple quotes in quick reply form.',
    'author' => 'PunBB Development Team',
    'minversion' => '1.3dev',
    'maxtestedon' => '1.3',
    'note' => 
    array (
      'content' => 'Tested in Internet Explorer 7, FireFox 3, Opera 9.51 and Google Chrome 0.2.',
      'attributes' => 
      array (
        'type' => 'install',
        'timing' => 'pre',
      ),
    ),
  ),
  'pun_repository' => 
  array (
    'content' => '
	',
    'attributes' => 
    array (
      'engine' => '1.0',
    ),
    'id' => 'pun_repository',
    'title' => 'PunBB Repository',
    'version' => '1.2.2',
    'description' => 'Feel free to download and install extensions from PunBB repository.',
    'author' => 'PunBB Development Team',
    'minversion' => '1.3dev',
    'maxtestedon' => '1.3',
    'note' => 
    array (
      'content' => 'Warning: web server should have write access to your extensions directory.',
      'attributes' => 
      array (
        'type' => 'install',
        'timing' => 'pre',
      ),
    ),
  ),
);

$pun_repository_extensions_timestamp = 1230938256;

?>