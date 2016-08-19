<?php
# query trigger is the word the user enters on an input field in the view page
# Approach: query directly on the Manex server, should query result performed on Mantis DB returns none.
# It is possible to perform check if query result $count < 1 and $t_query_trigger.length() match a specific number of chars to perform db_update
# TODO: mantis_db_query with array $q and different query_string -> capability improvement

/* Potential Limitations:
* Current array_diff_pairs_xt compares every member of array1 to the corresponding member of array2. It does throw nulls of array1 but not prioritized to display nulls of array2. This can be adjusted if necessary.

* X & T query responses come as an object array containing results [0], [1], [2],... each result is an arr.
* by comparing that way only first object array is compared, unless array_diff_pair_xt method is refined (developed) to accommodate objects comparison.
* Variation (zerofill in this case) could a source of error) e.g. 0000001880 works fine for retrieval from X but full string like that doesn't work on T. Reason: T only has 1880 entry not 0000001880

2 Workarounds:
A/ T-dabase wono, sono have to be ZEROFILL-ed.
B/ Enforce update to match all data including wono, no more variation and difference after sync.

*/

/**
	* @package DbSkewer
	* @copyright [Env-System] Copyright (C) 2002 - 2014  MantisBT Team - mantisbt-dev@lists.sourceforge.net
	* @copyright [OnTop-Dev] Copyright (C) 2016 ZeTek - https://github.com/zenithtekla
	*/
	/**
	* DbSkewer
*/

header('Content-Type: application/json');
define('__ROOT__', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('__CFG_FILE__', __ROOT__.'cfg\manextis_conf.ini');
require_once __ROOT__.'core.php';
require_once __ROOT__.'core\date_time.php';
require_once __ROOT__.'core\helper_util.php';

// require_once __ROOT__.'core\gpc_api.php';

// load datetime
$t_unix_today = strtotime(getDateTime());
// load query strings
$conf = HelperUTIL::load_conf(__CFG_FILE__);

/*
// for POST METHOD
$t_query_trigger = file_get_contents('php://input');
$t_query_trigger = json_decode($t_query_trigger, TRUE);
echo json_encode(array('mySearch' => $t_query_trigger), JSON_PRETTY_PRINT);*/

// GET METHOD
/*
*	MOCHA Configurations
*/
$t_mocha_test = $_GET['Mocha'] === 'true' || $conf['MOCHA']['TEST'] == true;

/*
* Preparing $result array;
*/
$result = [
	'Mocha' 			=> $t_mocha_test,
	'queryExeTime' 		=> $t_unix_today
];

/* Wish there is a compiler to automatically transbose the JS object literal below to PHP syntax NOT json to copy-paste over into PHP file OR even build script and allow running, testing and production to happen smoothly Without heavily relying on ugly syntaxes of PHP for OOP. Procedural programming for PHP, forget it!

var o = {
	timestamp: unix_time,
	wo_so_table: qrs.mantis.wo_so_table,
	assembly_table: qrs.mantis.assembly_table
	...
}
*/

$o_Mocha = new stdClass();
$o_Mocha->timestamp = $t_unix_today;
$o_Mocha->default_status = $conf['MOCHA']['DEFAULT_STATUS'];
$o_Mocha->wo_so_table 		= $conf['MANTIS']['wo_so_table'];
$o_Mocha->assembly_table 	= $conf['MANTIS']['assembly_table'];
$o_Mocha->customer_table 	= $conf['MANTIS']['customer_table'];
$o_Mocha->query_sync_table 	= $conf['MANTIS']['query_sync_table'];


$o_Mocha->customer_find 		= $conf['MANTIS']['QUERY_CUSTOMER_FIND'];
$o_Mocha->uniq_key_find		= $conf['MANTIS']['QUERY_UNIQ_KEY_FIND'];
$o_Mocha->assembly_find 		= $conf['MANTIS']['QUERY_ASSEMBLY_FIND'];
$o_Mocha->assy_find 		= $conf['MANTIS']['QUERY_ASSY_FIND'];


$o_Mocha->insert_wo_so_table 		= $conf['MANTIS']['QUERY_INSERT_WO_TABLE'];
$o_Mocha->insert_assembly_table 	= $conf['MANTIS']['QUERY_INSERT_ASSEMBLY_TABLE'];
$o_Mocha->insert_customer_table 	= $conf['MANTIS']['QUERY_INSERT_CUSTOMER_TABLE'];
$o_Mocha->query_sync_table_insert 	= $conf['MANTIS']['QUERY_SYNC_TABLE_INSERT'];
$o_Mocha->query_sync_table_find 	= $conf['MANTIS']['QUERY_SYNC_TABLE_FIND'];

$o_Mocha->lazy_update_wo_so_table 		= $conf['MANTIS']['QUERY_LAZY_UPDATE_WO_TABLE'];
$o_Mocha->lazy_update_assembly_table 	= $conf['MANTIS']['QUERY_LAZY_UPDATE_ASSEMBLY_TABLE'];
$o_Mocha->lazy_update_customer_table 	= $conf['MANTIS']['QUERY_LAZY_UPDATE_CUSTOMER_TABLE'];
$o_Mocha->lazy_update_customer 	= $conf['MANTIS']['QUERY_LAZY_UPDATE_CUSTOMER'];

$o_Mocha->update_wo_so_table 	= $conf['MANTIS']['QUERY_UPDATE_WO_TABLE'];
$o_Mocha->update_assembly_table = $conf['MANTIS']['QUERY_UPDATE_ASSEMBLY_TABLE'];
$o_Mocha->update_customer_table = $conf['MANTIS']['QUERY_UPDATE_CUSTOMER_TABLE'];


if ($t_mocha_test){
	$o_Mocha->testing = $t_mocha_test;
	$o_Mocha->x_res_count = $conf['MOCHA']['X_RESULT_COUNT'];
	$o_Mocha->t_res_count = $conf['MOCHA']['T_RESULT_COUNT'];
	$o_Mocha->x_res_arr = HelperUTIL::input_string_valid($conf['MOCHA']['X_RESULT_ARR']) ? json_decode($conf['MOCHA']['X_RESULT_ARR'], true) : null;
	$o_Mocha->x_simulate_res_arr = $conf['MOCHA']['X_SIMULATE_RESULT_ARR'];
	$o_Mocha->t_res_arr = HelperUTIL::input_string_valid($conf['MOCHA']['T_RESULT_ARR'])? json_decode($conf['MOCHA']['T_RESULT_ARR'], true) : null;

	// $result['o_Mocha'] = $o_Mocha;
}

/* End MOCHA Configurations
___________________________________________

* Load query trigger and Instantiate a new process
*
* gpc_api UTIL can be used but some HelperUTIL methods are more advanced.
* $result['queryGpc_get']   = gpc_get('query')
* $result['queryGpc_isset']	= gpc_isset('query')
*
* creator = user typing the search key
* the search key itself is considered as a query trigger
* 00091519A, 197390A1
*/

// find T_customer_name based X_customer_name
$t_c_lookup = HelperUTIL::mantis_db_query(
	$o_Mocha->customer_find,
		$o_Mocha->customer_table,
		'John Doe'
);
$result['lookup'] = $t_c_lookup;

?>
<pre>
<?php



print_r($result);
// var_dump($result['Tquery']['response'][0]);
?>
</pre>