<?php
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
html_page_top1( plugin_lang_get( 'plugin_format_title' ) );
html_page_top2();
print_manage_menu();
?>

<br/>
<form action="<?php echo plugin_page( 'config_edit' ) ?>" method="post">
<table align="center" class="width50" cellspacing="1">

<tr>
	<td class="form-title" colspan="3">
		<?php echo plugin_lang_get( 'plugin_format_title' ) . ': ' . plugin_lang_get( 'plugin_format_config' ) ?>
	</td>
</tr>
<tr <?php echo helper_alternate_class() ?>>
	<td class="category" width="60%">
		<?php echo plugin_lang_get( 'plugin_format_format_text' ) ?>
	</td>
	<td class="center" width="20%">
		<label><input type="radio" name="format_text" value="1" <?php echo ( ON == plugin_config_get( 'format_text' ) ) ? 'checked="checked" ' : ''?>/>
			<?php echo plugin_lang_get( 'plugin_format_enabled' ) ?></label>
	</td>
	<td class="center" width="20%">
		<label><input type="radio" name="format_text" value="0" <?php echo ( OFF == plugin_config_get( 'format_text' ) ) ? 'checked="checked" ' : ''?>/>
			<?php echo plugin_lang_get( 'plugin_format_disabled' ) ?></label>
	</td>
</tr>

<tr <?php echo helper_alternate_class() ?>>
	<td class="category" width="60%">
		<?php echo plugin_lang_get( 'plugin_format_search_text' ) ?>
	</td>
	<td class="center" width="20%">
		<label><input type="radio" name="search_text" value="1" <?php echo ( ON == plugin_config_get( 'search_text' ) ) ? 'checked="checked" ' : ''?>/>
			<?php echo plugin_lang_get( 'plugin_format_enabled' ) ?></label>
	</td>
	<td class="center" width="20%">
		<label><input type="radio" name="search_text" value="0" <?php echo ( OFF == plugin_config_get( 'search_text' ) ) ? 'checked="checked" ' : ''?>/>
			<?php echo plugin_lang_get( 'plugin_format_disabled' ) ?></label>
	</td>
</tr>

<tr <?php echo helper_alternate_class() ?>>
	<td class="category">
		<?php echo plugin_lang_get( 'plugin_format_format_threshold' ) ?>
	</td>
	<td class="center">
			<select name="format_threshold">
			<?php print_enum_string_option_list( 'access_levels', plugin_config_get( 'format_threshold'  ) ) ?>;
			</select>
	</td>
	<td>
	</td>

</tr>


<tr <?php echo helper_alternate_class() ?>>
	<td class="category">
		<?php echo plugin_lang_get( 'plugin_format_search_threshold' ) ?>
	</td>
	<td class="center">
			<select name="promote_threshold">
			<?php print_enum_string_option_list( 'access_levels', plugin_config_get( 'search_threshold'  ) ) ?>;
			</select>
	</td>
	<td>
	</td>

</tr>

<tr <?php echo helper_alternate_class() ?>>
	<td class="category">
		<?php echo plugin_lang_get( 'plugin_format_serials_view_threshold' ) ?>
	</td>
	<td class="center">
			<select name="promote_threshold">
			<?php print_enum_string_option_list( 'access_levels', plugin_config_get( 'serials_view_threshold'  ) ) ?>;
			</select>
	</td>
	<td>
	</td>

</tr>

<tr <?php echo helper_alternate_class() ?>>
	<td class="category">
		<?php echo plugin_lang_get( 'plugin_format_manage_threshold' ) ?>
	</td>
	<td class="center">
			<select name="promote_threshold">
			<?php print_enum_string_option_list( 'access_levels', plugin_config_get( 'manage_threshold'  ) ) ?>;
			</select>
	</td>
	<td>
	</td>

</tr>

<tr>
	<td class="center" colspan="3">
		<input type="submit" class="button" value="<?php echo lang_get( 'change_configuration' ) ?>" />
	</td>
</tr>

</table>
<form>

<?php
html_page_bottom1();