
<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.tokeninput.js"></script>
    <script> jQuery.noConflict();</script>

    <link rel="stylesheet" href="css/token-input-facebook.css" type="text/css" />
    <noscript>This page requires Javascript</noscript>
</head>
<body>
<?php
	require_once('dbi_con.php');
	// $t_users = gpc_get_string( 'bug_monitor_user', '' );

	$result =$mysqli->query("SELECT id,realname FROM mantis_user_table where id>0 LIMIT 15") or die(mysqli_error());

	//create an array
	$user_arr = array();
	while(($row = $result->fetch_assoc()) !== null ) {
		if (!in_array($row['id'], $t_users))
			$user_arr[] = $row;
	}

    # JSON-encode the response
	$json_res = json_encode($user_arr, JSON_PRETTY_PRINT);

	# Optionally: Wrap the response in a callback function for JSONP cross-domain support
	if(isset($_GET["callback"])) {
		$json_res = $_GET["callback"] . "(" . $json_res . ")";
	}

    $mysqli->close();
?>
	<script type="text/javascript">
	<!-- Hide JavaScript
        var ar =<?php echo $json_res?>;
        jQuery(document).ready(function() {
            jQuery("#demo-input-facebook-theme").tokenInput(
                    ar
                , {
					propertyToSearch: "realname",
					hintText: "type in the name of a person",
					theme: "facebook",

					excludeCurrent: true,
					preventDuplicates: true

				}
			);
		});
	-->
    </script>
</body>
</html>
