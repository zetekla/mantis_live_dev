<?php
	$g_mantis_customer       			= db_get_table('mantis_customer_table');
	$g_mantis_assembly       			= db_get_table('mantis_assembly_table');
	$g_mantis_serials_format         	= strtolower(plugin_table('format'));
	$g_mantis_serials_serial         	= strtolower(plugin_table('serial'));
	#----------------------------------
	# serials page definitions

	$g_serials_menu_page                = plugin_page( 'serials_menu_page.php' );
	$g_format_add_page					= plugin_page( 'format_add_.php' );
	$g_format_page                 		= plugin_page( 'format_view.php' );
	$g_config_page                 		= plugin_page( 'config.php' );
	$g_config_edit_page                 = plugin_page( 'config_edit.php' );
		#----------------------------------

	###########################################################################
	# serials API
	###########################################################################

	function customer_name_unique( $p_customer_name ) {
		global $g_mantis_customer;
		$query = "SELECT id
					FROM $g_mantis_customer
					WHERE name = '$p_customer_name'";
		$result = mysql_query( $query ) or die(mysql_error());
		if( mysql_num_rows( $result ) > 0 ) {
			$row = mysql_fetch_array($result);
			return $row["id"];
		} else {
			return 'true';
		}
	}

	function assembly_revision_unique ( $p_assembly, $p_revision, $new_customer) {
		global $g_mantis_assembly;
		$query = "SELECT id
					FROM $g_mantis_assembly
					WHERE number = '$p_assembly' AND revision = '$p_revision' AND customer_id ='$new_customer'";
		$result = mysql_query( $query ) or die(mysql_error());
		if( mysql_num_rows( $result ) > 0 ) {
			$row = mysql_fetch_array($result);
			return $row["id"];
		} else {
			return 'true';
		}
	}
	function format_is_new( $p_assembly_id ){
		global $g_mantis_serials_format;
		$query = " SELECT format_id
				FROM $g_mantis_serials_format 
				WHERE assembly_id='$p_assembly_id'";
		$result = mysql_query( $query ) or die(mysql_error());
		if( mysql_num_rows( $result ) > 0 ) {
			$row = mysql_fetch_array($result);
			return $row["format_id"];
		} else {
			return 'true';
		}
	}
	function add_customer( $p_customer_name, $new_customer){
		global $g_mantis_customer;
		if ( $new_customer == 'true' ){
			$query = "INSERT INTO $g_mantis_customer
					( id, name )
					VALUES
					( null, '$p_customer_name')";
			db_query_bound( $query );
			$t_customer_id = db_insert_id ( $g_mantis_customer );
			return $t_customer_id;
		} else {
			return $new_customer;
		}
	}

	function add_assembly ( $p_assembly_number, $p_revision , $m_customer_name, $new_customer, $new_assembly ){
		$p_customer_id = add_customer ( $m_customer_name, $new_customer );
		global $g_mantis_assembly;
		if ( $new_assembly == 'true' ){
			$query = "INSERT
					INTO $g_mantis_assembly
					( id, customer_id, number, revision )
					VALUES
					( null, '$p_customer_id', '$p_assembly_number', '$p_revision' )";
			db_query_bound( $query );
			$t_assembly_id = db_insert_id ( $g_mantis_assembly );
			return $t_assembly_id;
		} else {
			return $new_assembly;
		}
	}
	function add_format( $p_customer_name, $p_assembly_number, $p_revision, $p_format, $p_format_example, $new_customer, $new_assembly, $new_format ){
		$p_assembly_id = add_assembly ( $p_assembly_number, $p_revision, $p_customer_name, $new_customer, $new_assembly );
		global $g_mantis_serials_format;
		if ( $new_assembly == 'true' || $new_format == 'true' ){
			$query = "INSERT
					INTO $g_mantis_serials_format
					( format_id, assembly_id, format, format_example )
					VALUES
					( null, '$p_assembly_id', '$p_format', '$p_format_example' )";
	    	return db_query_bound( $query );
		} else {
			$query = "UPDATE $g_mantis_serials_format
					SET format='$p_format', format_example='$p_format_example'
					WHERE assembly_id='$p_assembly_id'";
			return db_query_bound( $query );
		}
	}
