<?php
//mantislive core file for common use functions and variables.
	
	function customer_name_exist( $p_customer_name ) {
		$g_mantis_customer       = db_get_table( 'mantis_customer_table' );
		global $g_mantis_customer;
		$query = "SELECT id
					FROM $g_mantis_customer 
					WHERE name = '$p_customer_name'";
		$result = mysql_query( $query ) or die(mysql_error());
		if( mysql_num_rows( $result ) > 0 ) {
			$row = mysql_fetch_array($result);
			return $row["id"];
		}
	}
	
	function assembly_revision_exist ( $p_assembly, $p_revision, $p_customer_id) {
		global $g_mantis_assembly;
		$g_mantis_assembly       = db_get_table( 'mantis_assembly_table' );
		$query = "SELECT id
					FROM $g_mantis_assembly
					WHERE number = '$p_assembly' AND revision = '$p_revision' AND customer_id ='$p_customer_id'";
		$result = mysql_query( $query ) or die(mysql_error());
		if( mysql_num_rows( $result ) > 0 ) {
			$row = mysql_fetch_array($result);
			return $row["id"];
		}
	}




	
?>