<?php
require_once( "plugins/Serials/core/format_check_api.php" );
//access_ensure_project_level( plugin_config_get('format_threshold'));
//access_ensure_project_level( REPORTER );

	$t_unique_key 		= $_POST['unique_key'];
	$t_format			= $_POST['format'];
	$t_format_example	= $_POST['format_example'];
	$t_work_order 		= $_POST['work_order'];
	
	if($t_unique_key){
		$query = "SELECT id, customer_id, format, format_id
				FROM $g_mantis_assembly
				LEFT JOIN $g_mantis_serials_format
				ON $g_mantis_assembly.id = $g_mantis_serials_format.assembly_id
				WHERE $g_mantis_assembly.unique_key='$t_unique_key'";
		$t_r = db_query_bound( $query );
		$row = db_fetch_array( $t_r );
				$t_assembly_id = $row['id'];
				$t_customer_id  = $row['customer_id'];
				$t_format = $row['format'];
				$t_format_example = $row['format_example'];
	}
function get_format ($p_assembly_id){
	global $g_mantis_serials_format;
	$query = "SELECT format, format_id, format_example
			FROM $g_mantis_serials_format
			WHERE assembly_id='$p_assembly_id'";

	$result = mysql_query($query) or die(mysql_error());
	    //Create an array
	$json_response = [];

	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$row_array['format'] = $row['format'];
		$row_array['id'] = $row['format_id'];
		$row_array['example'] = $row['format_example'];

		//push the values in the array
		$json_response[] =$row_array;
	}

	return
	json_encode(
		array_unique($json_response, SORT_REGULAR)
	);
}
	echo get_format($t_assembly_id);
?>
