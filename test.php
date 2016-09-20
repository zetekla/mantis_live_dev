<?php
require_once('core/helper_util.php');
/*
// from json_api, it DOESN'T work
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

print_p(json_url("data:application/octet-stream;base64,ew0KCSJwcm9kdWN0aW9uIjogew0KCSJ1c2VyIjogInJvb3QiLA0KCSJwYXNzd29yZCI6IG51bGwsDQoJImRhdGFiYXNlIjogImRhdGFiYXNlX3Byb2R1Y3Rpb24iLA0KCSJob3N0IjogIjEyNy4wLjAuMSIsDQoJImRpYWxlY3QiOiAibXlzcWwiDQogIAl9DQp9"));

// print_p(json_url("cfg/config.json"));

// print_p(json_url("https://github.com/zenithtekla/nodeMySQL/blob/master/package.json"));

*/

// from helper_util
print_p(HelperUTIL::get_all_files_from_a_directory());
print_p(HelperUTIL::get_all_json_from_a_directory());

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

	// Similar to the just written method above, iterate through the entire directory to first get $files array of file names then _.forEach (foreach in PHP) to loop through.

	// SEE get_all_files_from_a_directory for rudimentary implementation of the JS method in PHP
	/* src: https://gist.github.com/zenithtekla/0849c76c541113e56266d89e559fde4a */

function file_last_mods($directory){
	$files[0]["name"] = "manextis.client.filters.js";
	$files[0]["last_mod"] = filemtime( $directory . $files[0]["name"]);
	return $files[0];
}

print_p(file_last_mods("client/js/"));

// print_p(HelperUTIL::load_json("cfg/config.json")); // works for now
print_p(HelperUTIL::load_json_string("cfg/config.json")); // works for now

