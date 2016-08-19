<?php
access_ensure_project_level( plugin_config_get('serials_view_threshold')); 
header('Content-Type: application/json');
	$g_mantis_customer       = db_get_table( 'mantis_customer_table' );
	$g_mantis_assembly       = db_get_table( 'mantis_assembly_table' );
	$g_mantis_serials_format         = plugin_table('format');
	$g_mantis_serials_serial         = plugin_table('serial');	
	$p_assembly_id = gpc_get_string('id');
	
	function get_format ($p_assembly_id){
		$t_assembly_id = $p_assembly_id;
		global $g_mantis_serials_format; 
		$query = "SELECT format, format_example
				FROM $g_mantis_serials_format
				WHERE assembly_id='$t_assembly_id'";
				
		$result = mysql_query($query) or die(mysql_error());
		
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$row_array['format'] = $row['format'];
			$row_array['format_example'] = $row['format_example'];
						
			//push the values in the array
				$json_response[] =$row_array;
			}
		return
		json_encode(
			$json_response
		);
	}
		echo get_format($p_assembly_id);