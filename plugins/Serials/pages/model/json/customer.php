<?php
access_ensure_project_level( plugin_config_get('serials_view_threshold'));
header('Content-Type: application/json');
	$g_mantis_customer       		 = db_get_table( 'mantis_customer_table' );
	$g_mantis_assembly       		 = db_get_table( 'mantis_assembly_table' );
	$g_mantis_serials_format         = strtolower(plugin_table('format'));
	$g_mantis_serials_serial         = strtolower(plugin_table('serial'));

function list_customer (){
	global $g_mantis_customer;
	$query = "	SELECT name, id
			FROM $g_mantis_customer
			ORDER BY
			name";

	$result = mysql_query($query) or die(mysql_error());
	
	$json_response = [];
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$row_array['nimi'] = $row['name'];
		$row_array['id'] = $row['id'];

		//push the values in the array
		$json_response[] =$row_array;
	}
	return
	json_encode(
		array_unique($json_response, SORT_REGULAR)
	);
}
	echo list_customer();