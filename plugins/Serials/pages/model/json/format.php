<?php
access_ensure_project_level( plugin_config_get('serials_view_threshold'));
header('Content-Type: application/json');
	$g_mantis_customer       		 = db_get_table( 'mantis_customer_table' );
	$g_mantis_assembly       		 = db_get_table( 'mantis_assembly_table' );
	$g_mantis_serials_format         = strtolower(plugin_table('format'));
	$g_mantis_serials_serial         = strtolower(plugin_table('serial'));
	$t_assembly_id = gpc_get_string ('id');

function get_format ($p_assembly_id){
	global $g_mantis_serials_format;
	$query = "SELECT format, format_id, format_example
			FROM $g_mantis_serials_format
			WHERE assembly_id='$p_assembly_id'";

	$result = mysql_query($query) or die(mysql_error());
	    //Create an array
	$json_response = [];

	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$row_array['nimi'] = $row['format'];
		$row_array['id'] = $row['format_id'];
		$row_array['sample'] = $row['format_example'];

		//push the values in the array
		$json_response[] =$row_array;
	}

	return
	json_encode(
		array_unique($json_response, SORT_REGULAR)
	);
}
	echo get_format($t_assembly_id);