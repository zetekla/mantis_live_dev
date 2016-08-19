<?php
    // query to select all entry matching $_SESSION['format'] => the regex WHERE serial_number = $qr;
    // serial_id, assembly_id, customer_id, sale_order_id, serial_number, user_id, time
    // query to insert into the db
	require_once( 'core.php' );
	$g_mantis_customer       = db_get_table( 'mantis_customer_table' );
	$g_mantis_assembly       = db_get_table( 'mantis_assembly_table' );
	$g_mantis_serials_format         = plugin_table('format');
	$g_mantis_serials_serial         = plugin_table('serial');
	$scan_msg="";
    if(isset($_POST['new_scan']))
		$cat_count = array(
				0=>$_POST['new_scan'],
				1=>$_POST['sales_order'],
				2=>$_POST['assembly_id'],
				3=>$_POST['customer_id'],
				4=>$_POST['format_example'],
				);
		$andcount = count(array_filter($cat_count));
		
		if(!$_POST['sales_order']){
				$scan_msg .= "Sales Order";
		}
		if(!$_POST['assembly_id']){
				$scan_msg .= "Assembly and Revision";
		}
		if(!$_POST['customer_id']){
				$scan_msg .= "Customer";
		}		
		if($andcount < $cat_count){
			echo "ERROR - Please enter or select data for " . $scan_msg . "<br>";
		}
		else
		{
			$t_new_scan       = $_POST['new_scan'];
			$regex = "/^". $_POST['format'] ."$/";
			if (is_scalar($t_new_scan)){
				if (preg_match($regex, $t_new_scan)){
					global $g_mantis_serials_serial;
					$t_count			= $_POST['list_count'] +=1;
					$t_assembly_id      = $_POST['assembly_id'];
					$t_customer_id      = $_POST['customer_id'];
					$t_sales_order    	= $_POST['sales_order'];
					$t_user_id 			= auth_get_current_user_id();
					$t_date_time        = date('Y-m-d H:i:s');
					$query = "SELECT * FROM $g_mantis_serials_serial WHERE serial_scan='$t_new_scan' AND assembly_id='$t_assembly_id'";
					$result = mysql_query($query) or die(mysql_error());	
					if( mysql_num_rows( $result ) > 0 ) {
						echo 'Duplication ERROR - Scan Data Shown Below! <table class="col-md-12 table table-bordered table-condensed table-striped">';
						$where_search .= $g_mantis_serials_serial . ".serial_scan = " . '"' . $t_new_scan . '"' ;	
						$query = "SELECT 
									$g_mantis_customer.name,
									$g_mantis_assembly.number, 
									$g_mantis_assembly.revision,
									$g_mantis_serials_serial.sales_order,
									mantis_user_table.realname,
									$g_mantis_serials_serial.date_posted,
									$g_mantis_serials_serial.serial_scan
									FROM $g_mantis_serials_serial
									INNER JOIN $g_mantis_assembly ON $g_mantis_serials_serial.assembly_id = $g_mantis_assembly.id
									INNER JOIN $g_mantis_customer ON $g_mantis_serials_serial.customer_id = $g_mantis_customer.id
									INNER JOIN mantis_user_table ON mantis_user_table.id = $g_mantis_serials_serial.user_id
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
								" (serial_id, assembly_id, customer_id, user_id, date_posted, serial_scan, sales_order ) " .
								" VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s');",
										$t_assembly_id,
										$t_customer_id,
										$t_user_id,
										$t_date_time,
										$t_new_scan,
										$t_sales_order);
						$result = mysql_query($query) or die(mysql_error());
						echo '<b>' . $t_count . '.</b> ' . $t_new_scan ;
					}
				}
				else echo "ERROR 20 - Format is incorrect </br><b>Please verify with the following example : " . $_POST['format_example'] . "</b>";
			}
        // echo json_encode($qr, JSON_FORCE_OBJECT);
        // echo json_encode($qr, JSON_PRETTY_PRINT);

		}
// die (json_encode (array ('qr'=>'Your script worked fine')));
?>
