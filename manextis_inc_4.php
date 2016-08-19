<?php
	define('__ROOT__', dirname(__FILE__).DIRECTORY_SEPARATOR);
	define('__CFG_FILE__', __ROOT__.'cfg\manextis_conf.ini');
	require_once __ROOT__.'core\helper_util.php';
	require_once __ROOT__.'core.php';
	/* ------------------------------------- */
?>
<pre>
<?php
	$t_arr = ["UNIQ_KEY" => "_X01","WO_NO" => "wono1","SO_NO" => "sono1","DUE_DATE" => 100166, "ASSY_NO" => '      "  " " 00 " \{\{{/+  ', "REVISION" => "    ", "QTY" => 99, "CUST_PO_NO" => " pono2      ", "CUST_NAME" => "Fuji_ko"];
	$x_arr = ["UNIQ_KEY" => "_X02","WO_NO" => "wono2","SO_NO" => "sono2","DUE_DATE" => 200266, "ASSY_NO" => 11, "REVISION" => "    R1   ", "QTY" => 88, "CUST_PO_NO" => "        poNo2 ", "CUST_NAME" => "Fuji_ko"];
	$result = HelperUTIL::array_diff_pairs($x_arr, $t_arr);

	/* ------------------------------------- */
	$qrs = HelperUTIL::load_conf(__CFG_FILE__);
	/* ------------------------------------- */

	$t_query_trigger = '1814';

	$t_query_param1 = 20;
	$t_query_param2 = 1461875091;
	$t_query_param3 = '333333CLE3';

	$t_query_trigger = HelperUTIL::query_trigger_handler($t_query_trigger);

	$t_query_params = [$qrs['MANTIS']['wo_so_table'], 32222, 20, 1461822222, $t_query_trigger];

	$response = HelperUTIL::mantis_db_query($qrs['MANTIS']['QUERY_LAZY_UPDATE_WO_TABLE'], $t_query_params);
	$result = array_merge($result, ['res1' => $response]);

	/* ---
			END RESPONSE_1 -feeding parameters array
			BEGIN RESPONSE_2 - feeding parameters themselves directly
	   ---
	*/
	$response = HelperUTIL::mantis_db_query($qrs['MANTIS']['QUERY_LAZY_UPDATE_WO_TABLE'], [$qrs['MANTIS']['wo_so_table'], 37777, 20, 1461877777, $t_query_trigger]);
	$result = array_merge($result, ['res2' => $response]);


	/* ---
			END RESPONSE_2 -feeding parameters array
			BEGIN RESPONSE_3 - feeding parameters themselves directly
			work w/ r24 (presumptive), r25 (tested)
	   ---
	*/
	$response = HelperUTIL::mantis_db_query_update($qrs['MANTIS']['QUERY_LAZY_UPDATE_WO_TABLE'], $qrs['MANTIS']['wo_so_table'], 10099, 20, 1000000099, $t_query_trigger);
	$result = array_merge($result, ['res3' => $response]);

	/* ---
			END RESPONSE_3 -feeding parameters array
			BEGIN RESPONSE_4 - feeding parameters themselves directly
			work w/ r24 (presumptive), r25 (tested)
	   ---
	*/
	$response = HelperUTIL::mantis_db_query_update($qrs['MANTIS']['QUERY_LAZY_UPDATE_WO_TABLE'], [$qrs['MANTIS']['wo_so_table'], 355555, 20, 1000055555, $t_query_trigger]);
	$result = array_merge($result, ['res4' => $response]);



	echo json_encode($result, JSON_PRETTY_PRINT);
?>
</pre>
