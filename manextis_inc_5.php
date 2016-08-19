<?php
	define('__ROOT__', dirname(__FILE__).DIRECTORY_SEPARATOR);
	define('__CFG_FILE__', __ROOT__.'cfg\manextis_conf.ini');
	require_once __ROOT__.'core\helper_util.php';
	require_once __ROOT__.'core.php';
	/* ------------------------------------- */
?>
<pre>
<?php

$response = HelperUTIL::mantis_db_query_update(["UPDATE mantis_customer_table_test SET pono='59486' WHERE id = '6'"]);

	$result = ['res1' => $response];
	print_r($result);
?>
</pre>