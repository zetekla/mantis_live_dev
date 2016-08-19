<?php
access_ensure_project_level( plugin_config_get('serials_view_threshold')); 
header('Content-Type: application/json');
	$g_mantis_customer       = db_get_table( 'mantis_customer_table' );
	$g_mantis_assembly       = db_get_table( 'mantis_assembly_table' );
	$g_mantis_serials_format         = plugin_table('format');
	$g_mantis_serials_serial         = plugin_table('serial');
	$p_customer_id = $_POST["customer_id"];
	function list_assembly ($p_customer_id){
		global $g_mantis_assembly;
		if($p_customer_id){
		$t_customer_id = $p_customer_id;		
		$query = "	SELECT DISTINCT number, customer_id
				FROM $g_mantis_assembly
				WHERE customer_id='$t_customer_id'
				ORDER BY number";

		$result = mysql_query($query) or die(mysql_error());

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$row_array['nimi'] = $row['number'];
			$row_array['id'] = $row['customer_id'];
						
			//push the values in the array
			$json_response[] =$row_array;
		}
		return
		json_encode(
			$json_response
		);
		}
	}	
	echo list_assembly( $p_customer_id );