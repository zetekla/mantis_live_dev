<?php
require_once( "plugins/Serials/core/format_check_api.php" );
access_ensure_project_level( plugin_config_get('format_threshold'));
access_ensure_project_level( DEVELOPER );

	$f_user_id 			= auth_get_current_user_id();
	$f_customer_name	= gpc_get_string('customer_name');
	$f_assembly_number	= gpc_get_string( 'assembly_number' );
	$f_revision	  		= gpc_get_string( 'revision' );
	$f_format 			= gpc_get_string( 'format' );
	$f_format_example 	= gpc_get_string( 'format_example' );

	/* is customer exist?, is assembly number exist?, and is format exist?.
	* Verify if c.name exist? > verify if a.number exist? > verify if format exist
	* If none of them exist => .then 'INSERT'
	* Else function UpdateCb().
	*/
	$result = add_format (
					$f_customer_name,
					$f_assembly_number,
					$f_revision,
					$f_format,
					$f_format_example
				);

	$form_msg = (!($c_name_exist && $a_number_exist && $f_exist))
					? plugin_lang_get( 'update_format' ) : plugin_lang_get( 'new_format' );
?>
<div align="center">
<?php
	$t_redirect_url = 'plugin.php?page=Serials/format_view.php';
	html_page_top( null, $t_redirect_url );
	echo '<br />';
	echo $form_msg;
	echo '<br />';
	echo lang_get( 'operation_successful' ) . '<br />';
	print_bracket_link( $t_redirect_url, lang_get( 'proceed' ) );
?>
</div>
<?php
	html_page_bottom();
