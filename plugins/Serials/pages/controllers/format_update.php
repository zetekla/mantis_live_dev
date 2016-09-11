<?php
require_once( "plugins/Serials/core/format_check_api.php" );
access_ensure_project_level( plugin_config_get('format_threshold'));
access_ensure_project_level( DEVELOPER );
	$g_mantis_customer       		= db_get_table( 'mantis_customer_table' );
	$g_mantis_assembly       		= db_get_table( 'mantis_assembly_table' );
	$g_mantis_serials_format         = strtolower(plugin_table('format'));
	$g_mantis_serials_serial         = strtolower(plugin_table('serial'));

	$f_user_id 			= auth_get_current_user_id();
	$t_unique_key 		= $_POST['unique_key'];
	$t_format			= $_POST['format'];
	$t_format_example	= $_POST['format_example'];
	if($t_unique_key){
		$query = "SELECT id
				FROM $g_mantis_assembly
				LEFT JOIN $g_mantis_serials_format
				ON $g_mantis_assembly.id = $g_mantis_serials_format.assembly_id
				WHERE $g_mantis_assembly.unique_key='$t_unique_key'";
		$t_r = db_query_bound( $query );
		$row = db_fetch_array( $t_r );
				$t_assembly_id = $row['id'];
	}
	/* is customer exist?, is assembly number exist?, and is format exist?.
	* Verify if c.name exist? > verify if a.number exist? > verify if format exist
	* If none of them exist => .then 'INSERT'
	* Else function UpdateCb().
	*/
	$result = update_format (
					$t_assembly_id,
					$t_format,
					$t_format_example
				);
	if ($result){
		echo "success";
	}else{
		echo "Error 99";
	}

?>