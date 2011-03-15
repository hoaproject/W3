<?php

if (!defined('FORUM_UPDATES_LOADED')) define('FORUM_UPDATES_LOADED', 1);

$forum_updates = array (
  'version' => '1.3.4',
  'hotfix' => 
  array (
    0 => 
    array (
      'content' => 'PunBB 1.3–1.3.2 hotfix removing a notice on updates check.',
      'attributes' => 
      array (
        'id' => 'hotfix_13_updates_cache_notice_removal',
      ),
    ),
    1 => 
    array (
      'content' => 'PunBB 1.3–1.3.2 hotfix for a potential XSS-attack via GET-parameter "p".',
      'attributes' => 
      array (
        'id' => 'hotfix_132_xss_attack_via_get_parameter_p',
      ),
    ),
    2 => 
    array (
      'content' => 'PunBB 1.3-1.3.3 hotfix for a potential XSS attack on password change.',
      'attributes' => 
      array (
        'id' => 'hotfix_133_xss_attack_in_profile',
      ),
    ),
  ),
  'cached' => 1300176077,
  'fail' => false,
);

?>