<?php

/* src: https://gist.github.com/zenithtekla/0849c76c541113e56266d89e559fde4a */
function get_all_images_from_a_directory($folder='test_images/', $type='{*.jpg, *.JPG, *.JPEG, *.png, *.PNG}'){
    $images = [];
    $all_images = glob($folder.$type, GLOB_BRACE);
    $count = count($all_images);
    for ($i = 0; $i < $count; $i++) {
        $images[$i] = new stdClass();
        $images[$i]->src = $all_images[$i];
        $images[$i]->name = substr($all_images[$i],strlen($folder),strpos($all_images[$i], '.')-strlen($folder));
        $images[$i]->modified = date('YmdHis', filemtime($all_images[$i])).$i;
    }
    return $images;
}

function get_all_files_from_a_directory($folder='test_files/', $type='{*.css, *.js}'){
    $files = [];
    $all_files = glob($folder.$type, GLOB_BRACE);
    $count = count($all_files);
    for ($i = 0; $i < $count; $i++) {
        $files[$i] = new stdClass();
        // $files[$i]->path = $folder;
        $files[$i]->src = $all_files[$i];
        $files[$i]->name = substr($all_files[$i],strlen($folder),strpos($all_files[$i], '.')-strlen($folder));
        $files[$i]->modified = date('YmdHis', filemtime($all_files[$i])).$i;
    }
    return $files;
}

print_r(get_all_files_from_a_directory());

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

print_r(json_url("data:application/octet-stream;base64,ew0KCSJwcm9kdWN0aW9uIjogew0KCSJ1c2VyIjogInJvb3QiLA0KCSJwYXNzd29yZCI6IG51bGwsDQoJImRhdGFiYXNlIjogImRhdGFiYXNlX3Byb2R1Y3Rpb24iLA0KCSJob3N0IjogIjEyNy4wLjAuMSIsDQoJImRpYWxlY3QiOiAibXlzcWwiDQogIAl9DQp9"));

// print_r(json_url("cfg/config.json"));

// print_r(json_url("https://github.com/zenithtekla/nodeMySQL/blob/master/package.json"));

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

function load_json_obj($file){
	$json_file = file_get_contents($file);
	return json_decode($json_file);
}

function load_json_arr($file){
	$json_file = file_get_contents($file);
	return json_decode($json_file, true);
}

function load_json_string($file){
	return file_get_contents($file);
}

// print_r(load_json("cfg/config.json")); // works for now
print_r(load_json_string("cfg/config.json")); // works for now

