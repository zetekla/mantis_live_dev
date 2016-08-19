<?php
/**
* @require class HelperUTIL
* class SkewChess handles maneX and manTis db requests.
* The process is tied up with query_trigger and creator_id
*/
function shlog($v){
	return ['bash' => $v];
}

class SkewChess{
	// initialization
	function __construct($query_trigger, $t_creator_id = '') {
		$this->query_trigger = HelperUTIL::query_trigger_handler($query_trigger);
		$this->creator_id = HelperUTIL::input_string_escape($t_creator_id);
	}
	function getQueryTrigger(){
		return $this->query_trigger;
	}
	function getCreatorId(){
		return $this->creator_id;
	}
	function setQueryTrigger($val){
		$this->query_trigger = HelperUTIL::query_trigger_handler($val);
	}

	function fn_process_SkewedData($p_Curl_result, $p_qr_execute_update){
		$obj = json_decode($p_Curl_result);
		return $obj;
		/*foreach ($obj as $val){
			$p_customer_name = HelperUTIL::input_string_escape($val->CUST_NAME);
			$p_timestamp = $val->ACCT_DATE;
			db_query_bound($p_qr_execute_update);
		}*/
	}
	/**
	 * Returns an array list of query result
	 * @param specific CONSTANT STRING $http_request from ini file to be executed.
 	 * NO count as MantisDb not evaluated.
	 * @return json string
	 */
	function manex_db_query($http_request){
		$p_query_trigger = $this->getQueryTrigger();
		// on server machine, getQueryPrefixedTrigger() might be used instead.
		$result = HelperUTIL::getCurlData($http_request, $p_query_trigger);
		return $result;
	}
	/**
	 * Returns an array list of query result
	 * @param specific CONSTANT STRING $http_request from ini file to be executed.
 	 * @param $count number of result from a previous MantisDb query
	 * @return json string
	 */
	function fn_skew_manexDb (){
		$params = func_get_arg(0);
		$http_request= $params[0];
		$count= $params[1];
		if($count<1)
			return $this->manex_db_query($http_request);
	}
	function xt_compare($arr1, $arr2){
		return HelperUTIL::array_diff_pairs_xt($arr1, $arr2);
	}

	/* customer_id lookup X.c_name */
	function id_customer_if_exist($p_query, $p_table, $p_X_name){
		$res = HelperUTIL::mantis_db_query(
			$p_query,
				$p_table,
				$p_X_name
		);
		$result['response'] = $res['response']['response'][0];
		if ($res['response']['count']){
			$result['customer_id'] 	=  $result['response']['CUST_ID'];
			$result['time_stamp'] 	= $result['response']['TIME_STAMP'];
		} else {
			$result['customer_id'] 	= 0;
			$result['time_stamp'] 	= 0;
		}

		return $result;
		// then in main xt_sync(), retrieve $source['customer_id'] = function()['customer_id'];
	}

	/**
	* Build update query
	*/
	function update_a_record($p_update, $p_query, $p_table, $p_where){
		$p_set_at = implode(', ', array_keys($p_update));
		$p_query = str_replace('?', $p_set_at, $p_query);
		$params = array_values($p_update);
		array_unshift($params, $p_table);
		array_push($params, $p_where);
		return HelperUTIL::mantis_db_query_build($p_query, $params);
	}

	/**
	* Insertion build logic of XTsync
	*/
	function xt_sync_insert($source){
		$result = [];

		if($source['insert_customer_table']){
			$params = [
				$source['customer_table'],
				$source['CUST_NAME'],
				$source['CUST_PO_NO'],
				$source['STATUS'],
				$source['ACCT_DATE'],
				$source['TIME_STAMP']
			];
			// $t_query_string = HelperUTIL::mantis_db_query_build($source['insert_customer_table'], $params);
			if (!$source['customer_id']){
				// customer not exist, invoke insertion to get a new id
				$t_insertion = HelperUTIL::mantis_db_query_insert($source['insert_customer_table'], $params);
				$source['customer_id'] = $t_insertion['id'];
			} else {
				$t_query_string = HelperUTIL::mantis_db_query_build($source['insert_assembly_table'], $params);
				$result[] = $t_query_string;
			}
		}

		// perform next query
		if($source['insert_assembly_table']){
			$params = [
				$source['assembly_table'],
				$source['customer_id'],
				$source['ASSY_NO'],
				$source['REVISION'],
				$source['UNIQ_KEY']
			];
			$t_query_string = HelperUTIL::mantis_db_query_build($source['insert_assembly_table'], $params);
			$result[] = $t_query_string;
		}

		// perform last query
		if($source['insert_wo_so_table']){
			$params = [
				$source['wo_so_table'],
				$source['WO_NO'],
				$source['SO_NO'],
				$source['QTY'],
				$source['DUE_DATE'],
				$source['UNIQ_KEY']
			];
			$t_query_string = HelperUTIL::mantis_db_query_build($source['insert_wo_so_table'], $params);
			$result[] = $t_query_string;
		}

		return $result;
	}

	/**
	* Insertion build logic of XTsync
	*/
	function xt_sync_insert_assembly_wo($source){

		$params = [
			$source['assembly_table'],
			$source['customer_id'],
			$source['ASSY_NO'],
			$source['REVISION'],
			$source['UNIQ_KEY']
		];
		$t_query_string = HelperUTIL::mantis_db_query_insert($source['insert_assembly_table'], $params);

		$params = [
			$source['wo_so_table'],
			$source['WO_NO'],
			$source['SO_NO'],
			$source['QTY'],
			$source['DUE_DATE'],
			$source['UNIQ_KEY']
		];
		$t_query_string = HelperUTIL::mantis_db_query_insert($source['insert_wo_so_table'], $params);

	}

	/**
	* Insertion build logic of XTsync
	*/
	function xt_sync_insert_all($source){

		$params = [
				$source['customer_table'],
				$source['CUST_NAME'],
				$source['CUST_PO_NO'],
				$source['STATUS'],
				$source['ACCT_DATE'],
				$source['TIME_STAMP']
			];

		$t_insertion = HelperUTIL::mantis_db_query_insert($source['insert_customer_table'], $params);
		$source['customer_id'] = $t_insertion['id'][0];

		$params = [
			$source['assembly_table'],
			$source['customer_id'],
			$source['ASSY_NO'],
			$source['REVISION'],
			$source['UNIQ_KEY']
		];
		$t_query_string = HelperUTIL::mantis_db_query_insert($source['insert_assembly_table'], $params);

		$params = [
			$source['wo_so_table'],
			$source['WO_NO'],
			$source['SO_NO'],
			$source['QTY'],
			$source['DUE_DATE'],
			$source['UNIQ_KEY']
		];
		$t_query_string = HelperUTIL::mantis_db_query_insert($source['insert_wo_so_table'], $params);

	}
	/**
	* Returns an array list of query result
	* @param X_query_str
	* @param T_query_str
	* @return array
	*/
	function xt_sync($X_query_str, $T_query_str){
		$X_response = $this->manex_db_query($X_query_str);
		$X_res_arr = json_decode($X_response, true);
		if ($X_response){
			$result['Xquery']['count'] = count($X_res_arr);
			$result['Xquery']['response'] = $X_response;
			$result['Xquery']['query_string'] = $X_query_str;
		}
		/* ---
				END Xresponse
				BEGIN Tresponse
		   ---
		*/
		$T_response = HelperUTIL::mantis_db_query($T_query_str, $this->getQueryTrigger());
		$result['Tquery'] = $T_response;

		$result['XTcompare'] = $this->xt_compare($X_res_arr[0], $T_response['response'][0]);
		return $result;
	}

	/**
	* Returns an array list of query result
	* @param X_query_str
	* @param T_query_str
	* @param o_Mocha carries over pre-defined settings
	* @return array
	*/

	function xt_sync_update($X_query_str, $T_query_str, $o_Mocha = null){
		try {
			$X_response = $this->manex_db_query($X_query_str);
			if ($X_response){
				$X_res_arr = json_decode($X_response, true);
				$t_count_override = false;

				/* o_Mocha required for your coffee. Feel the Mocha. mochajs.org
				* UnitTest, IDE environment, IDE utilities (Compiler, Debugger, Lint...), Shell scripting (npm, pip, gem, bower, chmod, md) is hardly performed in PHP if PHP development rocks on the sand of Windows and TextEditor
				*/
				if (empty($o_Mocha)) throw new Exception(' 13 - Mocha isn\'t defined.');
				// override result count.
				$result['Xquery']['count'] = ($o_Mocha->testing && !empty($o_Mocha->x_res_count)) ? $o_Mocha->x_res_count : count($X_res_arr);
				$result['shell'][] = shlog('init() Xquery.count = '. $result['Xquery']['count']);

				if ($result['Xquery']['count'] < 1) throw new Exception('02.1 - X_Query result has NO matching entry');
				if ($result['Xquery']['count'] > 1) throw new Exception('03.1 - X_Query result contains more than one entry');

				// X_query.count == 1
				$result['Xquery']['response'] = $X_response;
				$result['Xquery']['query_string'] = $X_query_str;

				/* ---
				END X_response
				BEGIN T_response, load table names from conf.ini
				   ---
				*/
				// get timestamp
				$t_timestamp 				= $o_Mocha->timestamp;
				// get table names
				$q_wo_so_table    			= $o_Mocha->wo_so_table;
				$q_assembly_table 			= $o_Mocha->assembly_table;
				$q_customer_table 			= $o_Mocha->customer_table;
				// get query_trigger
				$t_query_trigger			= $this->getQueryTrigger();
				if (!$t_query_trigger) throw new Exception ('query_trigger = null, verify input or testing boolean');

				$T_response = HelperUTIL::mantis_db_query($T_query_str, $q_wo_so_table, $q_assembly_table, $q_customer_table, $t_query_trigger);
				$result['Tquery'] = $T_response;

				/* ---
				END T_response
				DONE X & T retrieval
				   ---
				*/
				$result['pipe']['response'] = $X_response;

				/* --- OVERRIDE result count --- effective, TESTED */
				if ($o_Mocha->testing && !($o_Mocha->t_res_count === NULL)){
					$result['Tquery']['response']['count'] = $o_Mocha->t_res_count;
					$t_count_override = true;
				}

				$result['shell'][] = shlog( 'init() Tquery.count = '. $result['Tquery']['response']['count']);

				/* --- CONDITION check --- */
				if ($result['Tquery']['response']['count'] > 1) throw new Exception('03.2 - T_Query result contains more than one entry');

				/* --- OVERRIDE res_array
						if testing is enabled--- */
 				//  Limitation: only grab first element of the result array.
				$T_res_array = ($o_Mocha->testing && !empty($o_Mocha->t_res_arr)) ? $o_Mocha->t_res_arr[0] : $T_response['response']['response'][0];
				$result['shell'][] = shlog( '$ T_res_arr: '. json_encode($T_res_array, JSON_PRETTY_PRINT));
				$X_res_array = ($o_Mocha->testing && !empty($o_Mocha->x_res_arr)) ? $o_Mocha->x_res_arr[0] : $X_res_arr[0];

				/* --- CONDITION check --- */
				if (empty($X_res_arr[0])) throw new Exception('02.2 - X_Query result has NO matching entry OR connection to Manex drops!');

				// acct_date set to 1 (1970) as for now
				$t_acct_date = $o_Mocha->default_status || 1;

				// if ($result['Tquery']['response']['count'] < 1) throw new Exception('04 - Ready to insertion mantis_db_invoke_insert()');
				if ($result['Tquery']['response']['count'] < 1 ){
				// meaning that WONO by that unique_key and customer_Id does not exist
					// prepare src to traverse
					$source = $X_res_array;
					$result['shell'][] = shlog( 'cond(Tquery.count<1), current X_res_array: '. implode(', ', $source));

					$source['TIME_STAMP'] 		= $t_timestamp;
					$source['STATUS'] 			= $o_Mocha->default_status;
					$source['Mocha'] 			= $o_Mocha->testing;
					$source['wo_so_table'] 		= $q_wo_so_table;
					$source['assembly_table'] 	= $q_assembly_table;
					$source['customer_table'] 	= $q_customer_table;

					/* -------------------------------------------
					* c_name_lookup by X.unique_key */
					$t_c_name_lookup = HelperUTIL::mantis_db_query(
						$o_Mocha->uniq_key_find,
							$q_assembly_table,
							$q_customer_table,
							$source['UNIQ_KEY']
					);
					// $result['c_name_lookup'] = $t_c_name_lookup;
					$result['shell'][] = shlog( 'res() $t_c_name_lookup: '. json_encode($t_c_name_lookup, JSON_PRETTY_PRINT));

					$result['shell'][] = shlog( 'res() $t_c_name_lookup: '. json_encode($t_c_name_lookup['response'], JSON_PRETTY_PRINT));

					if($t_c_name_lookup['response']['count'])
					{
						$t_c_name_lookup_result = $t_c_name_lookup['response']['response'][0];
						$t_c_name = $t_c_name_lookup_result['CUST_NAME'];
						$T_subset['ASSY_NO'] = $t_c_name_lookup_result['ASSY_NO'];
						/* the line below is extremely important: assign customer_id to the $source object */
						$source['customer_id'] = $t_c_name_lookup_result['CUST_ID'];
						$result['shell'][] = shlog( 'res() $t_c_name_lookup_result: '. json_encode($t_c_name_lookup_result['response'], JSON_PRETTY_PRINT));

					/* customer exists:					*
					*	- non-exist, continue:
					* 		- get associated T.ASSY_NO, T.CUST_NAME
					*			and pair up for diffs->write update query
					*
					*/
						$result['shell'][] = shlog( 'customer exists');

						// $t_assembly_count < 1, continue
						if ($t_c_name != $source['CUST_NAME']){
							// update -> pending query needed
							$result['shell'][] = shlog( '$ diff->pendingUpdate T_name: '. $t_c_name, 'X_name: ' . $source['CUST_NAME']);
							$T_subset['CUST_NAME'] = $t_c_name;
						}

						// so T_subset['CUST_NAME', 'ASSY_NO']
						if ($T_subset['CUST_NAME']) $X_subset['CUST_NAME'] = $source['CUST_NAME'];
						if ($T_subset['ASSY_NO']) $X_subset['ASSY_NO'] = $source['ASSY_NO'];
						if($T_subset && $X_subset){
							$XT_lineup = $this->xt_compare($X_subset, $T_subset);
							$XT_cust_diff = $XT_lineup['response']['all_diff'];
							// build pending updateQuery
						}

						$this->xt_sync_insert_assembly_wo($source);

						$result['shell'][] = shlog( 'prep() to Insert into 2 insert_assembly_table and insert_wo_so_table.');

					} else  {
						$t_customer_lookup = HelperUTIL::mantis_db_query(
							$o_Mocha->customer_find,
								$q_customer_table,
								$source['CUST_NAME']
						);
						$result['shell'][] = shlog( '$t_customer_lookup: '. json_encode($t_customer_lookup,JSON_PRETTY_PRINT));
						if (!$t_customer_lookup['response']['count']){
							/* customer_name not exist -> prep() to insertALL */
							$result['shell'][] = shlog( 'prep() to Queries for InsertALL everything cuz customer_lookup is negative -> customer NOT exist.');
							// 3 src.tables ready to perform query build
							if(!$t_count_override)
								$this->xt_sync_insert_all($source);
						} else {
							$source['customer_id'] = $t_customer_lookup['response']['response'][0]['CUST_ID'];
							$result['shell'][] = shlog( '@340 $ customer_id: '. json_encode($source['customer_id'], JSON_PRETTY_PRINT));
							// 2 src.tables ready to perform query build
							$this->xt_sync_insert_assembly_wo($source);
						}
					}

					// if (!$source['customer_id']) $result['pipe']['error'] = '04.3 - unable to retrieve customer.info, ' . $t_c_id_lookup['response'];

					$result['src'] = $source;

					$result['insert.pending'] 	= $this->xt_sync_insert($source);
					$result['shell'][] = shlog( '@412 $ customer_id: '. json_encode($source['customer_id'], JSON_PRETTY_PRINT));

					/* the INSERT queries are prepared
					* Insertion and return if c_id is valid
					* Otherwise continue XT compare
					*/
					// invoke insertion

					$T_response = HelperUTIL::mantis_db_query($T_query_str, $q_wo_so_table, $q_assembly_table, $q_customer_table, $t_query_trigger);

					$result['fullhouse'] = $T_response['response']['response'][0];
					if ($result['fullhouse'])
					$result['shell'][] = shlog( '$ fullhouse: '. implode(', ', $result['fullhouse']));

					return;
				}

				/* ---
				SYNC.UPDATE ManTis.entry $result['Tquery']['count'] == 1
				   ---
				*/
				$t_customer_id 	= $T_res_array['CUST_ID'];

				$result['XTcompare'] = $this->xt_compare($X_res_array, $T_res_array);

				if (!is_array($result['XTcompare']['response'])) throw new Exception ('05 - XT_Compare failed, review query results or testing inputs');

				$XT_all_diff = $result['XTcompare']['response']['all_diff'];
				$XT_diff = $result['XTcompare']['response']['diff'];
				$XT_same = $result['XTcompare']['response']['same'];
				$XT_null = $result['XTcompare']['response']['null'];
				/* this is again where ugly PHP is madness where simply
				o.xt_compare.res.diff is implemented in other language.
				*/
				$result['shell'][] = shlog( '$ all_diff: '. json_encode($XT_all_diff, JSON_PRETTY_PRINT));

				// set 2 key values;
				$t_uniq_key 	= $X_res_array['UNIQ_KEY']; // using Manex.UNIQ_KEY  on Mantis too.
				$t_wo 			= $X_res_array['WO_NO']; // = $this->getQueryTrigger() = $T_res_array['WO_NO']

				// instead of going and updating everything using 3 queries QUERY_UPDATE_WO_TABLE, QUERY_UPDATE_ASSEMBLY_TABLE, and QUERY_UPDATE_CUSTOMER_TABLE which disregard performance and does all update anyway, the righteous approach should be to the following route of query string build.
				$t_update_for = [];
				$t_unmatch = [];
				if ($XT_all_diff && is_array($XT_all_diff))
				foreach ($XT_all_diff as $key => $value) {
					$t_key = $key;
					switch (true) {
						case preg_match("/SO_NO/", $key, $match):
							// need moderation
							$t_set_at = "sono='%d'";
							$t_update_for[$q_wo_so_table][$t_set_at] = $value[0];
							if ($value[1])
							$t_unmatch[$key] = $value[1];
							break;
						case preg_match("/QTY/", $key, $match):
							$t_set_at = "quantity='%d'";
							$t_update_for[$q_wo_so_table][$t_set_at] = $value[0];
							if ($value[1])
							$t_unmatch[$key] = $value[1];
							break;
						case preg_match("/DUE_DATE/", $key, $match):
							$t_set_at = "due='%d'";
							$t_update_for[$q_wo_so_table][$t_set_at] = $value[0];
							if ($value[1])
							$t_unmatch[$key] = $value[1];
							break;
						case preg_match("/ASSY_NO/", $key, $match):
							// need moderation
							$t_set_at = "number='%s'";
							$t_update_for[$q_assembly_table][$t_set_at] = $value[0];
							if ($value[1])
							$t_unmatch[$key] = $value[1];
							break;
						case preg_match("/REVISION/", $key, $match):
							$t_set_at = "revision='%s'";
							$t_update_for[$q_assembly_table][$t_set_at] = $value[0];
							if ($value[1])
							$t_unmatch[$key] = $value[1];
							break;
						case preg_match("/CUST_NAME/", $key, $match):
							// need moderation
							$t_set_at = "name='%s'";
							$t_update_for[$q_customer_table][$t_set_at] = $value[0];
							if ($value[1])
							$t_unmatch[$key] = $value[1];
							break;
						case preg_match("/CUST_PO_NO/", $key, $match):
							$t_set_at = "pono='%d'";
							$t_update_for[$q_customer_table][$t_set_at] = $value[0];
							if ($value[1])
							$t_unmatch[$key] = $value[1];
							break;
						default:
							# possibility to perform update all with those 3 queries OR use count(array per table)>0 as condition to update an entire table selectively.
							break;
					}
				}
				if($t_unmatch)
					$result['shell'][] = shlog( '$ unmatch: '. implode(', ', $t_unmatch));
				$result['update_prep'] = $t_update_for;
				// prepare update query

				// status<0 deactive, status = 0 obselete, status>0 active, status= 1: , status=2: , status=3: recently updated.
				// on implode add status = 3 by default for having updated received.

				/*if (empty($XT_diff) && empty($XT_null) && !empty($XT_same)) throw new Exception('06 - XT_Compare results in NO diff');
				if (!empty($XT_null)) throw new Exception('07 - XT_Compare results in NULL fields in MantisDb. Update?');
				if ( (!empty($XT_diff) && empty($XT_same) && empty($XT_null))
				   || (!empty($XT_diff) && $T_res_array['WO_NO'] === $X_res_array['WO_NO'] && empty($XT_null))) throw new Exception('04 - Ready to insert ALL mantis_db_invoke_insert()');*/ // meaning the entire Mantis entry is different  very unlikely to happen

				// Unfolding the defined mantis set of tables
 				$q_update_wo_so_table    	= $o_Mocha->update_wo_so_table;
				$q_update_assembly_table 	= $o_Mocha->update_assembly_table;
				$q_update_customer_table 	= $o_Mocha->update_customer_table;

				$q_query_sync_table 		= $o_Mocha->query_sync_table;
				$q_query_sync_table_find 	= $o_Mocha->query_sync_table_find;
				$q_query_sync_table_insert 	= $o_Mocha->query_sync_table_insert;


 				if ($t_update_for[$q_wo_so_table]){
					$t_qr[$q_wo_so_table][$t_timestamp] = $this->update_a_record($t_update_for[$q_wo_so_table],$q_update_wo_so_table, $q_wo_so_table, $t_wo);
					$t_query[$q_wo_so_table] = $t_qr[$q_wo_so_table][$t_timestamp]['query_string'][0];
				}
				if ($t_update_for[$q_assembly_table]){
					$t_qr[$q_assembly_table][$t_timestamp] = $this->update_a_record($t_update_for[$q_assembly_table],$q_update_assembly_table, $q_assembly_table, $t_uniq_key);
					$t_query[$q_assembly_table] = $t_qr[$q_assembly_table][$t_timestamp]['query_string'][0];
				}
				if ($t_update_for[$q_customer_table]){
					$t_qr[$q_customer_table][$t_timestamp] = $this->update_a_record($t_update_for[$q_customer_table],$q_update_customer_table, $q_customer_table, $t_customer_id);
					$t_query[$q_customer_table] = $t_qr[$q_customer_table][$t_timestamp]['query_string'][0];
				}
				// update.pending is how MongoDb (document database style would look like: update.pending\table\time_stamp\query_text
				$result['update.pending'] = $t_qr;
/*				if ($result['insert.pending']){
					foreach ($result['insert.pending'] as $key => $value) {
						$t_query[] = $value['query_string'][0];
					}
				}*/

				/* Verdict from a master CS major & fix: PHP does yield results on its (back-end) view,
				* but there come minor errors (shown in back-end view)
				*; resulting in no json_response fetched and no display on the front.
				* Source of this problem is that PHP is NOT a loose-type language as Javascript, so PHP caught on issues of variable types: unset, null, undefine.. with unfovorable errors
				* Not only definition like this cannot be made with PHP syntax
				* $o ={
						x:'x_value',
						y: 2,
						z: function(){}
					};

					BUT also it is difficult to implement MVC patterns and Modular programming technique (or likely similar technique) in PHP without large amount of efforts, the Laravel, Symphony, CodeIgniter..
					Those implementation simplifies the development by allowing
					CodeFactory and Refactory, Modules load
					with
						require("fs");

						app.use('/profile', profile);

						module.exports = $o;
						// OR
						module.exports = {
								m:'m_value',
								n: 365,
								p: function(){}
						};

					resolving
					Namespace issues (Namespace.Namespace.Namespace, functional Namespaces, ...),
					baseUrl handling (accessing to baseUrl, accessing to routes, abstracting the Url routes, and prevent direct access),
					more organization of project-folder structure (accessing to routes is handled so there is minimal need of using ../../../myfile.ini or require('./a/long/path/to/myModule'); ),
					problem of nested scopes (handling of rootScope and context(ual) scope,
					problem of functional programming (when callbacks happen to be looped|nested everywhere).
				*/
				/* this line below is to fix that minor issue, handling when all matches, NO diff from X&T. Objective:
				* end nicely with fullhouse and notification dump
				* Prevent from happen the addding of another pending_update query as a duplicate into the database.
				*/
				if(!$t_query || count($t_query)<1) {
					$T_response = HelperUTIL::mantis_db_query($T_query_str, $q_wo_so_table, $q_assembly_table, $q_customer_table, $t_query_trigger);
					$result['fullhouse'] = $T_response['response']['response'][0];
					throw new Exception('06.1 - identical set of data from two sources/databases');
				}

				$t_query_text = HelperUTIL::input_string_escape(implode('; ', $t_query));
				$result['update.stock'] = $t_query;
				if($t_unmatch)
				foreach ($t_unmatch as $key => $value) {
					if ($value != $X_res_array[$key])
						$X_res_array[$key] = $value;
				}
				// dump out current manTis.
				$result['insertion'] = $X_res_array;
				$result['shell'][] = shlog( '$ X_res_array: '. json_encode($X_res_array, JSON_PRETTY_PRINT));
				// dump out some remarks
				$t_remark = 'query.wo = ' . $t_query_trigger . '\t\t' . 'query.customer_name = ' .$X_res_array['CUST_NAME'] . '\t\t'. 'query.creator_id = '. $this->getCreatorId() . '\t\t' . 'manTis.current_value: '. json_encode($t_unmatch, JSON_PRETTY_PRINT);

				$response = HelperUTIL::mantis_db_query($q_query_sync_table_find, $q_query_sync_table, $t_query_text);
				if ($response['response']['count']>0) {
					$result['pipe']['stock'] = $t_query_text;
					throw new Exception('06.2 - query_text exists');
				}

				HelperUTIL::mantis_db_query_insert($q_query_sync_table_insert, $q_query_sync_table, $t_query_text, $t_remark, $this->getCreatorId(), $t_timestamp, 0, 0, 0);

				$result['pipe']['stock'] = $t_query_text;

			} else throw new Exception('01 - invalid response from Manex');

		} catch (Exception $e) {
			$result['pipe']['error'] = 'ERROR ' . $e->getMessage(). ' , Reporter: xt_sync_update';
		}
		finally	{
			foreach ($result['shell'] as $value) {
				foreach ($value as $v) {
					$t_bash[] = $v;
				}
			}
			$log = '<body style="color:white;background-color:rgb(0, 58, 88)">'. implode('<br><br>', $t_bash). '</body>';
			file_put_contents('log/xtlog_'.$t_timestamp.'.html', $log, FILE_APPEND);
			return $result;
			// .sync.response = { response :'json', error: 'string if there is error', stock: 'obj'}
		}
	}
}