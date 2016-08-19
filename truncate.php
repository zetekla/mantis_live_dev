<?php
header('Content-Type: application/json');
define('__ROOT__', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('__CFG_FILE__', __ROOT__.'cfg\manextis_conf.ini');
require_once __ROOT__.'core.php';
require_once __ROOT__.'core\helper_util.php';

// load query strings
$conf = HelperUTIL::load_conf(__CFG_FILE__);

$t_tables[] = $conf['MANTIS']['wo_so_table'];
$t_tables[] = $conf['MANTIS']['assembly_table'];
$t_tables[] = $conf['MANTIS']['customer_table'];
$t_tables[] = 'mantis_query_manex_sync_table';

$t_query_truncate 	= $conf['MANTIS']['QUERY_TRUNCATE_TABLE'];

foreach ($t_tables as $table) {
	HelperUTIL::mantis_db_query($t_query_truncate, $table);
}

$files = glob('log/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}

echo ' Success!';
