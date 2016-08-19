<?php
# MantisBT - a php based bugtracking system

# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.
# Bug Tracking system developed by Phuc Tran

/**
 * @copyright Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
 * @copyright Copyright (C) 2002 - 2014  MantisBT Team - mantisbt-dev@lists.sourceforge.net
 * @link http://www.mantisbt.org
 * @package MantisBT
 */

/**
 * requires current_user_api
 */
require_once( 'current_user_api.php' );
/**
 * requires bug_api
 */
 require_once( 'bug_api.php' );
/**
 * requires string_api
 */
require_once( 'string_api.php' );
/**
 * requires date_api
 */
require_once( 'date_api.php' );
/**
 * requires icon_api
 */
require_once( 'icon_api.php' );

$t_filter = current_user_get_bug_filter();
if( $t_filter === false ) {
	$t_filter = filter_get_default();
}

$t_sort = $t_filter['sort'];
$t_dir = $t_filter['dir'];

$t_icon_path = config_get( 'icon_path' );
$t_update_bug_threshold = config_get( 'update_bug_threshold' );
$t_bug_resolved_status_threshold = config_get( 'bug_resolved_status_threshold' );
$t_hide_status_default = config_get( 'hide_status_default' );
$t_default_show_changed = config_get( 'default_show_changed' );

$c_filter['assigned'] = filter_create_assigned_to_unresolved( helper_get_current_project(), $t_current_user_id );
$url_link_parameters['assigned'] = FILTER_PROPERTY_HANDLER_ID . '=' . $t_current_user_id . '&' . FILTER_PROPERTY_HIDE_STATUS_ID . '=' . $t_bug_resolved_status_threshold;

$c_filter['recent_mod'] = array(
	FILTER_PROPERTY_CATEGORY => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_SEVERITY_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_STATUS_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_HIGHLIGHT_CHANGED => $t_default_show_changed,
	FILTER_PROPERTY_REPORTER_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_HANDLER_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_RESOLUTION_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_PRODUCT_BUILD => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_PRODUCT_VERSION => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_HIDE_STATUS_ID => Array(
		'0' => META_FILTER_NONE,
	),
	FILTER_PROPERTY_MONITOR_USER_ID => Array(
		'0' => META_FILTER_ANY,
	),
);
$url_link_parameters['recent_mod'] = FILTER_PROPERTY_HIDE_STATUS_ID . '=none';

$c_filter['reported'] = filter_create_reported_by( helper_get_current_project(), $t_current_user_id );
$url_link_parameters['reported'] = FILTER_PROPERTY_REPORTER_ID . '=' . $t_current_user_id . '&' . FILTER_PROPERTY_HIDE_STATUS_ID . '=' . $t_hide_status_default;

$c_filter['resolved'] = array(
	FILTER_PROPERTY_CATEGORY => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_SEVERITY_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_STATUS_ID => Array(
		'0' => $t_bug_resolved_status_threshold,
	),
	FILTER_PROPERTY_HIGHLIGHT_CHANGED => $t_default_show_changed,
	FILTER_PROPERTY_REPORTER_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_HANDLER_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_RESOLUTION_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_PRODUCT_BUILD => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_PRODUCT_VERSION => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_HIDE_STATUS_ID => Array(
		'0' => $t_hide_status_default,
	),
	FILTER_PROPERTY_MONITOR_USER_ID => Array(
		'0' => META_FILTER_ANY,
	),
);
$url_link_parameters['resolved'] = FILTER_PROPERTY_STATUS_ID . '=' . $t_bug_resolved_status_threshold . '&' . FILTER_PROPERTY_HIDE_STATUS_ID . '=' . $t_bug_resolved_status_threshold;

$c_filter['unassigned'] = filter_create_assigned_to_unresolved( helper_get_current_project(), 0 );
$url_link_parameters['unassigned'] = FILTER_PROPERTY_HANDLER_ID . '=[none]' . '&' . FILTER_PROPERTY_HIDE_STATUS_ID . '=' . $t_hide_status_default;

# TODO: check. handler value looks wrong

$c_filter['monitored'] = filter_create_monitored_by( helper_get_current_project(), $t_current_user_id );
$url_link_parameters['monitored'] = FILTER_PROPERTY_MONITOR_USER_ID . '=' . $t_current_user_id . '&' . FILTER_PROPERTY_HIDE_STATUS_ID . '=' . $t_hide_status_default;

$c_filter['feedback'] = array(
	FILTER_PROPERTY_CATEGORY => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_SEVERITY_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_STATUS_ID => Array(
		'0' => config_get( 'bug_feedback_status' ),
	),
	FILTER_PROPERTY_HIGHLIGHT_CHANGED => $t_default_show_changed,
	FILTER_PROPERTY_REPORTER_ID => Array(
		'0' => $t_current_user_id,
	),
	FILTER_PROPERTY_HANDLER_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_RESOLUTION_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_PRODUCT_BUILD => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_PRODUCT_VERSION => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_HIDE_STATUS_ID => Array(
		'0' => $t_hide_status_default,
	),
	FILTER_PROPERTY_MONITOR_USER_ID => Array(
		'0' => META_FILTER_ANY,
	),
);
$url_link_parameters['feedback'] = FILTER_PROPERTY_REPORTER_ID . '=' . $t_current_user_id . '&' . FILTER_PROPERTY_STATUS_ID . '=' . config_get( 'bug_feedback_status' ) . '&' . FILTER_PROPERTY_HIDE_STATUS_ID . '=' . $t_hide_status_default;

$c_filter['verify'] = array(
	FILTER_PROPERTY_CATEGORY => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_SEVERITY_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_STATUS_ID => Array(
		'0' => $t_bug_resolved_status_threshold,
	),
	FILTER_PROPERTY_HIGHLIGHT_CHANGED => $t_default_show_changed,
	FILTER_PROPERTY_REPORTER_ID => Array(
		'0' => $t_current_user_id,
	),
	FILTER_PROPERTY_HANDLER_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_RESOLUTION_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_PRODUCT_BUILD => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_PRODUCT_VERSION => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_HIDE_STATUS_ID => Array(
		'0' => $t_hide_status_default,
	),
	FILTER_PROPERTY_MONITOR_USER_ID => Array(
		'0' => META_FILTER_ANY,
	),
);
$url_link_parameters['verify'] = FILTER_PROPERTY_REPORTER_ID . '=' . $t_current_user_id . '&' . FILTER_PROPERTY_STATUS_ID . '=' . $t_bug_resolved_status_threshold;

$c_filter['my_comments'] = array(
	FILTER_PROPERTY_CATEGORY => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_SEVERITY_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_STATUS_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_HIGHLIGHT_CHANGED => $t_default_show_changed,
	FILTER_PROPERTY_REPORTER_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_HANDLER_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_RESOLUTION_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_PRODUCT_BUILD => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_PRODUCT_VERSION => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_HIDE_STATUS_ID => Array(
		'0' => $t_hide_status_default,
	),
	FILTER_PROPERTY_MONITOR_USER_ID => Array(
		'0' => META_FILTER_ANY,
	),
	FILTER_PROPERTY_NOTE_USER_ID=> Array(
		'0' => META_FILTER_MYSELF,
	),
);

$url_link_parameters['my_comments'] = FILTER_PROPERTY_NOTE_USER_ID. '=' . META_FILTER_MYSELF . '&' . FILTER_PROPERTY_HIDE_STATUS_ID . '=' . $t_hide_status_default;
$rows = filter_get_bug_rows( $f_page_number, $t_per_page, $t_page_count, $t_bug_count, $c_filter[$t_box_title] );

# Improve performance by caching category data in one pass
if( helper_get_current_project() == 0 ) {
	$t_categories = array();
	foreach( $rows as $t_row ) {
		$t_categories[] = $t_row->category_id;
	}

	category_cache_array_rows( array_unique( $t_categories ) );
}

$t_filter = array_merge( $c_filter[$t_box_title], $t_filter );

$box_title = lang_get( 'my_view_title_' . $t_box_title );

# -- ====================== BUG LIST ========================= --
?>

<table class="width100" cellspacing="1" >
<?php
# -- Navigation header row --?>
<tr class="nopad">
<?php
# -- Viewing range info --?>
	<td class="form-title pad1" colspan="20">
<?php
print_link( html_entity_decode( config_get( 'bug_count_hyperlink_prefix' ) ).'&' . $url_link_parameters[$t_box_title], $box_title, false, 'subtle' );
echo '&#160;';
print_bracket_link( html_entity_decode( config_get( 'bug_count_hyperlink_prefix' ) ).'&' . $url_link_parameters[$t_box_title], '^', true, 'subtle' );

if( count( $rows ) > 0 ) {
	$v_start = $t_filter[FILTER_PROPERTY_ISSUES_PER_PAGE] * ( $f_page_number - 1 ) + 1;
	$v_end = $v_start + count( $rows ) - 1;
}
else {
	$v_start = 0;
	$v_end = 0;
}
echo "($v_start - $v_end / $t_bug_count)";
?>
	</td>
</tr>

<?php
# -- Loop over bug rows and create $v_* variables --
	$t_count = count( $rows );
	for( $i = 0;$i < $t_count; $i++ ) {
		$t_bug = $rows[$i];

	# issue name
	$t_summary = string_display_line_links( $t_bug->summary );

	$t_last_updated = date( config_get( 'normal_date_format' ), $t_bug->last_updated );

	# choose color based on status
	$status_color = get_status_color( $t_bug->status, auth_get_current_user_id(), $t_bug->project_id );

	# Check for attachments
	$t_attachment_count = 0;
	# TODO: factor in the allow_view_own_attachments configuration option
	# instead of just using a global check.
	if(( file_can_view_bug_attachments( $t_bug->id, null ) ) ) {
		$t_attachment_count = file_bug_attachment_count( $t_bug->id );
	}

	# grab the project name
	$project_name = project_get_field( $t_bug->project_id, 'name' );
	?>

<tr bgcolor="<?php echo $status_color?>" class="nopad">
	<?php
	# -- Bug ID and details link + Pencil shortcut --?>
	<td class="center nopad" valign="top" width ="0" nowrap="nowrap" rowspan="2">
	<div style="padding:4px !important;">
		<span class="small" style="font-size:9pt !important;">
		<?php
			print_bug_link( $t_bug->id );

	echo '<br /></div><div style="padding:4px !important;">';

	# update button
#	if( !bug_is_readonly( $t_bug->id ) && access_has_bug_level( $t_update_bug_threshold, $t_bug->id ) ) {
#		echo '<a href="' . string_get_bug_update_url( $t_bug->id ) . '"><img border="0" src="' . $t_icon_path . 'update.png' . '" alt="' . lang_get( 'update_bug_button' ) . '" /></a>&#160;&#160;';
#	}

	# priority text
#	if( ON == config_get( 'show_priority_text' ) ) {
#		print_formatted_priority_string( $t_bug );
#	} else {
#		print_status_icon( $t_bug->priority );
#	}

#	if ( $t_attachment_count > 0 ) {
#		$t_href = string_get_bug_view_url( $t_bug->id ) . '#attachments';
#		$t_href_title = sprintf( lang_get( 'view_attachments_for_issue' ), $t_attachment_count, $t_bug->id );
#		$t_alt_text = $t_attachment_count . lang_get( 'word_separator' ) . lang_get( 'attachments' );
#		echo "<a href=\"$t_href\" title=\"$t_href_title\"><img src=\"${t_icon_path}attachment.png\" alt=\"$t_alt_text\" title=\"$t_alt_text\" /></a>";
#	}

	if( VS_PRIVATE == $t_bug->view_state ) {
		echo '<img src="' . $t_icon_path . 'protected.gif" width="8" height="15" alt="' . lang_get( 'private' ) . '" />';
	}
	?>
		</span></div>
	</td>
	<td bgcolor="#f5f5f5"colspan="20" class="left" valign="top" width="100%">
		<div style="text-align: center; padding:0px;">
			<div class=" inline-block nopad" style="padding:0px"><?php
				# -- Summary --<td class="left" valign="top" width="100%">
					if( ON == config_get( 'show_bug_project_links' ) && helper_get_current_project() != $t_bug->project_id ) {
						echo '[', string_display_line( project_get_name( $t_bug->project_id ) ), '] ';
					}
					echo $t_summary;
				?>
			</div>

			<div class="floatright right italic-small gray inline-block nopad"><?php

				# type project name if viewing 'all projects' or bug is in subproject
				echo string_display_line( category_full_name( $t_bug->category_id, true, $t_bug->project_id ) );

				if( $t_bug->last_updated > strtotime( '-' . $t_filter[FILTER_PROPERTY_HIGHLIGHT_CHANGED] . ' hours' ) ) {
					echo ' - <b>' . $t_last_updated . '</b>';
				} else {
					echo ' - ' . $t_last_updated;
				}

			?></div>
		</div>
	</td>
	<tr class="nopad">
	<?php
	#*********  custom field value
	# Custom Fields
	$t_custom_fields_found = false;
	$t_related_custom_field_ids = custom_field_get_linked_ids( $t_bug->project_id );
	$g_show_only_custom_fields = config_get( 'show_only_custom_fields' );
	if($t_related_custom_field_ids)
	//** print_r($g_show_only_custom_fields);
	$t_columns = helper_get_columns_to_view( COLUMNS_TARGET_HOME_VIEW_PAGE, /* $p_viewable_only */ false, $t_current_user_id );
	// $t_cond = in_array($t_def_custom,$t_columns)===TRUE;
	$t_custom_fields_found = true;
	$t_config_table = db_get_table( 'mantis_config_table' );
	$b_helper_user_exists = helper_user_exists ($t_current_user_id, $t_config_table);

	if ($b_helper_user_exists && $t_columns){
		foreach( $t_columns as $t_column_name) {
			foreach( $t_related_custom_field_ids as $key => $t_id ) {
				if ( !custom_field_has_read_access( $t_id, $t_bug->id ) ) {
					continue;
				}
				$t_custom_fields_found = true;
				$t_def = custom_field_get_definition( $t_id );
				$t_def_custom = 'custom_' . strtolower($t_def['name']);

				if ($t_def_custom===$t_column_name)
					echo '<td class="custom_field pad1 center" title="',string_display( lang_get_defaulted( $t_def['name'] ) ),'">', print_custom_field_value( $t_def, $t_id, $t_bug->id ), '</td>';
			}
		}
	} else {
		if($t_related_custom_field_ids)
		foreach( $g_show_only_custom_fields as $t_display_id){
			foreach( $t_related_custom_field_ids as $key => $t_id ) {
				if ( !custom_field_has_read_access( $t_id, $t_bug->id ) ) {
					continue;
				} # has read access #d8d8d8

				$t_custom_fields_found = true;
				$t_def = custom_field_get_definition( $t_id );
				$t_def_custom = 'custom_' . strtolower($t_def['name']);

				if ($key+1===$t_display_id)
					echo '<td class="custom_field pad1 center" title="',string_display( lang_get_defaulted( $t_def['name'] ) ),'">', print_custom_field_value( $t_def, $t_id, $t_bug->id ), '</td>';
			}
		}
	}
	echo '</tr>';

	if ( $t_custom_fields_found ) {
		# spacer
		echo '<tr class="custom_spacer"><td colspan="20"></td></tr>';
	} # custom fields found

									#*********

	?></tr>
</tr>
<?php
	# -- end of Repeating bug row --
}

# -- ====================== end of BUG LIST ========================= --
?>
</table>
<?php
// Free the memory allocated for the rows in this box since it is not longer needed.
unset( $rows );

