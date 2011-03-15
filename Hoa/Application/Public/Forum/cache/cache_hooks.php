<?php

define('FORUM_HOOKS_LOADED', 1);

$forum_hooks = array (
  'aus_find_user_qr_find_users' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'hotfix_13_sql_injection_in_admin_users\',
\'path\'			=> FORUM_ROOT.\'extensions/hotfix_13_sql_injection_in_admin_users\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/hotfix_13_sql_injection_in_admin_users\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

//Check up for order_by and direction values
$order_by = isset($_POST[\'order_by\']) ? forum_trim($_POST[\'order_by\']) : null;
$direction = isset($_POST[\'direction\']) ? forum_trim($_POST[\'direction\']) : null;
if ($order_by == null || $direction == null)
	message($lang_common[\'Bad request\']);
if (!in_array($order_by, array(\'username\', \'email\', \'num_posts\', \'num_posts\', \'registered\')) || !in_array($direction, array(\'ASC\', \'DESC\')))
	message($lang_common[\'Bad request\']);
$query[\'ORDER BY\'] = $order_by.\' \'.$direction;

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'aop_qr_update_permission_conf' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'hotfix_13_sql_injection_in_admin_settings\',
\'path\'			=> FORUM_ROOT.\'extensions/hotfix_13_sql_injection_in_admin_settings\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/hotfix_13_sql_injection_in_admin_settings\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

$query[\'SET\'] = \'conf_value=\'.intval($input);

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'aex_section_manage_end' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_repository\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_repository\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_repository\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\'))
	include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\';
else
	include $ext_info[\'path\'].\'/lang/English/pun_repository.php\';

require_once $ext_info[\'path\'].\'/pun_repository.php\';

($hook = get_hook(\'pun_repository_pre_display_ext_list\')) ? eval($hook) : null;

?>
	<div class="main-subhead">
		<h2 class="hn"><span><?php echo $lang_pun_repository[\'PunBB Repository\'] ?></span></h2>
	</div>
	<div class="main-content main-extensions">
		<p class="content-options options"><a href="<?php echo $base_url ?>/admin/extensions.php?pun_repository_update&amp;csrf_token=<?php echo generate_form_token(\'pun_repository_update\') ?>"><?php echo $lang_pun_repository[\'Clear cache\'] ?></a></p>
<?php

if (!defined(\'PUN_REPOSITORY_EXTENSIONS_LOADED\') && file_exists(FORUM_CACHE_DIR.\'cache_pun_repository.php\'))
	include FORUM_CACHE_DIR.\'cache_pun_repository.php\';

if (!defined(\'FORUM_EXT_VERSIONS_LOADED\') && file_exists(FORUM_CACHE_DIR.\'cache_ext_version_notifications.php\'))
	include FORUM_CACHE_DIR.\'cache_ext_version_notifications.php\';

// Regenerate cache only if automatic updates are enabled and if the cache is more than 12 hours old
if (!defined(\'PUN_REPOSITORY_EXTENSIONS_LOADED\') || !defined(\'FORUM_EXT_VERSIONS_LOADED\') || ($pun_repository_extensions_timestamp < $forum_ext_versions_update_cache))
{
	$pun_repository_error = \'\';

	if (pun_repository_generate_cache($pun_repository_error))
	{
		require FORUM_CACHE_DIR.\'cache_pun_repository.php\';
	}
	else
	{

		?>
		<div class="ct-box warn-box">
			<p class="warn"><?php echo $pun_repository_error ?></p>
		</div>
		<?php

		// Stop processing hook
		return;
	}
}

$pun_repository_parsed = array();
$pun_repository_skipped = array();

// Display information about extensions in repository
foreach ($pun_repository_extensions as $pun_repository_ext)
{
	// Skip installed extensions
	if (isset($inst_exts[$pun_repository_ext[\'id\']]))
	{
		$pun_repository_skipped[\'installed\'][] = $pun_repository_ext[\'id\'];
		continue;
	}

	// Skip uploaded extensions (including incorrect ones)
	if (is_dir(FORUM_ROOT.\'extensions/\'.$pun_repository_ext[\'id\']))
	{
		$pun_repository_skipped[\'has_dir\'][] = $pun_repository_ext[\'id\'];
		continue;
	}

	// Check for unresolved dependencies
	if (isset($pun_repository_ext[\'dependencies\']))
		$pun_repository_ext[\'dependencies\'] = pun_repository_check_dependencies($inst_exts, $pun_repository_ext[\'dependencies\']);

	if (empty($pun_repository_ext[\'dependencies\'][\'unresolved\']))
	{
		// \'Download and install\' link
		$pun_repository_ext[\'options\'] = array(\'<a href="\'.$base_url.\'/admin/extensions.php?pun_repository_download_and_install=\'.$pun_repository_ext[\'id\'].\'&amp;csrf_token=\'.generate_form_token(\'pun_repository_download_and_install_\'.$pun_repository_ext[\'id\']).\'">\'.$lang_pun_repository[\'Download and install\'].\'</a>\');
	}
	else
		$pun_repository_ext[\'options\'] = array();

	$pun_repository_parsed[] = $pun_repository_ext[\'id\'];

	// Direct links to archives
	$pun_repository_ext[\'download_links\'] = array();
	foreach (array(\'zip\', \'tgz\', \'7z\') as $pun_repository_archive_type)
		$pun_repository_ext[\'download_links\'][] = \'<a href="\'.PUN_REPOSITORY_URL.\'/\'.$pun_repository_ext[\'id\'].\'/\'.$pun_repository_ext[\'id\'].\'.\'.$pun_repository_archive_type.\'">\'.$pun_repository_archive_type.\'</a>\';

	($hook = get_hook(\'pun_repository_pre_display_ext_info\')) ? eval($hook) : null;

	// Let\'s ptint it all out
?>
		<div class="ct-box info-box extension available" id="<?php echo $pun_repository_ext[\'id\'] ?>">
			<h3 class="ct-legend hn"><span><?php echo forum_htmlencode($pun_repository_ext[\'title\']).\' \'.$pun_repository_ext[\'version\'] ?></span></h3>
			<p><?php echo forum_htmlencode($pun_repository_ext[\'description\']) ?></p>
<?php

	// List extension dependencies
	if (!empty($pun_repository_ext[\'dependencies\'][\'dependency\']))
		echo \'
			<p>\', $lang_pun_repository[\'Dependencies:\'], \' \', implode(\', \', $pun_repository_ext[\'dependencies\'][\'dependency\']), \'</p>\';

?>
			<p><?php echo $lang_pun_repository[\'Direct download links:\'], \' \', implode(\' \', $pun_repository_ext[\'download_links\']) ?></p>
<?php

	// List unresolved dependencies
	if (!empty($pun_repository_ext[\'dependencies\'][\'unresolved\']))
		echo \'
			<div class="ct-box warn-box">
				<p class="warn">\', $lang_pun_repository[\'Resolve dependencies:\'], \' \', implode(\', \', array_map(create_function(\'$dep\', \'return \\\'<a href="#\\\'.$dep.\\\'">\\\'.$dep.\\\'</a>\\\';\'), $pun_repository_ext[\'dependencies\'][\'unresolved\'])), \'</p>
			</div>\';

	// Actions
	if (!empty($pun_repository_ext[\'options\']))
		echo \'
			<p class="options">\', implode(\' \', $pun_repository_ext[\'options\']), \'</p>\';

?>
		</div>
<?php

}

?>
		<div class="ct-box warn-box">
			<p class="warn"><?php echo $lang_pun_repository[\'Files mode and owner\'] ?></p>
		</div>
<?php

if (empty($pun_repository_parsed) && (count($pun_repository_skipped[\'installed\']) > 0 || count($pun_repository_skipped[\'has_dir\']) > 0))
{
	($hook = get_hook(\'pun_repository_no_extensions\')) ? eval($hook) : null;

	?>
		<div class="ct-box info-box">
			<p><?php echo $lang_pun_repository[\'All installed or downloaded\'] ?></p>
		</div>
	<?php

}

($hook = get_hook(\'pun_repository_after_ext_list\')) ? eval($hook) : null;

?>
	</div>
<?php

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'aex_new_action' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_repository\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_repository\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_repository\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

// Clear pun_repository cache
if (isset($_GET[\'pun_repository_update\']))
{
	// Validate CSRF token
	if (!isset($_POST[\'csrf_token\']) && (!isset($_GET[\'csrf_token\']) || $_GET[\'csrf_token\'] !== generate_form_token(\'pun_repository_update\')))
		csrf_confirm_form();

	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\'))
		include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\';
	else
		include $ext_info[\'path\'].\'/lang/English/pun_repository.php\';

	@unlink(FORUM_CACHE_DIR.\'cache_pun_repository.php\');
	if (file_exists(FORUM_CACHE_DIR.\'cache_pun_repository.php\'))
		message($lang_pun_repository[\'Unable to remove cached file\'], \'\', $lang_pun_repository[\'PunBB Repository\']);

	redirect($base_url.\'/admin/extensions.php?section=manage\', $lang_pun_repository[\'Cache has been successfully cleared\']);
}

if (isset($_GET[\'pun_repository_download_and_install\']))
{
	$ext_id = preg_replace(\'/[^0-9a-z_]/\', \'\', $_GET[\'pun_repository_download_and_install\']);

	// Validate CSRF token
	if (!isset($_POST[\'csrf_token\']) && (!isset($_GET[\'csrf_token\']) || $_GET[\'csrf_token\'] !== generate_form_token(\'pun_repository_download_and_install_\'.$ext_id)))
		csrf_confirm_form();

	// TODO: Should we check again for unresolved dependencies here?

	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\'))
		include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\';
	else
		include $ext_info[\'path\'].\'/lang/English/pun_repository.php\';

	require_once $ext_info[\'path\'].\'/pun_repository.php\';

	($hook = get_hook(\'pun_repository_download_and_install_start\')) ? eval($hook) : null;

	// Download extension
	$pun_repository_error = pun_repository_download_extension($ext_id, $ext_data);

	if ($pun_repository_error == \'\')
	{
		if (empty($ext_data))
			redirect($base_url.\'/admin/extensions.php?section=manage\', $lang_pun_repository[\'Incorrect manifest.xml\']);

		// Validate manifest
		$errors = validate_manifest($ext_data, $ext_id);
		if (!empty($errors))
			redirect($base_url.\'/admin/extensions.php?section=manage\', $lang_pun_repository[\'Incorrect manifest.xml\']);

		// Everything is OK. Start installation.
		redirect($base_url.\'/admin/extensions.php?install=\'.urlencode($ext_id), $lang_pun_repository[\'Download successful\']);
	}

	($hook = get_hook(\'pun_repository_download_and_install_end\')) ? eval($hook) : null;
}

// Handling the download and update extension action
if (isset($_GET[\'pun_repository_download_and_update\']))
{
	$ext_id = preg_replace(\'/[^0-9a-z_]/\', \'\', $_GET[\'pun_repository_download_and_update\']);

	// Validate CSRF token
	if (!isset($_POST[\'csrf_token\']) && (!isset($_GET[\'csrf_token\']) || $_GET[\'csrf_token\'] !== generate_form_token(\'pun_repository_download_and_update_\'.$ext_id)))
		csrf_confirm_form();

	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\'))
		include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\';
	else
		include $ext_info[\'path\'].\'/lang/English/pun_repository.php\';

	require_once $ext_info[\'path\'].\'/pun_repository.php\';

	$pun_repository_error = \'\';

	($hook = get_hook(\'pun_repository_download_and_update_start\')) ? eval($hook) : null;

	@pun_repository_rm_recursive(FORUM_ROOT.\'extensions/\'.$ext_id.\'.old\');

	// Check dependancies
	$query = array(
		\'SELECT\'	=> \'e.id\',
		\'FROM\'		=> \'extensions AS e\',
		\'WHERE\'		=> \'e.disabled=0 AND e.dependencies LIKE \\\'%|\'.$forum_db->escape($ext_id).\'|%\\\'\'
	);

	($hook = get_hook(\'aex_qr_get_disable_dependencies\')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	if ($forum_db->num_rows($result) != 0)
	{
		$dependency = $forum_db->fetch_assoc($result);
		$pun_repository_error = sprintf($lang_admin[\'Disable dependency\'], $dependency[\'id\']);
	}

	if ($pun_repository_error == \'\' && ($ext_id != $ext_info[\'id\']))
	{
		// Disable extension
		$query = array(
			\'UPDATE\'	=> \'extensions\',
			\'SET\'		=> \'disabled=1\',
			\'WHERE\'		=> \'id=\\\'\'.$forum_db->escape($ext_id).\'\\\'\'
		);

		($hook = get_hook(\'aex_qr_update_disabled_status\')) ? eval($hook) : null;
		$forum_db->query_build($query) or error(__FILE__, __LINE__);

		// Regenerate the hooks cache
		require_once FORUM_ROOT.\'include/cache.php\';
		generate_hooks_cache();
	}

	if ($pun_repository_error == \'\')
	{
		if ($ext_id == $ext_info[\'id\'])
		{
			// Hey! That\'s me!
			// All the necessary files should be included before renaming old directory
			// NOTE: Self-updating is to be tested more in real-life conditions
			if (!defined(\'PUN_REPOSITORY_TAR_EXTRACT_INCLUDED\'))
				require $ext_info[\'path\'].\'/pun_repository_tar_extract.php\';
		}

		// Rename old extension dir
		if (is_writable(FORUM_ROOT.\'extensions/\'.$ext_id) && @rename(FORUM_ROOT.\'extensions/\'.$ext_id, FORUM_ROOT.\'extensions/\'.$ext_id.\'.old\'))
			$pun_repository_error = pun_repository_download_extension($ext_id, $ext_data); // Download extension
		else
			$pun_repository_error = sprintf($lang_pun_repository[\'Unable to rename old dir\'], FORUM_ROOT.\'extensions/\'.$ext_id);
	}

	if ($pun_repository_error == \'\')
	{
		// Do we have extension dat at all? :-)
		if (empty($ext_data))
			$errors = array(true);

		// Validate manifest
		if (empty($errors))
			$errors = validate_manifest($ext_data, $ext_id);

		if (!empty($errors))
			$pun_repository_error = $lang_pun_repository[\'Incorrect manifest.xml\'];
	}

	if ($pun_repository_error == \'\')
	{
		($hook = get_hook(\'pun_repository_download_and_update_ok\')) ? eval($hook) : null;

		// Everything is OK. Start installation.
		pun_repository_rm_recursive(FORUM_ROOT.\'extensions/\'.$ext_id.\'.old\');
		redirect($base_url.\'/admin/extensions.php?install=\'.urlencode($ext_id), $lang_pun_repository[\'Download successful\']);
	}

	($hook = get_hook(\'pun_repository_download_and_update_error\')) ? eval($hook) : null;

	// Get old version back
	@pun_repository_rm_recursive(FORUM_ROOT.\'extensions/\'.$ext_id);
	@rename(FORUM_ROOT.\'extensions/\'.$ext_id.\'.old\', FORUM_ROOT.\'extensions/\'.$ext_id);

	// Enable extension
	$query = array(
		\'UPDATE\'	=> \'extensions\',
		\'SET\'		=> \'disabled=0\',
		\'WHERE\'		=> \'id=\\\'\'.$forum_db->escape($ext_id).\'\\\'\'
	);

	($hook = get_hook(\'aex_qr_update_enabled_status\')) ? eval($hook) : null;
	$forum_db->query_build($query) or error(__FILE__, __LINE__);

	// Regenerate the hooks cache
	require_once FORUM_ROOT.\'include/cache.php\';
	generate_hooks_cache();

	($hook = get_hook(\'pun_repository_download_and_update_end\')) ? eval($hook) : null;
}

// Do we have some error?
if (!empty($pun_repository_error))
{
	// Setup breadcrumbs
	$forum_page[\'crumbs\'] = array(
		array($forum_config[\'o_board_title\'], forum_link($forum_url[\'index\'])),
		array($lang_admin_common[\'Forum administration\'], forum_link($forum_url[\'admin_index\'])),
		array($lang_admin_common[\'Extensions\'], forum_link($forum_url[\'admin_extensions_manage\'])),
		array($lang_admin_common[\'Install extensions\'], forum_link($forum_url[\'admin_extensions_install\'])),
		$lang_pun_repository[\'PunBB Repository\']
	);

	($hook = get_hook(\'pun_repository__pre_header_load\')) ? eval($hook) : null;

	define(\'FORUM_PAGE_SECTION\', \'extensions\');
	define(\'FORUM_PAGE\', \'admin-extensions-pun-repository\');
	require FORUM_ROOT.\'header.php\';

	// START SUBST - <!-- forum_main -->
	ob_start();

	($hook = get_hook(\'pun_repository_display_error_output_start\')) ? eval($hook) : null;

?>
	<div class="main-subhead">
		<h2 class="hn"><span><?php echo $lang_pun_repository[\'PunBB Repository\'] ?></span></h2>
	</div>
	<div class="main-content">
		<div class="ct-box warn-box">
			<p class="warn"><?php echo $pun_repository_error ?></p>
		</div>
	</div>
</div>
<?php

	($hook = get_hook(\'pun_repository_display_error_pre_ob_end\')) ? eval($hook) : null;

	$tpl_temp = trim(ob_get_contents());
	$tpl_main = str_replace(\'<!-- forum_main -->\', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->

	require FORUM_ROOT.\'footer.php\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'aex_section_manage_pre_header_load' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_repository\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_repository\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_repository\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\'))
	include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/pun_repository.php\';
else
	include $ext_info[\'path\'].\'/lang/English/pun_repository.php\';

require_once $ext_info[\'path\'].\'/pun_repository.php\';

if (!defined(\'PUN_REPOSITORY_EXTENSIONS_LOADED\') && file_exists(FORUM_CACHE_DIR.\'cache_pun_repository.php\'))
	include FORUM_CACHE_DIR.\'cache_pun_repository.php\';

if (!defined(\'FORUM_EXT_VERSIONS_LOADED\') && file_exists(FORUM_CACHE_DIR.\'cache_ext_version_notifications.php\'))
	include FORUM_CACHE_DIR.\'cache_ext_version_notifications.php\';

// Regenerate cache only if automatic updates are enabled and if the cache is more than 12 hours old
if (!defined(\'PUN_REPOSITORY_EXTENSIONS_LOADED\') || !defined(\'FORUM_EXT_VERSIONS_LOADED\') || ($pun_repository_extensions_timestamp < $forum_ext_versions_update_cache))
{
	if (pun_repository_generate_cache($pun_repository_error))
		require FORUM_CACHE_DIR.\'cache_pun_repository.php\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'aex_section_manage_pre_ext_actions' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_repository\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_repository\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_repository\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (defined(\'PUN_REPOSITORY_EXTENSIONS_LOADED\') && isset($pun_repository_extensions[$id]) && version_compare($ext[\'version\'], $pun_repository_extensions[$id][\'version\'], \'<\') && is_writable(FORUM_ROOT.\'extensions/\'.$id))
{
	// Check for unresolved dependencies
	if (isset($pun_repository_extensions[$id][\'dependencies\']))
		$pun_repository_extensions[$id][\'dependencies\'] = pun_repository_check_dependencies($inst_exts, $pun_repository_extensions[$id][\'dependencies\']);

	if (empty($pun_repository_extensions[$id][\'dependencies\'][\'unresolved\']))
		$forum_page[\'ext_actions\'][] = \'<a href="\'.$base_url.\'/admin/extensions.php?pun_repository_download_and_update=\'.$id.\'&amp;csrf_token=\'.generate_form_token(\'pun_repository_download_and_update_\'.$id).\'">\'.$lang_pun_repository[\'Download and update\'].\'</a>\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'co_common' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_repository\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_repository\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_repository\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

$pun_extensions_used = array_merge(isset($pun_extensions_used) ? $pun_extensions_used : array(), array($ext_info[\'id\']));

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
    1 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

$pun_extensions_used = array_merge(isset($pun_extensions_used) ? $pun_extensions_used : array(), array($ext_info[\'id\']));

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
    2 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

$pun_extensions_used = array_merge(isset($pun_extensions_used) ? $pun_extensions_used : array(), array($ext_info[\'id\']));

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
    3 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_quote\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_quote\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_quote\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

$pun_extensions_used = array_merge(isset($pun_extensions_used) ? $pun_extensions_used : array(), array($ext_info[\'id\']));

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
    4 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

$pun_extensions_used = array_merge(isset($pun_extensions_used) ? $pun_extensions_used : array(), array($ext_info[\'id\']));

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'hd_head' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (FORUM_PAGE == \'viewtopic\' || FORUM_PAGE == \'postedit\' || FORUM_PAGE == \'post\' && $forum_user[\'pun_bbcode_enabled\'] == \'1\')
			{
				require_once FORUM_ROOT.\'include/parser.php\';

				$forum_head[\'style_pun_bbcode\'] = \'<link rel="stylesheet" type="text/css" media="screen" href="\'.$ext_info[\'url\'].\'/styles.css" />\';
				$forum_head[\'js_pun_bbcode\'] = \'<script type="text/javascript" src="\'.$ext_info[\'url\'].\'/scripts.js"></script>\';
			}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
    1 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

// Incuding styles for pun_pm
if (defined(\'FORUM_PAGE\') && \'pun_pm\' == substr(FORUM_PAGE, 0, 6))
{
	if (file_exists($ext_info[\'path\'].\'/styles/\'.$forum_user[\'style\'].\'/\'))
		$forum_head[\'style_pun_pm\'] = \'<link rel="stylesheet" type="text/css" media="screen" href="\'.$ext_info[\'url\'].\'/styles/\'.$forum_user[\'style\'].\'/style.css" />\';
	else
		$forum_head[\'style_pun_pm\'] = \'<link rel="stylesheet" type="text/css" media="screen" href="\'.$ext_info[\'url\'].\'/styles/Oxygen/style.css" />\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'po_pre_post_contents' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if ($forum_user[\'pun_bbcode_enabled\'] == \'1\')
	include $ext_info[\'path\'].\'/bar.php\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'vt_quickpost_pre_message_box' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if ($forum_user[\'pun_bbcode_enabled\'] == \'1\')
	include $ext_info[\'path\'].\'/bar.php\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'ed_pre_message_box' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if ($forum_user[\'pun_bbcode_enabled\'] == \'1\')
	include $ext_info[\'path\'].\'/bar.php\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'pf_change_details_settings_validation' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (!isset($_POST[\'form\'][\'pun_bbcode_enabled\']) || $_POST[\'form\'][\'pun_bbcode_enabled\'] != \'1\')
	$form[\'pun_bbcode_enabled\'] = \'0\';
else
	$form[\'pun_bbcode_enabled\'] = \'1\';

if (!isset($_POST[\'form\'][\'pun_bbcode_use_buttons\']) || $_POST[\'form\'][\'pun_bbcode_use_buttons\'] != \'1\')
	$form[\'pun_bbcode_use_buttons\'] = \'0\';
else
	$form[\'pun_bbcode_use_buttons\'] = \'1\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
    1 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

// Validate option \'quote beginning of message\'
if (!isset($_POST[\'form\'][\'pun_pm_long_subject\']) || $_POST[\'form\'][\'pun_pm_long_subject\'] != \'1\')
	$form[\'pun_pm_long_subject\'] = \'0\';
else
	$form[\'pun_pm_long_subject\'] = \'1\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'pf_change_details_settings_email_fieldset_end' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
	include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
else
	include $ext_info[\'path\'].\'/lang/English/pun_bbcode.php\';

$forum_page[\'item_count\'] = 0;

?>
			<fieldset class="frm-group group<?php echo ++$forum_page[\'group_count\'] ?>">
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_bbcode_enabled]" value="1"<?php if ($user[\'pun_bbcode_enabled\'] == \'1\') echo \' checked="checked"\' ?> /></span>
						<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_bbcode[\'Pun BBCode Bar\'] ?></span> <?php echo $lang_pun_bbcode[\'Notice BBCode Bar\'] ?></label>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_bbcode_use_buttons]" value="1"<?php if ($user[\'pun_bbcode_use_buttons\'] == \'1\') echo \' checked="checked"\' ?> /></span>
						<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_bbcode[\'BBCode Graphical\'] ?></span> <?php echo $lang_pun_bbcode[\'BBCode Graphical buttons\'] ?></label>
					</div>
				</div>
			</fieldset>
<?php

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
    1 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

// Per-user option \'quote beginning of message\'
if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
	include $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
else
	include $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';

$forum_page[\'item_count\'] = 0;

?>
			<fieldset class="frm-group group<?php echo ++$forum_page[\'group_count\'] ?>">
				<legend class="group-legend"><strong><?php echo $lang_pun_pm[\'PM settings\'] ?></strong></legend>
				<fieldset class="mf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<legend><span><?php echo $lang_pun_pm[\'Private messages\'] ?></span></legend>
					<div class="mf-box">
						<div class="mf-item">
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_pm_long_subject]" value="1"<?php if ($user[\'pun_pm_long_subject\'] == \'1\') echo \' checked="checked"\' ?> /></span>
							<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $lang_pun_pm[\'Begin message quote\'] ?></label>
						</div>
					</div>
				</fieldset>
			</fieldset>
<?php

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'mi_new_action' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if ($action == \'pun_pm_send\' && !$forum_user[\'is_guest\'])
{
	include_once $ext_info[\'path\'].\'/functions.php\';

	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
		include_once $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
	else
		include_once $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';

	$pun_pm_body = isset($_POST[\'pm_body\']) ? $_POST[\'pm_body\'] : \'\';
	$pun_pm_subject = isset($_POST[\'pm_subject\']) ? $_POST[\'pm_subject\'] : \'\';
	$pun_pm_receiver_username = isset($_POST[\'pm_receiver\']) ? $_POST[\'pm_receiver\'] : \'\';
	$pun_pm_message_id = isset($_POST[\'message_id\']) ? $_POST[\'message_id\'] : false;

	if (isset($_POST[\'send_action\']) && in_array($_POST[\'send_action\'], array(\'send\', \'draft\', \'delete\', \'preview\')))
		$pun_pm_send_action = $_POST[\'send_action\'];
	elseif (isset($_POST[\'pm_draft\']))
		$pun_pm_send_action = \'draft\';
	elseif (isset($_POST[\'pm_send\']))
		$pun_pm_send_action = \'send\';
	elseif (isset($_POST[\'pm_delete\']))
		$pun_pm_send_action = \'delete\';
	else
		$pun_pm_send_action = \'preview\';

	if ($pun_pm_send_action == \'draft\')
	{
		// Try to save the message as draft
		// Inside this function will be a redirect, if everything is ok
		$pun_pm_errors = pun_pm_save_message($pun_pm_body, $pun_pm_subject, $pun_pm_receiver_username, $pun_pm_message_id);
		// Remember $pun_pm_message_id = false; inside this function if $pun_pm_message_id is incorrect

		// Well... Go processing errors

		// We need no preview
		$pun_pm_msg_preview = false;
	}
	elseif ($pun_pm_send_action == \'send\')
	{
		// Try to send the message
		// Inside this function will be a redirect, if everything is ok
		$pun_pm_errors = pun_pm_send_message($pun_pm_body, $pun_pm_subject, $pun_pm_receiver_username, $pun_pm_message_id);
		// Remember $pun_pm_message_id = false; inside this function if $pun_pm_message_id is incorrect

		// Well... Go processing errors

		// We need no preview
		$pun_pm_msg_preview = false;
	}
	elseif ($pun_pm_send_action == \'delete\' && $pun_pm_message_id !== false)
	{
		pun_pm_delete_from_outbox(array((int) $pun_pm_message_id));
		redirect(forum_link($forum_url[\'pun_pm_outbox\']), $lang_pun_pm[\'Message deleted\']);
	}
	else
	{
		// Preview message
		$pun_pm_errors = array();
		$pun_pm_msg_preview = pun_pm_preview($pun_pm_receiver_username, $pun_pm_subject, $pun_pm_body, $pun_pm_errors);
	}

	$pun_pm_page_text = pun_pm_send_form($pun_pm_receiver_username, $pun_pm_subject, $pun_pm_body, $pun_pm_message_id, false, false, $pun_pm_msg_preview);

	// Setup navigation menu
	$forum_page[\'main_menu\'] = array(
		\'inbox\'		=> \'<li><a href="\'.forum_link($forum_url[\'pun_pm_inbox\']).\'"><span>\'.$lang_pun_pm[\'Inbox\'].\'</span></a></li>\',
		\'outbox\'	=> \'<li><a href="\'.forum_link($forum_url[\'pun_pm_outbox\']).\'"><span>\'.$lang_pun_pm[\'Outbox\'].\'</span></a></li>\',
		\'write\'		=> \'<li class="active"><a href="\'.forum_link($forum_url[\'pun_pm_write\']).\'"><span>\'.$lang_pun_pm[\'Compose message\'].\'</span></a></li>\',
	);

	// Setup breadcrumbs
	$forum_page[\'crumbs\'] = array(
		array($forum_config[\'o_board_title\'], forum_link($forum_url[\'index\'])),
		array($lang_pun_pm[\'Private messages\'], forum_link($forum_url[\'pun_pm\'])),
		array($lang_pun_pm[\'Compose message\'], forum_link($forum_url[\'pun_pm_write\']))
	);

	define(\'FORUM_PAGE\', \'pun_pm-write\');
	require FORUM_ROOT.\'header.php\';

	// START SUBST - <!-- forum_main -->
	ob_start();

	echo $pun_pm_page_text;

	$tpl_temp = trim(ob_get_contents());
	$tpl_main = str_replace(\'<!-- forum_main -->\', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->

	require FORUM_ROOT.\'footer.php\';
}

$section = isset($_GET[\'section\']) ? $_GET[\'section\'] : null;

if ($section == \'pun_pm\' && !$forum_user[\'is_guest\'])
{
	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
		require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
	else
		require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';

	require $ext_info[\'path\'].\'/functions.php\';

	$pun_pm_page = isset($_GET[\'pmpage\']) ? $_GET[\'pmpage\'] : \'\';

	// pun_pm_get_page() performs everything :)
	// Remember $pun_pm_page correction inside pun_pm_get_page() if this variable is incorrect
	$pun_pm_page_text = pun_pm_get_page($pun_pm_page);

	// Setup navigation menu
	$forum_page[\'main_menu\'] = array(
		\'inbox\'		=> \'<li\'.(($pun_pm_page == \'inbox\')  ? \' class="active"\' : \'\').\'><a href="\'.forum_link($forum_url[\'pun_pm_inbox\']).\'"><span>\'.$lang_pun_pm[\'Inbox\'].\'</span></a></li>\',
		\'outbox\'	=> \'<li\'.(($pun_pm_page == \'outbox\') ? \' class="active"\' : \'\').\'><a href="\'.forum_link($forum_url[\'pun_pm_outbox\']).\'"><span>\'.$lang_pun_pm[\'Outbox\'].\'</span></a></li>\',
		\'write\'		=> \'<li\'.(($pun_pm_page == \'write\') ? \' class="active"\' : \'\').\'><a href="\'.forum_link($forum_url[\'pun_pm_write\']).\'"><span>\'.$lang_pun_pm[\'Compose message\'].\'</span></a></li>\',
	);

	// Setup breadcrumbs
	$forum_page[\'crumbs\'] = array(
		array($forum_config[\'o_board_title\'], forum_link($forum_url[\'index\'])),
		array($lang_pun_pm[\'Private messages\'], forum_link($forum_url[\'pun_pm\']))
	);
	if ($pun_pm_page == \'inbox\')
		$forum_page[\'crumbs\'][] = array($lang_pun_pm[\'Inbox\'], forum_link($forum_url[\'pun_pm_inbox\']));
	else if ($pun_pm_page == \'outbox\')
		$forum_page[\'crumbs\'][] = array($lang_pun_pm[\'Outbox\'], forum_link($forum_url[\'pun_pm_outbox\']));
	else if ($pun_pm_page == \'write\')
		$forum_page[\'crumbs\'][] = array($lang_pun_pm[\'Compose message\'], forum_link($forum_url[\'pun_pm_write\']));

	define(\'FORUM_PAGE\', \'pun_pm-\'.$pun_pm_page);
	require FORUM_ROOT.\'header.php\';

	// START SUBST - <!-- forum_main -->
	ob_start();

	echo $pun_pm_page_text;

	$tpl_temp = trim(ob_get_contents());
	$tpl_main = str_replace(\'<!-- forum_main -->\', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->

	require FORUM_ROOT.\'footer.php\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'aop_features_avatars_fieldset_end' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

// Admin options
if (!isset($lang_pun_pm))
{
	if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
		require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
	else
		require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
}

$forum_page[\'group_count\'] = $forum_page[\'item_count\'] = 0;

?>
			<div class="content-head">
				<h2 class="hn"><span><?php echo $lang_pun_pm[\'Features title\'] ?></span></h2>
			</div>
			<fieldset class="frm-group group<?php echo ++$forum_page[\'group_count\'] ?>">
				<legend class="group-legend"><span><?php echo $lang_pun_pm[\'PM settings\'] ?></span></legend>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_pm[\'Inbox limit\'] ?></span><small><?php echo $lang_pun_pm[\'Inbox limit info\'] ?></small></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="form[pun_pm_inbox_size]" size="6" maxlength="6" value="<?php echo $forum_config[\'o_pun_pm_inbox_size\'] ?>" /></span>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_pm[\'Outbox limit\'] ?></span><small><?php echo $lang_pun_pm[\'Outbox limit info\'] ?></small></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="form[pun_pm_outbox_size]" size="6" maxlength="6" value="<?php echo $forum_config[\'o_pun_pm_outbox_size\'] ?>" /></span>
					</div>
				</div>
				<fieldset class="mf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<legend><span><?php echo $lang_pun_pm[\'Navigation links\'] ?></span></legend>
					<div class="mf-box">
						<div class="mf-item">
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_pm_show_new_count]" value="1"<?php if ($forum_config[\'o_pun_pm_show_new_count\'] == \'1\') echo \' checked="checked"\' ?> /></span>
							<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $lang_pun_pm[\'Snow new count\'] ?></label>
						</div>
						<div class="mf-item">
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_pm_show_global_link]" value="1"<?php if ($forum_config[\'o_pun_pm_show_global_link\'] == \'1\') echo \' checked="checked"\' ?> /></span>
							<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><?php echo $lang_pun_pm[\'Show global link\'] ?></label>
						</div>
					</div>
				</fieldset>
			</fieldset>
<?php

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
    1 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

$forum_page[\'group_count\'] = $forum_page[\'item_count\'] = 0;

?>
			<div class="content-head">
				<h2 class="hn"><span><?php echo $lang_pun_antispam[\'Captcha admin head\'] ?></span></h2>
			</div>
			<div class="ct-box"><p><?php echo $lang_pun_antispam[\'Captcha admin info\'] ?></p></div>
			<fieldset class="frm-group group<?php echo ++$forum_page[\'group_count\'] ?>">
				<legend class="group-legend"><span><?php echo $lang_pun_antispam[\'Captcha admin legend\'] ?></span></legend>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_antispam_captcha_register]" value="1"<?php if ($forum_config[\'o_pun_antispam_captcha_register\'] == \'1\') echo \' checked="checked"\' ?> /></span>
						<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_antispam[\'Captcha admin legend\'] ?></span><?php echo $lang_pun_antispam[\'Captcha registrations info\'] ?></label>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_antispam_captcha_login]" value="1"<?php if ($forum_config[\'o_pun_antispam_captcha_login\'] == \'1\') echo \' checked="checked"\' ?> /></span>
						<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"> <?php echo $lang_pun_antispam[\'Captcha login info\'] ?></label>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_antispam_captcha_guestpost]" value="1"<?php if ($forum_config[\'o_pun_antispam_captcha_guestpost\'] == \'1\') echo \' checked="checked"\' ?> /></span>
						<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"> <?php echo $lang_pun_antispam[\'Captcha guestpost info\'] ?></label>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="form[pun_antispam_captcha_restorepass]" value="1"<?php if ($forum_config[\'o_pun_antispam_captcha_restorepass\'] == \'1\') echo \' checked="checked"\' ?> /></span>
						<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"> <?php echo $lang_pun_antispam[\'Captcha reset info\'] ?></label>
					</div>
				</div>
			</fieldset>
<?php

// Reset fieldset counter
$forum_page[\'set_count\'] = 0;

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'aop_features_validation' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

$form[\'pun_pm_inbox_size\'] = (!isset($form[\'pun_pm_inbox_size\']) || (int) $form[\'pun_pm_inbox_size\'] <= 0) ? \'0\' : (string)(int) $form[\'pun_pm_inbox_size\'];
$form[\'pun_pm_outbox_size\'] = (!isset($form[\'pun_pm_outbox_size\']) || (int) $form[\'pun_pm_outbox_size\'] <= 0) ? \'0\' : (string)(int) $form[\'pun_pm_outbox_size\'];
if (!isset($form[\'pun_pm_show_new_count\']) || $form[\'pun_pm_show_new_count\'] != \'1\')
	$form[\'pun_pm_show_new_count\'] = \'0\';
if (!isset($form[\'pun_pm_show_global_link\']) || $form[\'pun_pm_show_global_link\'] != \'1\')
	$form[\'pun_pm_show_global_link\'] = \'0\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
    1 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (!isset($form[\'pun_antispam_captcha_register\']) || $form[\'pun_antispam_captcha_register\'] != \'1\') $form[\'pun_antispam_captcha_register\'] = \'0\';
if (!isset($form[\'pun_antispam_captcha_login\']) || $form[\'pun_antispam_captcha_login\'] != \'1\') $form[\'pun_antispam_captcha_login\'] = \'0\';
if (!isset($form[\'pun_antispam_captcha_guestpost\']) || $form[\'pun_antispam_captcha_guestpost\'] != \'1\') $form[\'pun_antispam_captcha_guestpost\'] = \'0\';
if (!isset($form[\'pun_antispam_captcha_restorepass\']) || $form[\'pun_antispam_captcha_restorepass\'] != \'1\') $form[\'pun_antispam_captcha_restorepass\'] = \'0\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'hd_visit_elements' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

// \'New messages (N)\' link
if (!$forum_user[\'is_guest\'] && $forum_config[\'o_pun_pm_show_new_count\'])
{
	if (!isset($lang_pun_pm))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	}

	// TODO: Do not include all functions, divide them into 2 files
	if (!function_exists(\'pun_pm_unread_messages\'))
		require $ext_info[\'path\'].\'/functions.php\';

	$visit_elements[\'<!-- forum_visit -->\'] = preg_replace(\'#(<p id="visit-links" class="options">.*?)(</p>)#\', \'$1 <span><a href="\'.forum_link($forum_url[\'pun_pm_inbox\']).\'">\'.pun_pm_unread_messages().\'</a></span>$2\', $visit_elements[\'<!-- forum_visit -->\']);
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'vt_row_pre_post_contacts_merge' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

// Links \'Send PM\' near posts
if (!$forum_user[\'is_guest\'] && $cur_post[\'poster_id\'] > 1 && $forum_user[\'id\'] != $cur_post[\'poster_id\'])
	$forum_page[\'post_contacts\'][\'PM\'] = \'<a class="contact" title="\'.$lang_pun_pm[\'Send PM\'].\'" href="\'.forum_link($forum_url[\'pun_pm_post_link\'], $cur_post[\'poster_id\']).\'">\'.$lang_pun_pm[\'PM\'].\'</a>\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'fn_generate_navlinks_end' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

// Link \'PM\' in the main nav menu
if (isset($links[\'profile\'])  && $forum_config[\'o_pun_pm_show_global_link\'])
{
	global $lang_pun_pm;

	if (!isset($lang_pun_pm))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			include_once $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			include_once $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	}

	if (\'pun_pm\' == substr(FORUM_PAGE, 0, 6))
		$links[\'profile\'] = str_replace(\' class="isactive"\', \'\', $links[\'profile\']);

	$links[\'profile\'] .= "\\n\\t\\t".\'<li id="nav_pun_pm"\'.(\'pun_pm\' == substr(FORUM_PAGE, 0, 6) ? \' class="isactive"\' : \'\').\'><a href="\'.forum_link($forum_url[\'pun_pm\']).\'"><span>\'.$lang_pun_pm[\'Private messages\'].\'</span></a></li>\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'pf_view_details_pre_header_load' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

// Link in the profile 
if (!$forum_user[\'is_guest\'] && $forum_user[\'id\'] != $user[\'id\'])
{
	if (!isset($lang_pun_pm))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			include_once $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			include_once $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	}

	$forum_page[\'user_contact\'][\'PM\'] = \'<li><span>\'.$lang_pun_pm[\'PM\'].\': <a href="\'.forum_link($forum_url[\'pun_pm_post_link\'], $id).\'">\'.$lang_pun_pm[\'Send PM\'].\'</a></span></li>\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'pf_change_details_about_pre_header_load' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

// Link in the profile 
if (!$forum_user[\'is_guest\'] && $forum_user[\'id\'] != $user[\'id\'])
{
	if (!isset($lang_pun_pm))
	{
		if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
			include_once $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
		else
			include_once $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';
	}

	$forum_page[\'user_contact\'][\'PM\'] = \'<li><span>\'.$lang_pun_pm[\'PM\'].\': <a href="\'.forum_link($forum_url[\'pun_pm_post_link\'], $id).\'">\'.$lang_pun_pm[\'Send PM\'].\'</a></span></li>\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'co_modify_url_scheme' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (file_exists($ext_info[\'path\'].\'/url/\'.$forum_config[\'o_sef\'].\'.php\'))
	require $ext_info[\'path\'].\'/url/\'.$forum_config[\'o_sef\'].\'.php\';
else
	require $ext_info[\'path\'].\'/url/Default.php\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  're_rewrite_rules' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

$forum_rewrite_rules[\'/^pun_pm[\\/_-]?send(\\.html?|\\/)?$/i\'] = \'misc.php?action=pun_pm_send\';
$forum_rewrite_rules[\'/^pun_pm[\\/_-]?compose[\\/_-]?([0-9]+)(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm&pmpage=compose&receiver_id=$1\';
$forum_rewrite_rules[\'/^pun_pm(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm\';
$forum_rewrite_rules[\'/^pun_pm[\\/_-]?([0-9a-z]+)(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm&pmpage=$1\';
$forum_rewrite_rules[\'/^pun_pm[\\/_-]?([0-9a-z]+)[\\/_-]?(p|page\\/)([0-9]+)(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm&pmpage=$1&p=$3\';
$forum_rewrite_rules[\'/^pun_pm[\\/_-]?([0-9a-z]+)[\\/_-]?([0-9]+)(\\.html?|\\/)?$/i\'] = \'misc.php?section=pun_pm&pmpage=$1&message_id=$2\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'vt_end' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_quote\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_quote\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_quote\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

?>
		<script type="text/javascript" src="<?php echo $ext_info[\'url\'] ?>/scripts.js"></script>
		<form action="post.php" method="post" id="qq">
			<input type="hidden" value="" id="post_msg" name="post_msg"/>
		</form>
		<?php

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'po_qr_get_quote' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_quote\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_quote\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_quote\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if(isset($_POST[\'post_msg\']))
	$query[\'SELECT\'] = \'p.poster, \\\'\'.$forum_db->escape($_POST[\'post_msg\']).\'\\\'\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'vt_row_pre_post_actions_merge' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_quote\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_quote\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_quote\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
	require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
else
	require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';

if (!$forum_user[\'is_guest\'])
{
	$forum_page[\'post_actions\'][\'reply\'] = \'<span class="edit-post first-item"><a href ="javascript:Reply(\'.$id. \',\'.$cur_post[\'id\'].\')">\'.$lang_pun_quote[\'Reply\'].\'<span>&#160;\'.$lang_topic[\'Post\'].\' \'.($forum_page[\'start_from\'] + $forum_page[\'item_count\']).\'</span></a></span>\';
	unset($forum_page[\'post_actions\'][\'quote\']);
	$forum_page[\'post_actions\'][\'quote\'] = \'<span class="edit-post first-item"><a href ="javascript:QuickQuote(\'.$id. \',\'.$cur_post[\'id\'].\')">\'.$lang_pun_quote[\'Quote\'].\'<span>&#160;\'.$lang_topic[\'Post\'].\' \'.($forum_page[\'start_from\'] + $forum_page[\'item_count\']).\'</span></a></span>\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'mr_topic_actions_moved_row_pre_output' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'hotfix_13_moderate_xss\',
\'path\'			=> FORUM_ROOT.\'extensions/hotfix_13_moderate_xss\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/hotfix_13_moderate_xss\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

$forum_page[\'item_body\'][\'info\'][\'select\'] = \'<li class="info-select"><input id="fld\'.$forum_page[\'fld_count\'].\'" type="checkbox" name="topics[]" value="\'.$cur_topic[\'id\'].\'" /> <label for="fld\'.$forum_page[\'fld_count\'].\'">\'.sprintf($lang_forum[\'Select topic\'], forum_htmlencode($cur_topic[\'subject\'])).\'</label></li>\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'mr_topic_actions_normal_row_pre_output' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'hotfix_13_moderate_xss\',
\'path\'			=> FORUM_ROOT.\'extensions/hotfix_13_moderate_xss\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/hotfix_13_moderate_xss\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

$forum_page[\'item_body\'][\'info\'][\'select\'] = \'<li class="info-select"><input id="fld\'.$forum_page[\'fld_count\'].\'" type="checkbox" name="topics[]" value="\'.$cur_topic[\'id\'].\'" /> <label for="fld\'.$forum_page[\'fld_count\'].\'">\'.sprintf($lang_forum[\'Select topic\'], forum_htmlencode($cur_topic[\'subject\'])).\'</label></li>\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'mr_move_topics_qr_get_target_forums' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'hotfix_13_moderate_topics\',
\'path\'			=> FORUM_ROOT.\'extensions/hotfix_13_moderate_topics\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/hotfix_13_moderate_topics\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (!isset($action))
{
	if (!is_array($topics))
	{
		$action = \'single\';
		// Fetch the topic subject
		$query_subject = array(
			\'SELECT\'	=> \'t.subject\',
			\'FROM\'		=> \'topics AS t\',
			\'WHERE\'		=> \'t.id=\'.$topics
		);

		($hook = get_hook(\'mr_move_topics_qr_get_topic_to_move_subject\')) ? eval($hook) : null;
		$result = $forum_db->query_build($query_subject) or error(__FILE__, __LINE__);

		if (!$forum_db->num_rows($result))
			message($lang_common[\'Bad request\']);

		$subject = $forum_db->result($result);
	}
	else
		$action = \'multiple\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'mr_move_topics_output_start' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'hotfix_13_moderate_topics\',
\'path\'			=> FORUM_ROOT.\'extensions/hotfix_13_moderate_topics\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/hotfix_13_moderate_topics\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

$forum_page[\'hidden_fields\'][\'topics\'] = \'<input type="hidden" name="topics" value="\'.($action == \'single\' ? $topics : implode(\',\', $topics)).\'" />\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'se_results_topics_row_pre_item_title_merge' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'hotfix_13_incorrect_topic_status_in_search_results\',
\'path\'			=> FORUM_ROOT.\'extensions/hotfix_13_incorrect_topic_status_in_search_results\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/hotfix_13_incorrect_topic_status_in_search_results\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

$forum_page[\'item_subject_status\'] = array();

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'rg_start' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

// Load the captcha language file
if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
	require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
else
	require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'aop_start' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

// Load the captcha language file
if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
	require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
else
	require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'li_start' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

// Load the captcha language file
if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
	require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
else
	require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'po_start' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

// Load the captcha language file
if (file_exists($ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\'))
	require $ext_info[\'path\'].\'/lang/\'.$forum_user[\'language\'].\'/\'.$ext_info[\'id\'].\'.php\';
else
	require $ext_info[\'path\'].\'/lang/English/\'.$ext_info[\'id\'].\'.php\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'rg_register_form_submitted' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if ($forum_config[\'o_pun_antispam_captcha_register\'] == \'1\')
{
	session_start();

	if (empty($_SESSION[\'pun_antispam_confirmed_user\']))
	{
		if ((empty($_SESSION[\'pun_antispam_text\']) || strcasecmp(trim($_POST[\'pun_antispam_input\']), $_SESSION[\'pun_antispam_text\']) !== 0))
			$errors[] = $lang_pun_antispam[\'Invalid Text\'];
		else
			$_SESSION[\'pun_antispam_confirmed_user\'] = 1;
	}

	$_SESSION[\'pun_antispam_text\'] = \'\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'rg_register_pre_add_user' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

$_SESSION[\'pun_antispam_confirmed_user\'] = 0;

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'rg_register_pre_language' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if ($forum_config[\'o_pun_antispam_captcha_register\'] == \'1\' && empty($_SESSION[\'pun_antispam_confirmed_user\']))
{
?>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box text required">
						<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_antispam[\'Captcha\'] ?> <em><?php echo $lang_common[\'Required\'] ?></em></span> <small><?php echo $lang_pun_antispam[\'Captcha Info\'] ?></small></label><br />
						<span class="fld-input"><input id="fld<?php echo $forum_page[\'fld_count\'] ?>" type="text" name="pun_antispam_input" value="" size="20" maxlength="10" />&nbsp;&nbsp;&nbsp;<img src="<?php echo $ext_info[\'url\'].\'/image.php?\'.md5(time()) ?>" alt="" /></span>
					</div>
				</div>
<?php
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'li_login_form_submitted' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

session_start();

if ($forum_config[\'o_pun_antispam_captcha_login\'] == \'1\' && (isset($_SESSION[\'pun_antispam_logins\']) && $_SESSION[\'pun_antispam_logins\'] > 5) && (empty($_SESSION[\'pun_antispam_text\']) || strcasecmp(trim($_POST[\'pun_antispam_input\']), $_SESSION[\'pun_antispam_text\']) !== 0))
	$errors[] = $lang_pun_antispam[\'Invalid Text\'];

$_SESSION[\'pun_antispam_text\'] = \'\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'li_login_pre_auth_message' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if ($authorized && empty($errors))
	$_SESSION[\'pun_antispam_logins\'] = 0;

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'li_login_pre_remember_me_checkbox' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if ($forum_config[\'o_pun_antispam_captcha_login\'] == \'1\')
{
	if (empty($errors))
		session_start();

	// Output CAPTCHA if first attempts failed
	if (!isset($_SESSION[\'pun_antispam_logins\']))
		$_SESSION[\'pun_antispam_logins\'] = 1;
	else
		$_SESSION[\'pun_antispam_logins\']++;

	if ($_SESSION[\'pun_antispam_logins\'] > 5)
	{
?>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box text required">
						<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_antispam[\'Captcha\'] ?> <em><?php echo $lang_common[\'Required\'] ?></em></span> <small><?php echo $lang_pun_antispam[\'Captcha Info\'] ?></small></label><br />
						<span class="fld-input"><input id="fld<?php echo $forum_page[\'fld_count\'] ?>" type="text" name="pun_antispam_input" value="" size="20" maxlength="10" />&nbsp;&nbsp;&nbsp;<img src="<?php echo $ext_info[\'url\'].\'/image.php?\'.md5(time()) ?>" alt="" /></span>
					</div>
				</div>
<?php
	}
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'li_forgot_pass_selected' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (isset($_POST[\'form_sent\']))
{
	session_start();

	if ($forum_config[\'o_pun_antispam_captcha_restorepass\'] == \'1\' && (empty($_SESSION[\'pun_antispam_text\']) || strcasecmp(trim($_POST[\'pun_antispam_input\']), $_SESSION[\'pun_antispam_text\']) !== 0))
		$errors[] = $lang_pun_antispam[\'Invalid Text\'];

	$_SESSION[\'pun_antispam_text\'] = \'\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'li_forgot_pass_pre_group_end' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if ($forum_config[\'o_pun_antispam_captcha_restorepass\'] == \'1\')
{
?>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box text required">
						<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_antispam[\'Captcha\'] ?> <em><?php echo $lang_common[\'Required\'] ?></em></span> <small><?php echo $lang_pun_antispam[\'Captcha Info\'] ?></small></label><br />
						<span class="fld-input"><input id="fld<?php echo $forum_page[\'fld_count\'] ?>" type="text" name="pun_antispam_input" value="" size="20" maxlength="10" />&nbsp;&nbsp;&nbsp;<img src="<?php echo $ext_info[\'url\'].\'/image.php?\'.md5(time()) ?>" alt="" /></span>
					</div>
				</div>
<?php
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'po_end_validation' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if ($forum_user[\'is_guest\'] && $forum_config[\'o_pun_antispam_captcha_guestpost\'] == \'1\')
{
	session_start();

	if (empty($_SESSION[\'pun_antispam_confirmed_user\']))
	{
		if ((empty($_SESSION[\'pun_antispam_text\']) || strcasecmp(trim($_POST[\'pun_antispam_input\']), $_SESSION[\'pun_antispam_text\']) !== 0))
		{
			if (!isset($_POST[\'preview\']))
				$errors[] = $lang_pun_antispam[\'Invalid Text\'];
		}
		else
			$_SESSION[\'pun_antispam_confirmed_user\'] = 1;
	}

	$_SESSION[\'pun_antispam_text\'] = \'\';

	// Post is to be written to DB, ask CAPTCHA for the next posting
	if (empty($errors) && !isset($_POST[\'preview\']))
		$_SESSION[\'pun_antispam_confirmed_user\'] = 0;
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'po_pre_guest_info_fieldset_end' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if ($forum_config[\'o_pun_antispam_captcha_guestpost\'] == \'1\' && empty($_SESSION[\'pun_antispam_confirmed_user\']))
{
?>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box text required">
						<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_pun_antispam[\'Captcha\'] ?> <em><?php echo $lang_common[\'Required\'] ?></em></span> <small><?php echo $lang_pun_antispam[\'Captcha Info\'] ?></small></label><br />
						<span class="fld-input"><input id="fld<?php echo $forum_page[\'fld_count\'] ?>" type="text" name="pun_antispam_input" value="" size="20" maxlength="10" />&nbsp;&nbsp;&nbsp;<img src="<?php echo $ext_info[\'url\'].\'/image.php?\'.md5(time()) ?>" alt="" /></span>
					</div>
				</div>
<?php
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'li_login_pre_pass' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'hotfix_13_xss_attack_in_login\',
\'path\'			=> FORUM_ROOT.\'extensions/hotfix_13_xss_attack_in_login\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/hotfix_13_xss_attack_in_login\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

?>

<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box text required">
						<label for="fld<?php echo ++$forum_page[\'fld_count\'] ?>"><span><?php echo $lang_login[\'Password\'] ?> <em><?php echo $lang_common[\'Required\'] ?></em></span></label><br />
						<span class="fld-input"><input type="password" id="fld<?php echo $forum_page[\'fld_count\'] ?>" name="req_password" value="<?php echo isset($_POST[\'req_password\']) ? forum_htmlencode($_POST[\'req_password\']) : \'\' ?>" size="35" /></span>
					</div>
				</div>
<?php ($hook = get_hook(\'li_login_pre_remember_me_checkbox\')) ? eval($hook) : null; ?>
				<div class="sf-set set<?php echo ++$forum_page[\'item_count\'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page[\'fld_count\'] ?>" name="save_pass" value="1" /></span>
						<label for="fld<?php echo $forum_page[\'fld_count\'] ?>"><span><?php echo $lang_login[\'Remember me\'] ?></span> <?php echo $lang_login[\'Persistent login\'] ?></label>
					</div>
				</div>
<?php ($hook = get_hook(\'li_login_pre_group_end\')) ? eval($hook) : null; ?>
			</div>
<?php ($hook = get_hook(\'li_login_group_end\')) ? eval($hook) : null; ?>
			<div class="frm-buttons">
				<span class="submit"><input type="submit" name="login" value="<?php echo $lang_login[\'Login\'] ?>" /></span>
			</div>
		</form>
	</div>
<?php

($hook = get_hook(\'li_end\')) ? eval($hook) : null;

$tpl_temp = forum_trim(ob_get_contents());
$tpl_main = str_replace(\'<!-- forum_main -->\', $tpl_temp, $tpl_main);
ob_end_clean();
// END SUBST - <!-- forum_main -->

require FORUM_ROOT.\'footer.php\';

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
  'ft_about_end' => 
  array (
    0 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_repository\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_repository\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_repository\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (!defined(\'PUN_EXTENSIONS_USED\') && !empty($pun_extensions_used))
{
	define(\'PUN_EXTENSIONS_USED\', 1);
	echo \'<p id="extensions-used">Currently used extensions: \'.implode(\', \', $pun_extensions_used).\'. Copyright &copy; 2008 <a href="http://punbb.informer.com/">PunBB</a></p>\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
    1 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_bbcode\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_bbcode\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_bbcode\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (!defined(\'PUN_EXTENSIONS_USED\') && !empty($pun_extensions_used))
{
	define(\'PUN_EXTENSIONS_USED\', 1);
	echo \'<p id="extensions-used">Currently used extensions: \'.implode(\', \', $pun_extensions_used).\'. Copyright &copy; 2008 <a href="http://punbb.informer.com/">PunBB</a></p>\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
    2 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_pm\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_pm\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_pm\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (!defined(\'PUN_EXTENSIONS_USED\') && !empty($pun_extensions_used))
{
	define(\'PUN_EXTENSIONS_USED\', 1);
	echo \'<p id="extensions-used">Currently used extensions: \'.implode(\', \', $pun_extensions_used).\'. Copyright &copy; 2008 <a href="http://punbb.informer.com/">PunBB</a></p>\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
    3 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_quote\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_quote\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_quote\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (!defined(\'PUN_EXTENSIONS_USED\') && !empty($pun_extensions_used))
{
	define(\'PUN_EXTENSIONS_USED\', 1);
	echo \'<p id="extensions-used">Currently used extensions: \'.implode(\', \', $pun_extensions_used).\'. Copyright &copy; 2008 <a href="http://punbb.informer.com/">PunBB</a></p>\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
    4 => '$ext_info_stack[] = array(
\'id\'				=> \'pun_antispam\',
\'path\'			=> FORUM_ROOT.\'extensions/pun_antispam\',
\'url\'			=> $GLOBALS[\'base_url\'].\'/extensions/pun_antispam\',
\'dependencies\'	=> array (
)
);
$ext_info = $ext_info_stack[count($ext_info_stack) - 1];

if (!defined(\'PUN_EXTENSIONS_USED\') && !empty($pun_extensions_used))
{
	define(\'PUN_EXTENSIONS_USED\', 1);
	echo \'<p id="extensions-used">Currently used extensions: \'.implode(\', \', $pun_extensions_used).\'. Copyright &copy; 2008 <a href="http://punbb.informer.com/">PunBB</a></p>\';
}

array_pop($ext_info_stack);
$ext_info = empty($ext_info_stack) ? array() : $ext_info_stack[count($ext_info_stack) - 1];
',
  ),
);

?>