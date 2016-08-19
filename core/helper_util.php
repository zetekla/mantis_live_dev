<?php
/**
	* @package DbSkewer - CRUD and Sync operation
	* @copyright [Env-System] Copyright (C) 2002 - 2014  MantisBT Team - mantisbt-dev@lists.sourceforge.net
	* @copyright [OnTop-Dev] Copyright (C) 2016 ZeTek - https://github.com/zenithtekla
	*/
	/**
	* DbSkewer CoreAPI
*/
/*
* The helperUTIL class is highly reusable and can be extended for inheritance and instantiation use.
*/
function print_p ($a){
	echo '<pre>';
	print_r($a);
	echo '</pre>';
}

class HelperUTIL{
	const CFG_FILE = 'cfg/manextis_conf.ini';
	protected static $id;
	public static function input_string_valid($str){
        return is_string($str) && isset($str) && !empty($str); // && is_scalar($str)
    }
    public static function input_string_escape($inp) {
    	// escape inputs for form, query and security
        if(is_array($inp))  return array_map(__METHOD__, $inp);
        if(self::input_string_valid($inp)) {
            return str_replace(
                array('\\', "\0", "\n", "\r", "'", '"', "\x1a"),
                array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'),
                $inp
            );
        }
        return $inp;
    }
    public static function string_trim($str){
    	// trim from the beginning and end of the string
    	return (self::input_string_valid($str)) ? trim($str) : 'ERROR unable to trim an invalid string!, ' . $str;
    }
    public static function string_no_spaces($str){
    	return (self::input_string_valid($str)) ? preg_replace('/(\v|\s)+/', ' ', $str) : 'ERROR unable to trim spaces of an invalid string!, ' . $str;
    }
    public static function string_trim_strict($str){
    	// trim spaces from anywhere
    	return (self::input_string_valid($str)) ? self::string_trim(self::string_no_spaces($str)) : 'ERROR unable to razor-trim an invalid string!, ' . $str;
    }
    public static function string_zero_prefix($str){
    	return str_pad($str, 10, '0', STR_PAD_LEFT);
    }
    public static function query_trigger_handler($query_trigger){
    	return self::string_zero_prefix(self::string_trim($query_trigger));
    }
    /**
	 * Returns an array list of query result
	 * @param array1
	 * @param array2
	 * @return array.response listing elements of array1 (x) that is not part of array2 (t)
	 * Limitation: array1 has m elements and array2 has n elements
	 * with m > n , array1: haystack, array2: needles
	 * tx: ManTis-ManeX databases
	 * this method is specific to compare T query result with X query result
	 * and gives response indicating the diff, null, and same
	 * Thus, pass array2 as a haystack for this method to work effectively
	 */
    public static function array_diff_pairs_xt ($arr1, $arr2){
		try {
			foreach ($arr1 as $key => $value){
				$arr1_val = (self::input_string_valid($arr1[$key])) ? strtolower(self::string_trim($arr1[$key])) : $arr1[$key];
				$arr2_val = (self::input_string_valid($arr2[$key])) ? strtolower(self::string_trim($arr2[$key])) : $arr2[$key];
				$result['arr1'][$key] = $arr1_val;
				$result['arr2'][$key] = $arr2_val;
				// verify both value and value type
				if ($arr2_val != $arr1_val){
					$result['response']['all_diff'][$key] = [ $arr1[$key], $arr2[$key] ];
					if($arr2_val == null)
						$result['response']['null'][$key] = [ $arr1[$key], $arr2[$key] ];
					else
						$result['response']['diff'][$key] = [ $arr1[$key], $arr2[$key] ];
				} else  $result['response']['same'][$key] = [ $arr1[$key], $arr2[$key] ];
				// 'as is' similarity
			}
		}
		catch (Exception $e){
			$result['response'] = 'array_diff_pairs_xt ERROR: ' . $e->getMessage();
		}
		finally {
			return $result;
		}
	}
	public static function array_diff_pairs ($arr1, $arr2){
		foreach ($arr2 as $key => $value){
			$arr2_val = (is_string($arr2[$key])) ? strtolower(self::string_trim($arr2[$key])) : $arr2[$key];
			$arr1_val = (is_string($arr1[$key])) ? strtolower(self::string_trim($arr1[$key])) : $arr1[$key];
			$result['arr2'][$key] = $arr2_val;
			$result['arr1'][$key] = $arr1_val;
			if($arr2_val == null || $arr1_val == null)
				$result['response']['null'][$key] = [ $arr2[$key], $arr1[$key] ];
			if ($arr2_val != $arr1_val){
				$result['response']['diff'][$key] = [ $arr2[$key], $arr1[$key] ];
			} else $result['response']['same'][$key] = [ $arr2[$key], $arr1[$key] ];
			// 'as is' similarity
		}
			return $result;
	}
	/**
	 * Returns an array list of query result
	 * @param array1
	 * @param array2
	 * @return array listing (diff) elements out of intersection scope.
	 */
	public static function array_diff_merge($arr1, $arr2){
		return array_merge(array_diff($a, $b), array_diff($b, $a));
	}

	/*
	// An example of deleting an element in an array if you only know the value in PHP
	$key = array_search($valueToSearch,$arrayToSearch);
	if($key!==false){
	    unset($array[$key]);
	}*/

    public static function load_conf(){
    	$args = func_get_args();
    	$numargs = func_num_args();
    	// verify if array is empty
    	if (empty($args)) {
    		$file = self::CFG_FILE;
    		if (!$conf = parse_ini_file($file, TRUE)) throw new Exception('Unable to open ' . $file . '.');
			else
				$config = $conf;
    	} else {
    		foreach ($args as $key => $file) {
    			// loading configured script
				if (!$conf = parse_ini_file($file, TRUE)) throw new Exception('Unable to open ' . $file . '.');
				else {
					if ($numargs == 1)
						$config= $conf;
					else
						$config[]= $conf;
				}
    		}
    	}
		return $config;
    }

    // this method will perform update even if entry does not exist; use w/mantis_db_query to perform check on result count of a SELECT query if necessary, see '06 - query_text exists' of xt_sync_update() method for example
    public static function mantis_db_query_update(){
	try {
			$response = [];
			$args = func_get_args();
			$response['args'] = $args;

			if (count($args) === 1){
				$query = ( is_array($args[0]) ) ? $args[0] : $args;
			} else {
				$response['update_query'] = self::mantis_db_query_build($args);
				$query = [$response['update_query']['query_string']];
			}

			foreach ($query as $qr) {
				if (is_string($qr)){
					db_query_bound( $qr );
				}
				else db_query_bound( $qr[0] );
			}
			// db_query_bound( $query[0] );
			$response['response']['text'] = 'Query update successfully executed.';
		}
		catch (Exception $e){
			$response['response'] = 'mantis_db_query ERROR: ' . $e->getMessage();
		}
    	finally {
    		return $response;
    	}
    }

    // REQUIRE strict; on params, so start off with query_build before calling this method.
    public static function mantis_db_query_insert(){
	try {
		$response = [];
		$args = func_get_args();
		$response = self::mantis_db_query_build($args);
		$query_string = $response['query_string'];
		$response['response'] = self::mantis_db_invoke_insert($query_string, $response['table_of_insert']);
		$response['id'] = $response['response']['id'];
	}
		catch (Exception $e){
			$response->response = 'mantis_db_insert ERROR: ' . $e->getMessage();
		}
    	finally {
    		return $response;
    	}
    }

    // REQUIRE strict; on params, so start off with query_build before calling this method.
    public static function mantis_db_invoke_insert($query, $table){
	try {
		if (is_array($query)){
			foreach ($query as $qr) {
				db_query_bound( $qr);
				$response['id'][] = db_insert_id( $table );
			}
		} else {
			db_query_bound( $query );
			$response['id'][] = db_insert_id( $table );
		}
	}
		catch (Exception $e){
			$response->response = 'mantis_db_invoke_insert ERROR: ' . $e->getMessage();
		}
    	finally {
    		return $response;
    	}
    }
    public static function mantis_db_query_select(){
	try {
		$response = [];
		$args = func_get_args();
		// $response['args'] = $args;
		$result_buffer_check = false;
		if (count($args) === 1){
			$query = ( is_array($args[0]) ) ? $args[0] : $args;
		} else {
			$response['select_query'] = self::mantis_db_query_build($args);
			$query = $response['select_query']['query_string'];
		}

		foreach ($query as $qr) {
			if (!is_array($qr)){
				$result[] = db_query_bound( $qr );
				$result_buffer_check = true;
			}
		}
		if (is_array($result)){
			$result = array_unique($result);
			if (count($result) > 1) throw new Exception('More than one SELECT query executed');
			if (count($result) == 0) throw new Exception('No SELECT query performed');
		}
		$result = ($result_buffer_check) ? $result[0] : $result;
		$response['count'] = db_num_rows( $result );
		if ($response['count']>0)
    	for ($i=0; $i<$response['count']; $i++ ){
    		$response['response'][] = db_fetch_array($result);
    	}
	}
		catch (Exception $e){
			$response['response'] = 'mantis_db_query_select ERROR: ' . $e->getMessage();
		}
    	// $response["response"] = mysql_query( $query );
    	finally {
    		return $response;
    	}
    }

    private static function is_sql_word($string){
    	$result = new stdClass();
    	switch (true) {
    		case preg_match("/SELECT/", $string, $match):
    			$result->bool = true;
    			$result->word = $match;
    			break;
    		case preg_match("/INSERT/", $string, $match):
    			$result->bool = true;
    			$result->word = $match;
    			break;
    		case preg_match("/UPDATE/", $string, $match):
    			$result->bool = true;
    			$result->word = $match;
    			break;
    		case preg_match("/DELETE/", $string, $match):
    			$result->bool = true;
    			$result->word = $match;
    			break;
    		case preg_match("/TRUNCATE/", $string, $match):
    			$result->bool = true;
    			$result->word = $match;
    			break;
    		default:
    			$result->bool = false;
    			break;
    	}
    	return $result;
    }

    public static function mantis_db_query_build(){
		try {
			$response = [];
			$args = func_get_args();
			if (count($args)==1) $args = $args[0];

			$query_string = self::string_trim($args[0]);
			$query_word = substr($query_string, 0, 9);
			$o_sql_word = self::is_sql_word($query_word);
			if ($o_sql_word->bool) {
				if (strlen($query_string) < 9) array_shift($args);
				$response['query_word'] = $o_sql_word->word;
			}

			$params = $args;
			$count = 0;
			foreach ($params as $param) {
				// not going to handle many arrays here
				if (is_array($param)){
 					$t_query[] = vsprintf($params[0], $param);
 					$t_table_of_insert = $param[0];
					$count++;
				}
			}

			$response['params'] = $params;
			if ($count < 1)
				$response['query_string'] = call_user_func_array( 'sprintf', $params);
			else{
				$response['query_string'] = array_unique($t_query);
				$response['table_of_insert'] = $t_table_of_insert;
			}
		}
		catch (Exception $e){
			$response->response = 'mantis_db_query_build ERROR: ' . $e->getMessage();
		}
		finally {
    		return $response;
    	}
    }
    /**
	 * Returns an array list of query result
	 * @param [0] = $query_string
	 * @param [1], [2].. = array of parameters to pass in a query function be invoked through swtich
	 * @return array
	 */
    public static function mantis_db_query(){
	try {
		$response = [];
		$args = func_get_args();
		$response = self::mantis_db_query_build($args);
		$query_word = $response['query_word'];
		$query_word = (is_array($query_word)) ? $query_word[0] : $query_word;
		$query_string = $response['query_string'];
		switch (true) {
    		case ($query_word ==='SELECT'):
    			$response['response'] = self::mantis_db_query_select($query_string );
    			break;
    		case ($query_word ==='INSERT'):
    			// invoke insertion
    			$response['response'] = self::mantis_db_invoke_insert($query_string, $response['table_of_insert']);
    			break;
    		case ($query_word ==='UPDATE'):
    			$response['response'] = self::mantis_db_query_update($query_string);
    			break;
    		case ($query_word ==='DELETE'):
    			$response['type'] = gettype($query_string);
    			break;
    		case ($query_word ==='TRUNCATE'):
    			$response['response'] = db_query_bound($query_string);
    			break;
    		default:
    			#
    			break;
    	}
	}
		catch (Exception $e){
			$response['response'] = 'mantis_db_query ERROR: ' . $e->getMessage();
		}
    	finally {
    		return $response;
    	}
    }
    public static function getCurlData ($http, $q, $status = '&status='){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $http.$q.$status);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}