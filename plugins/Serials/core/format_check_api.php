<?php
	require_once( 'core.php' );
	require_once( 'helper_util.php' );

	$g_mantis_customer       			= db_get_table('mantis_customer_table');
	$g_mantis_assembly       			= db_get_table('mantis_assembly_table');
	$g_mantis_serials_format         	= strtolower(plugin_table('format'));
	$g_mantis_serials_serial         	= strtolower(plugin_table('serial'));
	#----------------------------------
	# serials page definitions

	$g_serials_menu_page                = plugin_page( 'serials_menu_page.php' );
	$g_format_add_page					= plugin_page( 'format_add.php' );
	$g_format_page                 		= plugin_page( 'format_view.php' );
	$g_config_page                 		= plugin_page( 'config.php' );
	$g_config_edit_page                 = plugin_page( 'config_edit.php' );
		#----------------------------------

	###########################################################################
	# serials API
	###########################################################################

	// int function, return int
	function is_c_name_exist( $p_customer_name ) {
		global $g_mantis_customer;
		$query = "SELECT id
					FROM $g_mantis_customer
					WHERE name = '$p_customer_name'";
		$result = mysql_query( $query ) or die(mysql_error());
		if( mysql_num_rows( $result ) < 1 ) return 0;

		$row = mysql_fetch_array($result);
		return $row["id"];
	}

	// int function, return int
	function is_assembly_revision_exist ( $p_assembly, $p_revision, $p_c_name_exist) {
		global $g_mantis_assembly;
		$query = "SELECT id
					FROM $g_mantis_assembly
					WHERE number = '$p_assembly' AND revision = '$p_revision' AND customer_id ='$p_c_name_exist'";
		$result = mysql_query( $query ) or die(mysql_error());
		if( mysql_num_rows( $result ) < 1 ) return 0;

		$row = mysql_fetch_array($result);
		return $row["id"];
	}

	// int function, return int
	function is_format_exist( $p_assembly_id ){
		global $g_mantis_serials_format;
		$query = " SELECT format_id
				FROM $g_mantis_serials_format
				WHERE assembly_id='$p_assembly_id'";
		$result = mysql_query( $query ) or die(mysql_error());
		if( mysql_num_rows( $result ) < 1 ) return 0;

		$row = mysql_fetch_array($result);
		return $row["format_id"];
	}

	// int function, return int
	function add_customer( $p_customer_name, $p_c_name_exist){
		global $g_mantis_customer;
		if ($p_c_name_exist) return $p_c_name_exist;

		$query = "INSERT INTO $g_mantis_customer
				( id, name )
				VALUES
				( null, '$p_customer_name')";
		db_query_bound( $query );
		$t_customer_id = db_insert_id ( $g_mantis_customer );
		return $t_customer_id;
	}

	// int function, return int
	function add_assembly (
		$p_assembly_number,
		$p_revision,
		$p_customer_name,
		$p_c_name_exist,
		$p_a_number_exist
	){
		$p_customer_id = add_customer ( $p_customer_name, $p_c_name_exist );
		global $g_mantis_assembly;
		if ($p_a_number_exist) return $p_a_number_exist;

		$query = "INSERT
				INTO $g_mantis_assembly
				( id, customer_id, number, revision )
				VALUES
				( null, '$p_customer_id', '$p_assembly_number', '$p_revision' )";
		db_query_bound( $query );
		$t_assembly_id = db_insert_id ( $g_mantis_assembly );
		return $t_assembly_id;
	}

	function add_format(
		$p_customer_name,
		$p_assembly_number,
		$p_revision,
		$p_format,
		$p_format_example
	){
		$p_c_name_exist = is_c_name_exist ( $p_customer_name );
		$p_a_number_exist = is_assembly_revision_exist ( $p_assembly_number, $p_revision ,$p_c_name_exist );
		$p_format_exist = is_format_exist ( $p_a_number_exist );

		// invoke assembly insertion to determine if it is a new assembly (assembly_id) or not.
		// p_assembly_id = id || 0;
		$p_assembly_id = add_assembly (
							$p_assembly_number,
							$p_revision,
							$p_customer_name,
							$p_c_name_exist,
							$p_a_number_exist );

		global $g_mantis_serials_format;
		if ( !($p_a_number_exist && $p_format_exist) ){
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
	
	function update_format(
		$p_assembly_id,
		$p_format,
		$p_format_example
	){
		$p_format_exist = is_format_exist ( $p_assembly_id );
		global $g_mantis_serials_format;
		if ( !$p_format_exist ){
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