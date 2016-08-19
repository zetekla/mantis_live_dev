<?php
require( "serials_api.php" );
access_ensure_project_level( plugin_config_get('format_threshold')); 
	access_ensure_project_level( DEVELOPER );
	$f_user_id = auth_get_current_user_id();
	$f_customer_name	= gpc_get_string('customer_name');
	$f_assembly_number	  = gpc_get_string( 'assembly_number' );
	$f_revision	  = gpc_get_string( 'revision' );
	$f_format = gpc_get_string( 'format' );
	$f_format_example = gpc_get_string( 'format_example' );
	$new_customer = customer_name_unique ( $f_customer_name );
	$new_assembly = assembly_revision_unique ( $f_assembly_number, $f_revision ,$new_customer );
	$check_format = format_is_new ( $new_assembly );
?>
<?php
	if ( $new_customer == 'true' OR $new_assembly == 'true' OR $check_format == 'true' ){
		$result = add_format ( $f_customer_name, $f_assembly_number, $f_revision, $f_format, $f_format_example, $new_customer, $new_assembly, $check_format );
		$form_msg = plugin_lang_get( 'new_format' );
	} else {					# FAILURE
		$result = add_format ( $f_customer_name, $f_assembly_number, $f_revision, $f_format, $f_format_example, $new_customer, $new_assembly, $check_format );
		$form_msg = plugin_lang_get( 'update_format' );
	}
?>
<div align="center">
<?php
	$t_redirect_url = 'plugin.php?page=Serials/format.php';
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
