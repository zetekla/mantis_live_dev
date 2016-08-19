<?php
    // query to select all entry matching $_SESSION['format'] => the regex WHERE serial_number = $qr;
    // serial_id, assembly_id, customer_id, sale_order_id, serial_number, user_id, time
    // query to insert into the db
	require_once( 'core.php' );
	access_ensure_project_level( plugin_config_get('search_threshold')); 
	$g_mantis_customer       = db_get_table( 'mantis_customer_table' );
	$g_mantis_assembly       = db_get_table( 'mantis_assembly_table' );
	$g_mantis_serials_format         = plugin_table('format');
	$g_mantis_serials_serial         = plugin_table('serial');
	$t_scan_input         = $_POST['scan_input'];
    $t_unique_key    	= $_POST['unique_key'];
	$t_unique_key  = '_4HI0XLSY7';
		if($t_scan_input =="" && $t_unique_key == "" ){
			echo "ERROR - Please search using the Work Order Number or Serial Number.";
			}
			else
			{
			$cat_count = array(
				0=>$_POST['scan_input'],
				1=>$_POST['unique_key'],
				);
			$andcount = count(array_filter($cat_count));
			$where_search ="";	
			$search_msg="Search for ";
            global $g_mantis_serials_serial;

/* 			$t_search_date    = $_POST['search_date'];
            $t_user_id 			= null; // typeahead of username list */
			
			if($t_scan_input){
				$where_search .= "mantis_plugin_serials_serial_table.serial_scan = " . "'" . $t_scan_input . "'";
				$andcount = $andcount - 1;
				$search_msg .=" Serial Number " . $t_scan_input ;
				if ($andcount > 0){
					$where_search .=" AND ";
					$search_msg .= " and ";
				}
			}
			if($t_unique_key){
				$where_search .="mantis_plugin_serials_serial_table.unique_key = " . "'" . $t_unique_key . "'";
				$andcount = $andcount - 1;
				if ($andcount > 0){
					$where_search .=" AND ";
				}
			}
/* 			if($t_search_date){
				$where_search .="mantis_plugin_serials_serial_table.date_posted LIKE " . "'%" . $t_search_date . "%'";
				$andcount = $andcount - 1;
				if ($andcount > 0){
					$where_search .=" AND ";
				}
			}
			if($t_user_id){
				$where_search .="mantis_plugin_serials_serial_table.user_id = " . "'" . $t_user_id . "'";
				$andcount = $andcount - 1;
				if ($andcount > 0){
					$where_search .=" AND ";
				}
			} */
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
				/* $first_row = true;
				echo '<style>';
				echo 'tr:nth-child(odd){ background-color: white;}';
				echo 'td { nowrap; padding: 1px 3px;}';
				echo '</style>';
				echo '<table class="col-md-12"><div>';
				while ( $row = mysql_fetch_assoc( $result )) {
					if ($first_row) {
						$first_row = false;
						$count = 0;
						// Output header row from keys.
						echo '<tr >';
						foreach($row as $key => $field) {
							echo '<th class="text-center text-uppercase">' . htmlspecialchars($key) . '</th>';
						}
						echo '<th class="text-center text-uppercase">Count</th>';
						echo '</tr>';
					}
					echo '<tr >';
					foreach($row as $field) {
						echo '<td class="text-center">' . htmlspecialchars($field) . '</td>';
					}
					$count++;
					echo '<td class="text-center">' . $count . '</td>';
					echo '</tr>' ;
				}
				echo '</table></div>'; */
				$json_response = [];
					$count= 0;
					
				while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$count++;
					$row_array['customer'] = $row['name'];
					$row_array['assembly'] = $row['number'];
					$row_array['revision'] = $row['revision'];
					$row_array['realname'] = $row['realname'];
					$row_array['date_posted'] = $row['date_posted'];
					$row_array['serial_scan'] = $row['serial_scan'];
					$row_array['work_order'] = $row['work_order'];
					$row_array['count'] = $count;

					//push the values in the array
					$json_response[] =$row_array;
				}

				echo json_encode(array_unique($json_response, SORT_REGULAR));
				
			}	
            else {
				echo $search_msg . "  returned with no results." ;
				}
			}
        // echo json_encode($qr, JSON_FORCE_OBJECT);
        // echo json_encode($qr, JSON_PRETTY_PRINT);

// die (json_encode (array ('qr'=>'Your script worked fine')));
function queryOfQuery($rs, // The recordset to query
  $fields = "*", // optional comma-separated list of fields to return, or * for all fields
  $distinct = false, // optional true for distinct records
  $fieldToMatch = null, // optional database field name to match
  $valueToMatch = null) { // optional value to match in the field, as a comma-separated list

  $newRs = Array();
  $row = Array();
  $valueToMatch = explode(",",$valueToMatch);
  $matched = true;
  mysql_data_seek($rs, 0);
  if($rs) {
    while ($row_rs = mysql_fetch_assoc($rs)){
      if($fields == "*") {
        if($fieldToMatch != null) {
          $matched = false;
          if(is_integer(array_search($row_rs[$fieldToMatch],$valueToMatch))){
            $matched = true;
          }
        }
        if($matched) $row = $row_rs;
      }else{
        $fieldsArray=explode(",",$fields);
        foreach($fields as $field) {
          if($fieldToMatch != null) {
            $matched = false;
            if(is_integer(array_search($row_rs[$fieldToMatch],$valueToMatch))){
              $matched = true;
            }
          }
          if($matched) $row[$field] = $row_rs[$field];
        }
      }
      if($matched)array_push($newRs, $row);
    };
    if($distinct) {
      sort($newRs);
      for($i = count($newRs)-1; $i > 0; $i--) {
        if($newRs[$i] == $newRs[$i-1]) unset($newRs[$i]);
      }
    }
  }
  mysql_data_seek($rs, 0);
  return $newRs;
}
?>
