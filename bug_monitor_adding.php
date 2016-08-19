<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.tokeninput.js"></script>
    <script> jQuery.noConflict();</script>

    <link rel="stylesheet" href="css/token-input-facebook.css" type="text/css" />

    <script type="text/javascript">	 
 	/* jQuery(document).ready(function() {
        $("input[type=button]").click(function () {
			
            // alert("Would submit: " + $(this).siblings("input[type=text]").val());
            console.log(jQuery(this).siblings("input[type=text]").val());
        });
    });*/
    </script>
    <noscript>This page requires Javascript</noscript>
</head>
<body>
<?php
	require_once('dbi_con.php');
	require_once('/core/helper_api.php');

	$result =$mysqli->query("SELECT id,realname FROM mantis_user_table where id>0 LIMIT 15") or die(mysqli_error());
	//create an array
	$user_arr = array();
	while($row=mysqli_fetch_assoc($result)) { $user_arr[] = $row; }

    // print_r($user_arr); echo "<br/><br/>";


    # JSON-encode the response
	$json_res = json_encode($user_arr, JSON_PRETTY_PRINT);

	# Optionally: Wrap the response in a callback function for JSONP cross-domain support
	if(isset($_GET["callback"])) {
		$json_res = $_GET["callback"] . "(" . $json_res . ")";
	}

	# Return the response
	// echo $json_res;

    # write to JSON file
    /* $fp = fopen('userdata.json', 'w');
    fwrite($fp, json_encode($user_arr));
    fclose($fp); // http://www.kodingmadesimple.com/2015/01/convert-mysql-to-json-using-php.html */

?>
<td class="category">
	<?php echo 'Add monitors' ?>
	<?php // print_documentation_link( 'monitors' ) ?>
</td>
<td >
	<form method="get" action="core/bug_api.php">
	<input <?php echo helper_get_tab_index() ?> type="text" id="demo-input-facebook-theme" name="monitors_names" />

	</form>

	<script type="text/javascript">
	<!-- Hide JavaScript
        var ar =<?php echo $json_res?>;

        // console.log(ar);
        // var $monitors_ids= [];

        jQuery(document).ready(function() {
            jQuery("#demo-input-facebook-theme").tokenInput(
                   /* <?php echo $json_res ?> OR <?php echo json_encode($user_arr) ?> without quotes and blocks gives the same result */
                    ar
                , {

					propertyToSearch: "realname",
					hintText: "Enter the name of a person",
					theme: "facebook",

					excludeCurrent: true,
					preventDuplicates: true,

	                onAdd: function (item) {

	                    // alert("Added " + item.id + " " + item.realname);
	                    // var j = ($monitors_ids==="") ? "" : ";";
	                    // $monitors_ids += j + item.id;
	                    // $monitors_ids = $monitors_ids.concat(item.id);

	                    // console.log($monitors_ids);
	                    // $(this).siblings("input[name=monitors_names[]").val().html($monitors_ids);
	                    // $.(siblings("input[name=monitors_names[]").val()).html($monitors_ids);
	                    // $.(siblings("input[name=monitors_names[]").val()).html($monitors_ids);
	                    // console.log($monitors_ids.join(" "));

	                    /*
						if (item.id=="group1"){

						}

						if (item.id=="group2"){

						}
	                    */
	                },
					onDelete: function (item) {
						// if ($monitors_ids.exec()==
						//return window.confirm( "Are you sure you want to delete?" );
	                }
				}
			);
		});

		/* var $arr = $monitors_ids;
		$.post("bug_api.php", {'client_info': $arr}, function(data)
			{ console.log( "Data Loaded: " + data );
	          // some stuffs here
	        }
		); */

        // var selectedValues = $("#demo-input-facebook-theme").tokenInput("get");
        // console.log(selectedValues);
        /*
        JSON file has [{"id":"856","product":"sample1"}, {"id":"1035","product":"sample product 2"}]
        $('#product_tokens').tokenInput('/products.json', { propertyToSearch: 'product' });
        */
    -->
    </script>
</body>
</html>