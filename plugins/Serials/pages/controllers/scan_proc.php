<?php
    // query to select all entry matching $_SESSION['format'] => the regex WHERE serial_number = $qr;
    // serial_id, assembly_id, customer_id, sale_order_id, serial_number, user_id, time
    // query to insert into the db
	require_once( 'core.php' );
	
	$g_mantis_customer       		= db_get_table( 'mantis_customer_table' );
	$g_mantis_assembly       		= db_get_table( 'mantis_assembly_table' );
	$g_mantis_serials_format         = strtolower(plugin_table('format'));
	$g_mantis_serials_serial         = strtolower(plugin_table('serial'));

	$t_sales_order    	= $_POST['sales_order'];
	$t_revision			= $_POST['revision'];
	$t_user_id 			= auth_get_current_user_id();
	$t_date_time        = date('Y-m-d H:i:s');
	//$t_format			= $_POST['format'];
	//$t_format_example	= $_POST['format_example'];
	$t_work_order 		= str_pad($_POST['work_order'], 10, '0',STR_PAD_LEFT);
	$t_session_id 		= $_POST['session_id'];
	$t_rework			= $_POST['rework'];
	$t_new_scan       = strtoupper($_POST['new_scan']);
	if($t_work_order){
		$query = "SELECT unique_key
				FROM mantis_wo_so_table
				WHERE mantis_wo_so_table.work_order='$t_work_order'";
		$t_r = db_query_bound( $query );
		$row = db_fetch_array( $t_r );
				$t_unique_key = $row['unique_key'];		
	}
	
	if($t_unique_key){
		$query = "SELECT id, customer_id, format, format_example
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
		
    if(isset($_POST['new_scan'])){
		if($t_unique_key =="" or $_POST['new_scan'] == "" or $t_work_order == ""){
			echo "ERROR - Please complete the required field in RED TEXT<br>";
			echo '$t_assembly_id ' . $t_assembly_id . '<br>';
			echo '$t_customer_id ' . $t_customer_id . '<br>';
			echo '$t_sales_order ' . $t_sales_order . '<br>';
			echo '$t_revision ' . $t_revision . '<br>';
			echo '$scan' . $_POST['new_scan'] . '<br>';
			echo '$t_work_order ' . $t_work_order . '<br>';
			echo '$t_unique_key' . $t_unique_key . '<br>';
			
		}else{
			if ($t_rework == 'true'){
						global $g_mantis_serials_serial;
						
						$query = "SELECT * FROM $g_mantis_serials_serial WHERE serial_scan='$t_new_scan' AND work_order='$t_work_order'";
						$result = mysql_query($query) or die(mysql_error());
						if( mysql_num_rows( $result ) > 0 ) {
							echo 'Duplication ERROR REWORK SCAN - Scan Data Shown Below! <table class="col-md-12 table table-bordered table-condensed table-striped">';
							$where_search .= $g_mantis_serials_serial . ".serial_scan = " . '"' . $t_new_scan . '"' ;
							$query = "SELECT 	mantis_customer_table.name,
												mantis_assembly_table.number,
												mantis_assembly_table.revision,
												mantis_user_table.realname,
												mantis_plugin_serials_serial_table.date_posted,
												mantis_plugin_serials_serial_table.serial_scan,
												mantis_plugin_serials_serial_table.work_order,
												mantis_plugin_serials_serial_table.session_id
										FROM `mantis_plugin_serials_serial_table` 
										LEFT JOIN mantis_assembly_table ON mantis_plugin_serials_serial_table.unique_key = mantis_assembly_table.unique_key
										LEFT JOIN mantis_customer_table ON mantis_assembly_table.customer_id = mantis_customer_table.id
										LEFT JOIN mantis_user_table ON mantis_plugin_serials_serial_table.user_id = mantis_user_table.id
										WHERE $where_search
										ORDER BY serial_scan, date_posted
										";
							$result = mysql_query($query) or die(mysql_error());
							if( mysql_num_rows( $result ) > 0 ) {
								$first_row = true;
								while ( $row = mysql_fetch_assoc( $result )) {
									if ($first_row) {
										$first_row = false;
										// Output header row from keys.
										echo '<tr >';
										foreach($row as $key => $field) {
											echo '<th class="text-center text-uppercase col-md-2">' . htmlspecialchars($key) . '</th>';
										}
										echo '</tr>';
									}
									echo '<tr >';
									foreach($row as $field) {
										echo '<td class="text-center col-md-2">' . htmlspecialchars($field) . '</td>';
									}
									echo '</tr>' ;
								}
								echo '</table>';
							}
							exit;
						}
						if (!$t_session_id){
										$t_session_id = $t_user_id . date(ymdHis);
									}
									$query = sprintf("INSERT INTO $g_mantis_serials_serial " .
											" (serial_id, user_id, date_posted, serial_scan, unique_key, session_id, work_order ) " .
											" VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s');",
													$t_user_id,
													$t_date_time,
													$t_new_scan,
													$t_unique_key,
													$t_session_id,
													$t_work_order);
									$result = mysql_query($query) or die(mysql_error());
									$row_array['error_code'] = 'undefined';
									$row_array['scan'] = $t_new_scan;
									$row_array['session_id'] = $t_session_id;
									$json_response[] = $row_array;
									echo json_encode($json_response);
									exit;
			}else{
				if ($t_format){
				$regex = "/^". $t_format ."$/";
				if (is_scalar($t_new_scan)){
					if (preg_match($regex, $t_new_scan)){
						global $g_mantis_serials_serial;
						$query = "SELECT * FROM $g_mantis_serials_serial WHERE serial_scan='$t_new_scan' AND unique_key='$t_unique_key'";
						$result = mysql_query($query) or die(mysql_error());
						if( mysql_num_rows( $result ) > 0 ) {
							echo 'Duplication ERROR - Scan Data Shown Below! <table class="col-md-12 table table-bordered table-condensed table-striped">';
							$where_search .= $g_mantis_serials_serial . ".serial_scan = " . '"' . $t_new_scan . '"' ;
							$query = "SELECT 	mantis_customer_table.name,
												mantis_assembly_table.number,
												mantis_assembly_table.revision,
												mantis_user_table.realname,
												mantis_plugin_serials_serial_table.date_posted,
												mantis_plugin_serials_serial_table.serial_scan,
												mantis_plugin_serials_serial_table.work_order,
												mantis_plugin_serials_serial_table.session_id
										FROM `mantis_plugin_serials_serial_table` 
										LEFT JOIN mantis_assembly_table ON mantis_plugin_serials_serial_table.unique_key = mantis_assembly_table.unique_key
										LEFT JOIN mantis_customer_table ON mantis_assembly_table.customer_id = mantis_customer_table.id
										LEFT JOIN mantis_user_table ON mantis_plugin_serials_serial_table.user_id = mantis_user_table.id
										WHERE $where_search
										ORDER BY serial_scan, date_posted
										";
							$result = mysql_query($query) or die(mysql_error());
							if( mysql_num_rows( $result ) > 0 ) {
								$first_row = true;
								while ( $row = mysql_fetch_assoc( $result )) {
									if ($first_row) {
										$first_row = false;
										// Output header row from keys.
										echo '<tr >';
										foreach($row as $key => $field) {
											echo '<th class="text-center text-uppercase col-md-2">' . htmlspecialchars($key) . '</th>';
										}
										echo '</tr>';
									}
									echo '<tr >';
									foreach($row as $field) {
										echo '<td class="text-center col-md-2">' . htmlspecialchars($field) . '</td>';
									}
									echo '</tr>' ;
								}
								echo '</table>';
							}
						}
						else {
							if (!$t_session_id){
								$t_session_id = $t_user_id . date(ymdHis);
							}
							$query = sprintf("INSERT INTO $g_mantis_serials_serial " .
									" (serial_id, user_id, date_posted, serial_scan, unique_key, session_id, work_order ) " .
									" VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s');",
											$t_user_id,
											$t_date_time,
											$t_new_scan,
											$t_unique_key,
											$t_session_id,
											$t_work_order);
							$result = mysql_query($query) or die(mysql_error());
							$row_array['error_code'] = 'undefined';
							$row_array['scan'] = $t_new_scan;
							$row_array['session_id'] = $t_session_id;
							$json_response[] = $row_array;
							echo json_encode($json_response);
						}
					}
					else {
						$row_array['error_code'] = 'Error 20';
						$row_array['error_msg'] = 'Scan does not match format of this assembly';
						$row_array['format'] = $t_format;
						$row_array['format_example'] = $t_format_example;
						$json_response[] =$row_array;
						echo json_encode($json_response);
					}
				}
			}else {
				$row_array['error_code'] = 'Error 99';
				$row_array['error_msg'] = 'Format for this assembly has not been loaded.</br><b>Please contact the M.E. to add this assembly.</b>';
				$json_response[] =$row_array;
				echo json_encode($json_response);
			}
			}
			
		}
	}else{
		echo "No Scan value was submitted";
	}
		
    

    
        // echo json_encode($qr, JSON_FORCE_OBJECT);
        // echo json_encode($qr, JSON_PRETTY_PRINT);


// die (json_encode (array ('qr'=>'Your script worked fine')));
?>
