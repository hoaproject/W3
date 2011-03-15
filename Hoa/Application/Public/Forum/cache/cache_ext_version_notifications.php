<?php

if (!defined('FORUM_EXT_VERSIONS_LOADED')) define('FORUM_EXT_VERSIONS_LOADED', 1);

$forum_ext_repos = array (
  'http://punbb.informer.com/extensions' => 
  array (
    'timestamp' => '1230550097',
    'extension_versions' => 
    array (
      'pun_antispam' => '1.2',
      'pun_bbcode' => '1.2.1',
      'pun_quote' => '2.0',
      'pun_pm' => '1.2.4',
      'pun_repository' => '1.2.2',
    ),
  ),
);

 $forum_ext_last_versions = array (
  'pun_antispam' => 
  array (
    'version' => '1.2',
    'repo_url' => 'http://punbb.informer.com/extensions',
    'changes' => 'Markup update.',
  ),
  'pun_bbcode' => 
  array (
    'version' => '1.2.1',
    'repo_url' => 'http://punbb.informer.com/extensions',
    'changes' => 'Markup updated.',
  ),
  'pun_quote' => 
  array (
    'version' => '2.0',
    'repo_url' => 'http://punbb.informer.com/extensions',
    'changes' => 'Renamed old "Quote" link to the "Reply" (keeping the feature of the quote text selection).
Added new "Quote" link. Click it to copy the whole message or selected part of it to the "Quick Post" form.',
  ),
  'pun_pm' => 
  array (
    'version' => '1.2.4',
    'repo_url' => 'http://punbb.informer.com/extensions',
    'changes' => 'Fixed a bug with including the language file when \'New messages\' link is disabled.',
  ),
  'pun_repository' => 
  array (
    'version' => '1.2.2',
    'repo_url' => 'http://punbb.informer.com/extensions',
    'changes' => 'Improved the error message on directory creation fail, fixed markup and breadcrumbs issues.',
  ),
  'hotfix_13_sql_injection_in_admin_users' => 
  array (
    'version' => '1.0',
    'repo_url' => '',
    'changes' => '',
  ),
  'hotfix_13_sql_injection_in_admin_settings' => 
  array (
    'version' => '1.0',
    'repo_url' => '',
    'changes' => '',
  ),
  'hotfix_13_xss_attack_in_login' => 
  array (
    'version' => '1.0',
    'repo_url' => '',
    'changes' => '',
  ),
  'hotfix_13_incorrect_topic_status_in_search_results' => 
  array (
    'version' => '1.0',
    'repo_url' => '',
    'changes' => '',
  ),
  'hotfix_13_moderate_topics' => 
  array (
    'version' => '1.0',
    'repo_url' => '',
    'changes' => '',
  ),
  'hotfix_13_moderate_xss' => 
  array (
    'version' => '1.0',
    'repo_url' => '',
    'changes' => '',
  ),
);

$forum_ext_versions_update_cache = 1230938256;

?>