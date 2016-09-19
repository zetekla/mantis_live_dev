<?php

/* ref: https://github.com/zenithtekla/nodeMySQL/blob/master/config/config.view.js#L13

	var getDirectories = function (srcpath) {
	    return fs.readdirSync(srcpath).filter(function(file) {
	      return fs.statSync(path.join(srcpath, file)).isDirectory();
	    });
	}
	directory = path.resolve("/client/js");

	var _ = require('lodash'),
		fs= require('fs'),
		files = [];


	_.forEach(directory, function(file){
		files.push({name: file, last_mod: fs.statSync(directory+file));
		// http://stackoverflow.com/a/37161107/5828821
	});
	return files;
*/

// Similar to the just written method above, iterate through the entire directory to first get $files array of file names then _.forEach (foreach in PHP) to loop through.

function file_last_mods($directory){
	$files[0]["name"] = "manextis.client.filters.js";
	$files[0]["last_mod"] = filemtime( $directory . $files[0]["name"]);
	return $files[0];
}

print_r(file_last_mods("client/js/"));

/*
// from json_api, it doesn't work
function json_url( $p_url, $p_member = null ) {
	$t_data = url_get( $p_url );
	$t_json = json_decode( utf8_encode($t_data) );

	if( is_null( $p_member ) ) {
		return $t_json;
	} else if( property_exists( $t_json, $p_member ) ) {
		return $t_json->$p_member;
	} else {
		return false;
	}
}

print_r(json_url("cfg/config.json"));

*/

// from helper_util
function load_json(){
	$args = func_get_args();
	$numargs = func_num_args();
	print_r($args);
	// verify if array is empty
	if (empty($args)) {
		$file = self::CFG_FILE;
		if (!$conf = file_get_contents($file, TRUE)) throw new Exception('Unable to open ' . $file . '.');
		else
			$config = $conf;
	} else {
		foreach ($args as $key => $file) {
			// loading configured script
			if (!$conf = file_get_contents($file, TRUE)) throw new Exception('Unable to open ' . $file . '.');
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

print_r(load_json("cfg/config.json")); // works for now