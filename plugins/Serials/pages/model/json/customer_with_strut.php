<?php
access_ensure_project_level( plugin_config_get('serials_view_threshold'));
header('Content-Type: application/json');
	$g_mantis_customer       		 = db_get_table( 'mantis_customer_table' );
	$g_mantis_assembly       		 = db_get_table( 'mantis_assembly_table' );
	$g_mantis_serials_format         = plugin_table('format');
	$g_mantis_serials_serial         = plugin_table('serial');
require_once('model/fn.php');

function list_customer (){
	global $g_mantis_customer;
	$qr = "	SELECT customer_name, customer_id
			FROM $g_mantis_customer
			ORDER BY
			customer_name";

	require_once('model/init_dbi.php');
    $mysqli = $strut();
    $result = $strut();

    $result->result = $mysqli->query($qr) or die($mysqli->error());
	$num_rows = $result->num_rows();
	    //Create an array
	if ($num_rows){
    	$json_response = [];

    	while ($row = $result->fetch_assoc()) {
    		$json_response[] =  $row;
    	}
	}

	$jsonString = json_encode($json_response, JSON_PRETTY_PRINT);
	echo $jsonString;
}
	echo list_customer();