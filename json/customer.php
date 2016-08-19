<?php
access_ensure_project_level( plugin_config_get('serials_view_threshold')); 
header('Content-Type: application/json');
	$g_mantis_customer       = db_get_table( 'mantis_customer_table' );
	$g_mantis_assembly       = db_get_table( 'mantis_assembly_table' );
	$g_mantis_serials_format         = plugin_table('format');
	$g_mantis_serials_serial         = plugin_table('serial');	
	function list_customer (){
		global $g_mantis_customer; 
		$query = "	SELECT DISTINCT id, name 
				FROM $g_mantis_customer
				ORDER BY 
				name";

		$result = mysql_query($query) or die(mysql_error());
		
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$row_array['customer_id'] = $row['id'];
			$row_array['customer_name'] = $row['name'];
						
			//push the values in the array
			$json_response[] =$row_array;
			}
		return
		json_encode(
			$json_response
		);
	}
	echo list_customer();