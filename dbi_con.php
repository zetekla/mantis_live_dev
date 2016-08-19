<?php
require_once( 'config_inc.php' );
$time_start = microtime(true);
$mysqli = new mysqli($g_hostname, $g_db_username, $g_db_password,$g_database_name);
if (!$mysqli) die('Connect failed: ' . mysqli_error());
mysqli_select_db($mysqli, $g_database_name) or die('Error select: '.mysqli_error());
?>