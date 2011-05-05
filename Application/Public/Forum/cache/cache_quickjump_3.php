<?php

if (!defined('FORUM')) exit;
define('FORUM_QJ_LOADED', 1);
$forum_id = isset($forum_id) ? $forum_id : 0;

?><form id="qjump" method="get" accept-charset="utf-8" action="http://hoa-project.net/Forum/viewforum.php">
	<div class="frm-fld frm-select">
		<label for="qjump-select"><span><?php echo $lang_common['Jump to'] ?></span></label><br />
		<span class="frm-input"><select id="qjump-select" name="id">
			<optgroup label="Framework">
				<option value="2"<?php echo ($forum_id == 2) ? ' selected="selected"' : '' ?>>Actualités</option>
				<option value="3"<?php echo ($forum_id == 3) ? ' selected="selected"' : '' ?>>Bugs</option>
				<option value="4"<?php echo ($forum_id == 4) ? ' selected="selected"' : '' ?>>Améliorations souhaitées</option>
				<option value="5"<?php echo ($forum_id == 5) ? ' selected="selected"' : '' ?>>Dépannages</option>
				<option value="7"<?php echo ($forum_id == 7) ? ' selected="selected"' : '' ?>>Documentations</option>
				<option value="10"<?php echo ($forum_id == 10) ? ' selected="selected"' : '' ?>>Vos contributions</option>
				<option value="6"<?php echo ($forum_id == 6) ? ' selected="selected"' : '' ?>>Le spectacle continue</option>
			</optgroup>
			<optgroup label="Hoathis">
				<option value="32"<?php echo ($forum_id == 32) ? ' selected="selected"' : '' ?>>Hoathis</option>
			</optgroup>
			<optgroup label="Icône">
				<option value="8"<?php echo ($forum_id == 8) ? ' selected="selected"' : '' ?>>Actualités</option>
				<option value="9"<?php echo ($forum_id == 9) ? ' selected="selected"' : '' ?>>Des idées</option>
				<option value="11"<?php echo ($forum_id == 11) ? ' selected="selected"' : '' ?>>Vos contributions</option>
			</optgroup>
			<optgroup label="Site">
				<option value="12"<?php echo ($forum_id == 12) ? ' selected="selected"' : '' ?>>Correctifs</option>
				<option value="13"<?php echo ($forum_id == 13) ? ' selected="selected"' : '' ?>>Vidéos</option>
				<option value="14"<?php echo ($forum_id == 14) ? ' selected="selected"' : '' ?>>Bar</option>
			</optgroup>
		</select>
		<input type="submit" value="<?php echo $lang_common['Go'] ?>" onclick="return Forum.doQuickjumpRedirect(forum_quickjump_url, sef_friendly_url_array);" /></span>
	</div>
</form>
<script type="text/javascript">
		var forum_quickjump_url = "http://hoa-project.net/Forum/forum/$1/$2/";
		var sef_friendly_url_array = new Array(14);
	sef_friendly_url_array[2] = "actualites";
	sef_friendly_url_array[3] = "bugs";
	sef_friendly_url_array[4] = "ameliorations-souhaitees";
	sef_friendly_url_array[5] = "depannages";
	sef_friendly_url_array[7] = "documentations";
	sef_friendly_url_array[10] = "vos-contributions";
	sef_friendly_url_array[6] = "le-spectacle-continue";
	sef_friendly_url_array[32] = "hoathis";
	sef_friendly_url_array[8] = "actualites";
	sef_friendly_url_array[9] = "des-idees";
	sef_friendly_url_array[11] = "vos-contributions";
	sef_friendly_url_array[12] = "correctifs";
	sef_friendly_url_array[13] = "videos";
	sef_friendly_url_array[14] = "bar";
</script>
