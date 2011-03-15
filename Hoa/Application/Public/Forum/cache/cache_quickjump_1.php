<?php

if (!defined('FORUM')) exit;
define('FORUM_QJ_LOADED', 1);
$forum_id = isset($forum_id) ? $forum_id : 0;

?><form id="qjump" method="get" accept-charset="utf-8" action="http://hoa-project.net/Forum/viewforum.php">
	<div class="frm-fld frm-select">
		<label for="qjump-select"><span><?php echo $lang_common['Jump to'] ?></span></label><br />
		<span class="frm-input"><select id="qjump-select" name="id">
			<optgroup label="Hoa entre nous">
				<option value="15"<?php echo ($forum_id == 15) ? ' selected="selected"' : '' ?>>En vrac …</option>
				<option value="16"<?php echo ($forum_id == 16) ? ' selected="selected"' : '' ?>>Hoa_Orm</option>
				<option value="30"<?php echo ($forum_id == 30) ? ' selected="selected"' : '' ?>>Groupe de relecture</option>
				<option value="31"<?php echo ($forum_id == 31) ? ' selected="selected"' : '' ?>>Contributeurs</option>
			</optgroup>
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
			<optgroup label="Archives 11/2008">
				<option value="17"<?php echo ($forum_id == 17) ? ' selected="selected"' : '' ?>>Framework — Actualités</option>
				<option value="18"<?php echo ($forum_id == 18) ? ' selected="selected"' : '' ?>>Framework — Bugs</option>
				<option value="19"<?php echo ($forum_id == 19) ? ' selected="selected"' : '' ?>>Framework — Améliorations souhaités</option>
				<option value="20"<?php echo ($forum_id == 20) ? ' selected="selected"' : '' ?>>Framework — Dépannages</option>
				<option value="21"<?php echo ($forum_id == 21) ? ' selected="selected"' : '' ?>>Framework — Documentations</option>
				<option value="29"<?php echo ($forum_id == 29) ? ' selected="selected"' : '' ?>>Framework — Vos contributions</option>
				<option value="22"<?php echo ($forum_id == 22) ? ' selected="selected"' : '' ?>>Framework — Le spectacle continue</option>
				<option value="23"<?php echo ($forum_id == 23) ? ' selected="selected"' : '' ?>>Icône — Actualités</option>
				<option value="24"<?php echo ($forum_id == 24) ? ' selected="selected"' : '' ?>>Icône — Des idées</option>
				<option value="25"<?php echo ($forum_id == 25) ? ' selected="selected"' : '' ?>>Icône — Vos contributions</option>
				<option value="26"<?php echo ($forum_id == 26) ? ' selected="selected"' : '' ?>>Sites — Correctifs</option>
				<option value="27"<?php echo ($forum_id == 27) ? ' selected="selected"' : '' ?>>Sites — Vidéos</option>
				<option value="28"<?php echo ($forum_id == 28) ? ' selected="selected"' : '' ?>>Sites — Bar</option>
			</optgroup>
		</select>
		<input type="submit" value="<?php echo $lang_common['Go'] ?>" onclick="return Forum.doQuickjumpRedirect(forum_quickjump_url, sef_friendly_url_array);" /></span>
	</div>
</form>
<script type="text/javascript">
		var forum_quickjump_url = "http://hoa-project.net/Forum/forum/$1/$2/";
		var sef_friendly_url_array = new Array(31);
	sef_friendly_url_array[15] = "en-vrac";
	sef_friendly_url_array[16] = "hoaorm";
	sef_friendly_url_array[30] = "groupe-de-relecture";
	sef_friendly_url_array[31] = "contributeurs";
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
	sef_friendly_url_array[17] = "framework-actualites";
	sef_friendly_url_array[18] = "framework-bugs";
	sef_friendly_url_array[19] = "framework-ameliorations-souhaites";
	sef_friendly_url_array[20] = "framework-depannages";
	sef_friendly_url_array[21] = "framework-documentations";
	sef_friendly_url_array[29] = "framework-vos-contributions";
	sef_friendly_url_array[22] = "framework-le-spectacle-continue";
	sef_friendly_url_array[23] = "icone-actualites";
	sef_friendly_url_array[24] = "icone-des-idees";
	sef_friendly_url_array[25] = "icone-vos-contributions";
	sef_friendly_url_array[26] = "sites-correctifs";
	sef_friendly_url_array[27] = "sites-videos";
	sef_friendly_url_array[28] = "sites-bar";
</script>
