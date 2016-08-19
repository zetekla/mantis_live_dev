<?php
    // query to select all entry matching $_SESSION['format'] => the regex WHERE serial_number = $qr;
    // serial_id, assembly_id, customer_id, sale_order_id, serial_number, user_id, time
    // query to insert into the db
	require_once( 'core.php' );
	
	$g_mantis_customer       		= db_get_table( 'mantis_customer_table' );
	$g_mantis_assembly       		= db_get_table( 'mantis_assembly_table' );
	$g_mantis_serials_format         = strtolower(plugin_table('format'));
	$g_mantis_serials_serial         = strtolower(plugin_table('serial'));
	
	$t_assembly_id      = $_POST['assembly_id'];
	$t_customer_id      = $_POST['customer_id'];
	$t_sales_order    	= $_POST['sales_order'];
	$t_revision			= $_POST['revision'];
	$t_user_id 			= auth_get_current_user_id();
	$t_date_time        = date('Y-m-d H:i:s');
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
	
    if(isset($_POST['new_scan'])){
		if($t_assembly_id =="" or $t_customer_id == "" or $t_sales_order == "" or $t_revision == "" or $_POST['new_scan'] == "" or $t_work_order == ""){
			echo "ERROR - Please complete the required field in RED TEXT<br>";
			echo '$t_assembly_id ' . $t_assembly_id . '<br>';
			echo '$t_customer_id ' . $t_customer_id . '<br>';
			echo '$t_sales_order ' . $t_sales_order . '<br>';
			echo '$t_revision ' . $t_revision . '<br>';
			echo '$scan' . $_POST['new_scan'] . '<br>';
			echo '$t_work_order ' . $t_work_order . '<br>';
			echo '$t_unique_key' . $t_unique_key . '<br>';
			
		}else{
			if ($t_format){
				$t_new_scan       = $_POST['new_scan'];
				$regex = "/^". $t_format ."$/";
				if (is_scalar($t_new_scan)){
					if (preg_match($regex, $t_new_scan)){
						global $g_mantis_serials_serial;
						
						$query = "SELECT * FROM $g_mantis_serials_serial WHERE serial_scan='$t_new_scan' AND assembly_id='$t_assembly_id'";
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
												mantis_wo_so_table.work_order
										FROM `mantis_plugin_serials_serial_table` 
										INNER JOIN mantis_assembly_table ON mantis_assembly_table.unique_key = mantis_plugin_serials_serial_table.unique_key
										INNER JOIN mantis_customer_table ON mantis_customer_table.id = mantis_plugin_serials_serial_table.customer_id
										INNER JOIN mantis_user_table ON mantis_user_table.id = mantis_plugin_serials_serial_table.user_id
										INNER JOIN mantis_wo_so_table ON mantis_wo_so_table.unique_key = mantis_plugin_serials_serial_table.unique_key
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
							$query = sprintf("INSERT INTO $g_mantis_serials_serial " .
									" (serial_id, assembly_id, customer_id, user_id, date_posted, serial_scan, unique_key ) " .
									" VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s');",
											$t_assembly_id,
											$t_customer_id,
											$t_user_id,
											$t_date_time,
											$t_new_scan,
											$t_unique_key);
							$result = mysql_query($query) or die(mysql_error());
							echo $t_new_scan;
						}
					}
					else echo "ERROR 20 - Format is incorrect </br><b>Please verify with the following example : " . $t_format_example . "</b>";
				}
			}else echo "ERROR 99 - Format for this assembly has not been loaded.</br><b>Please contact the M.E. to load.</b>";
		}
	}else{
		echo "No Scan value was submitted";
	}
		
    

    
        // echo json_encode($qr, JSON_FORCE_OBJECT);
        // echo json_encode($qr, JSON_PRETTY_PRINT);


// die (json_encode (array ('qr'=>'Your script worked fine')));
?>
