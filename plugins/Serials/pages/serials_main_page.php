<?php
require_once( "plugins/Serials/core/format_check_api.php" );
require_once( 'current_user_api.php' );
access_ensure_global_level( plugin_config_get( 'serials_view_threshold' ) );
html_page_top1();
html_page_top2();
?>
<script>
	function preventBack(){
		window.history.forward();
		setTimeout("preventBack()", 0);
		window.onunload=function(){null};
	}
</script>
<script src="plugins/UTILS_plugin/bower_components/mantis_extended_kernel/client/js/buildscript.js" type="text/javascript"></script>
<link href="client/css/manextis.client.style.css" rel="stylesheet" />
<script>
	/*global ENV_MODE, UTILS_BOWER_URL, MANTIS_EXTENDED_KERNEL, PLUGIN_URL_SERIALS*/
	/*global loadScript */
	loadScript({
		path: UTILS_BOWER_URL+"/jquery-typeahead-2.1.3/dist/",
		ref: "jquery.typeahead.min.css"
	}, {
		path: UTILS_BOWER_URL+"/bootstrap/css/",
		ref: "bootstrap.css"
	}, {
		path: PLUGIN_URL_SERIALS+"/js/view_model/",
		ref: "ui_data.js"
	});
	var user = "<?php echo user_get_field( auth_get_current_user_id() , 'realname' ) ?>";
	var userid = "<?php echo preg_replace('/\D/','', user_get_field( auth_get_current_user_id() , 'username' )) ?>"
</script>
<section id="ui_data"></section>
<form id="myform" name="form" ng-app="myApp" >
<div class="container col-sm-12">
	<div class="pull-right" style="position:absolute; background: white; right: 10px;  z-index: 1000">
			<button type="button" id="reset" class="btn btn-secondary reset "><span class="glyphicon glyphicon-refresh"></span>
			<b>Reset</b></button>
	<?php
		if ( access_has_project_level( plugin_config_get('format_threshold') ) ) {
	?>
	<button id="format_config" class="btn btn-primary pull-right" type="button" ng-model="collapsed" ng-click="collapsed=!collapsed"><span class="glyphicon glyphicon-qrcode"></span>
	  Format Config
	</button>
	<br>
	<div id="panel" ng-show="collapsed" class="panel panel-default">
		<div class="panel-body">
			<span class="pull-left"><b>Unique Key</b></span>
			<input class="form-control pull-right" id="key" type="text" size="25" name="unique_key" ng-model='unique_key'/><br>
			<span class="pull-left"><b>Format Example</b></span>
			<input class="form-control pull-right" id="format_example"  name="format_example" type="text" size="25" ng-model='format_example' ng-pattern='format'/><br>
			<span class="pull-left"><b>Format Code</b></span>
			<input class="form-control pull-right" id="format" type="text" size="25" ng-model='format'/><br>
			<br>
			<div class="pull-left"> 
				<span class="pull-left alert alert-success glyphicon glyphicon-ok" ng-show="form.format_example.$valid && format_example && format"> - FORMAT SUCCESSFULLY MATCHES EXAMPLE</span>
				<span class="pull-left alert alert-danger glyphicon glyphicon-remove" ng-show="!form.format_example.$valid && !format_example && format"> - FORMAT DOES NOT MATCH THE PROVIDED EXAMPLE!</span>
			</div><br>
			<div class="pull-right">	
			<button id="format_update" type="button" class="btn btn-primary"> Update format</button>
			</div>
		</div>
	</div>
	<?php		
		}else{
	?>
	<br>
	<input class="pull-right hidden" id="key" type="text" size="15" name="unique_key" ng-model='unique_key'/>
	<?php
		}
		/*$t_now = date( config_get( 'complete_date_format' ) );
		echo "<span id='time'>". $t_now ."</span>";*/
	?>
	</div>
	<div id="top-function-wrapper">
		<button type="button" id="search" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span>
			<b>Search</b></button>
		<button type="button" id="html-painike" class="btn btn-primary print"><span class="glyphicon glyphicon-print"></span>
			<b>Print</b></button>
		<button type="button" id="cofc" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span>
			<b>C of C</b></button>
	</div>
	<div class="col-sm-12">
		<div class="input-group pull-left col-sm-3" >
			<b>Work Order Number:</b>
			<div class="typeahead-container">
				<div >
					<input id="field0" class="form-control" type="text" name="work_order" size="32" maxlength="32" value="" placeholder="Example 123456A" ng-model='query'></input>
					<span id="msg" ng-show="query" ng-cloak>
						<div class="alert alert-warning" ng-show="error" ng-cloak>
							<strong>No Results Found for {{query}}!</strong><br>Please Try Again.
						</div>
						<img ng-show="!error" src="images/load-am.gif" style="position: absolute;top: -2px;right:10px; z-index: 2" ng-cloak>
					</span>
				</div>	
			</div>
		</div>
		<div class="input-group pull-left col-sm-3">
			<b>PO Number:</b>
			<div class="typeahead-container">
				<div>
					<input class="form-control" type="text" name="purchase_order" size="32" maxlength="32" value="" ng-model='purchase_order' disabled></input>
				</div>	
			</div>
		</div>
		<div class="input-group pull-left col-sm-3">
			<b>Quantity:</b>
			<div class="typeahead-container">
				<div>
					<input  class="form-control" type="text" name="quantity" size="32" maxlength="32" value="" ng-model='quantity' disabled></input>
				</div>	
			</div>
		</div>
		<div class="input-group pull-left col-sm-3">
			<b>Due Date:</b>
			<div class="typeahead-container">
				<div>
					<input  class="form-control" type="text" name="due_date" size="32" maxlength="32" value="" ng-model='due_date' disabled></input>
				</div>	
			</div>
		</div>
		
	</div>	
	<div id="info" class="col-sm-12">
		<div id="typeahead-field7" class="input-group pull-left col-sm-3">
		<b>Sales Order</b>
			<div class="typeahead-container">
				<div>
					<input class="form-control" id="field7" type="text" size="100" value="" name="sales_order" ng-model='sales_order' disabled></input>
				</div>
			</div>
		</div>

		<div id="typeahead-field1" class="input-group pull-left col-sm-3">
		<b>Customer</b>
			<div class="typeahead-container">
				<div>
					<span class="typeahead-query">
						<input class="form-control" id="field1" type="text" size="100" name="customer" ng-model='customer' disabled></input>
					</span>
				</div>
			</div>
		</div>

		<div id="typeahead-field2"  class="input-group pull-left col-sm-3">
		<b>Assembly Number</b>
			<div class="typeahead-container">
				<div>
					<span class="typeahead-query">
						<input class="form-control" id="field2" type="text" size="100" name="assembly" ng-model='assembly' disabled></input>
					</span>
				</div>
			</div>
		</div>

		<div id="typeahead-field3"  class="input-group pull-left col-sm-3">
		<b>Revision</b>
			<div class="typeahead-container">
				<div>
					<span class="typeahead-query">
						<input class="form-control" id="field3" type="text" size="100" name="revision" ng-model='revision' disabled></input>
					</span>
				</div>
			</div>
		</div>
	</div>
<result-fetch></result-fetch>
	<div id="printable">
		<div id="log-wrapper" class="col-offset-1 col-xs-12 right-scroll pull-right"></div>
	</div>

	<div class="row no-print">
		<div id="scan_input" class="input-group input-group-lg col-sm-12 col-centered">
		  <span class="input-group-addon" id="sizing-addon1">Scan Input</span>
		  <input type="text" id="scan_result" name="scan_input" class="form-control" placeholder="Scan Serial Number Barcode - Auto Submit" aria-describedby="sizing-addon1">
		</div>
	</div>

	<div class="hidden no-print" id="log-verify"></div>

	<div id="konsoli_loki">
		<div id="virhe" class="col-md-12 alert"></div>
		<div id="virhe_kuvaus" class="alert"></div>
	</div>

	<div class="row" style="padding-left:20px ; padding-right:20px ;padding-top:20px">
		<div id="search-wrapper" class="col-md-12 right-scroll " style="border-radius: 4px; border: 1px solid transparent"></div>
	</div>
</div>
</form>
<script>
	/*global loadScript */
	loadScript({
		path: UTILS_BOWER_URL+"/jquery/",
		ref: "jquery-1.11.3.min.js",
		// async: true
	},{
		path: UTILS_BOWER_URL+"/jquery-typeahead-2.1.3/dist/",
		ref: "jquery.typeahead.min.js"
	},{
		path: UTILS_BOWER_URL+"/jQuery-Plugin-Js/",
		ref: "jQuery.print.js"
	},{
		path: MANTIS_EXTENDED_KERNEL+"/client/js/",
		ref: "ajax_typeahead_api.js"
	},{
		path: PLUGIN_URL_SERIALS+"/js/",
		ref: ["format_proc.js", "process_api.js", "front.js"]
	},{
		path: MANTIS_EXTENDED_KERNEL + "/client/css/",
		ref: ["default.css"]
		// type: "media" || type: "all"
	});
</script>
<script src="client/js/jquery.js"></script>
<script src="client/js/angular.js"></script>
<script src="client/js/moment.min.js"></script>
<script src="client/js/angular-momentjs.min.js"></script>
<script src="client/js/manextis.client.filters.js"></script>
<script src="client/js/manextis.client.directives.js"></script>
<script src="client/js/manextis.client.controller.js"></script>
<?php
html_page_bottom1( __FILE__ );
// echo lang_get( 'word_separator' );
?>