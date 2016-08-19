<?php
// query to select all entry matching $_SESSION['format'] => the regex WHERE serial_number = $qr;
// serial_id, assembly_id, customer_id, sale_order_id, serial_number, user_id, time
// query to insert into the db
require_once( 'core.php' );
require_once( 'helper_util.php' );
access_ensure_project_level( plugin_config_get('search_threshold'));
$g_mantis_customer       			= db_get_table( 'mantis_customer_table' );
$g_mantis_assembly       			= db_get_table( 'mantis_assembly_table' );
$g_mantis_user       			    = db_get_table( 'mantis_user_table' );
$g_mantis_wo_so_table         = 'mantis_wo_so_table';
$g_mantis_serials_format      = strtolower(plugin_table('format'));
$g_mantis_serials_serial      = strtolower(plugin_table('serial'));
// define variables
$o_post['serial_scan']		    = $_POST['serial_scan'];
$o_post['work_order']		    = $_POST['work_order'];
$o_post['unique_key']		    = $_POST['unique_key'];
/*$o_post['assembly_number']	= $_POST['assembly_number'];
$o_post['assembly_id']		  = $_POST['assembly_id'];
$o_post['customer_id']		  = $_POST['customer_id'];*/
$o_post['user_id']			    = auth_get_current_user_id();
// define query strings, code separation from the logic
/*$query_for_serials = "
	SELECT
		st.serial_scan
	FROM %s st
	INNER JOIN %s at
		ON st.assembly_id = at.id
	INNER JOIN %s ct
		ON st.customer_id = ct.id
	INNER JOIN mantis_user_table ut
		ON ut.id = st.user_id
	WHERE %s
	ORDER BY st.serial_scan
";*/
$query = "
	SELECT
		ct.name,
		at.number,
		at.revision,
		ut.realname,
		st.date_posted,
		st.serial_scan,
		wt.work_order
	FROM %s st
	INNER JOIN %s at
		ON st.unique_key = at.unique_key
	INNER JOIN %s ct
		ON st.customer_id = ct.id
	INNER JOIN %s ut
		ON ut.id = st.user_id
	INNER JOIN %s wt
  		ON wt.unique_key = st.unique_key
	WHERE %s
	ORDER BY st.serial_scan, st.date_posted
";
function search ($o_post){
	$p_serial_scan			= $o_post['serial_scan'];
	$p_work_order			  = $o_post['work_order'];
	$p_unique_key			  = $o_post['unique_key'];
	/*$p_assembly_number	= $o_post['assembly_number'];
	$p_assembly_id			= $o_post['assembly_id'];
	$p_customer_id			= $o_post['customer_id'];*/
	try {
		if (!($p_serial_scan || $p_unique_key || $p_work_order))
			throw new Exception('ERROR - Please search using a WORK ORDER , UNIQUE_KEY , or SERIAL NUMBER (SCAN INPUT).');
/*		if ($p_work_order){
			$p_post['st.work_order']	= $p_work_order;
			$p_search[]					= "Work Order '$p_work_order'";
		}*/
		if ($p_serial_scan){
			$p_post['st.serial_scan']	= $p_serial_scan;
			$p_search[]					= "Serial Number '$p_serial_scan'";
		}
		if ($p_unique_key){
			$p_post['st.unique_key']		= $p_unique_key;
//			$p_search[]					= "Unique Key '$p_unique_key'";
		}

		foreach ($p_post as $key => $value) {
			if($value){
				$p_cats[$key] = $value;
				$p_wheres[] = $key." = '$value'";
			}
		}
		$result['where'] = implode(' AND ', $p_wheres);
		$result['search_msg'] = count($p_search)>1 ? implode(' and ', $p_search) : $p_search;
	} catch (Exception $e) {
		$result['error'] = $e->getMessage();
	}
	finally {
		return $result;
	}
}
$response = search($o_post);
if ($response['error']){
	$json_response['serials'] = $response['error'];
} else {
	$t_where = $response['where'];
	$search_msg = 'Searching for ' . $response['search_msg'];

	$response =  HelperUTIL::mantis_db_query(
				$query,
					$g_mantis_serials_serial,
					$g_mantis_assembly,
					$g_mantis_customer,
					$g_mantis_user,
					$g_mantis_wo_so_table,
					$t_where
	);
	$json_response["all"] = $response['response'];
}
echo json_encode($json_response, JSON_PRETTY_PRINT);
// echo json_encode($qr, JSON_FORCE_OBJECT);