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
</script>
<section id="ui_data"></section>
<script type="text/template" id="ui-template">
<form ng-app="myApp">
<div class="container col-sm-12">
	<div class="pull-right">
		<button type="button" id="html-painike" class="btn btn-sm btn-primary print"><span class="glyphicon glyphicon-fullscreen"></span>
			{{bold htmlbtn}}</button>
		<button type="button" id="reset" class="btn btn-sm btn-secondary reset"><span class="glyphicon glyphicon-refresh"></span>
			{{bold resetbtn}}</button>
	<?php
		if ( access_has_project_level( plugin_config_get('format_threshold') ) ) {
		    print_bracket_link( $g_format_page, plugin_lang_get( 'format_title') );
		}
		/*$t_now = date( config_get( 'complete_date_format' ) );
		echo "<span id='time'>". $t_now ."</span>";*/
	?>
	</div>

	<div id="top-function-wrapper" class="col-sm-3 no-print">
		<button type="button" id="search" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> {{bold searchbtn}}</button>
		<button type="button" id="tulostaa-painike" class="btn btn-primary print"><span class="glyphicon glyphicon-print"></span>
			{{bold printbtn}}</button>
		<button type="button" id="cofc" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span>
			{{bold cofcbtn}}</button>
	</div>
	<div class="input-group pull-left col-sm-3">
		<b>Work Order Number:</b>
		<div class="typeahead-container">
			<div class="typeahead-field">
				<input type="text" name="wono" size="32" maxlength="32" value="" ng-model='query'></input><span ng-show="query">Search for "{{query}}"</span>
			</div>	
		</div>
	</div>	
	<div id="info" class="col-sm-12">
		<div id="typeahead-field7" class="input-group pull-left col-sm-3">
		{{bold sales_order}} {{required}}:
			<div class="typeahead-container">
				<div class="typeahead-field">
					<input id="field7" type="text" size="100" name="sales_order" required/>
				</div>
			</div>
		</div>

		<div id="typeahead-field1" class="input-group pull-left col-sm-3">
		{{bold customer}} {{required}}:
			<div class="typeahead-container">
				<div class="typeahead-field">
					<span class="typeahead-query">
						<input id="field1" type="text" size="100" name="customer" required/>
					</span>
				</div>
			</div>
		</div>

		<div id="typeahead-field2"  class="input-group pull-left col-sm-3">
		{{bold assembly}} {{required}}:
			<div class="typeahead-container">
				<div class="typeahead-field">
					<span class="typeahead-query">
						<input id="field2" type="text" size="100" name="assembly" required/>
					</span>
				</div>
			</div>
		</div>

		<div id="typeahead-field3"  class="input-group pull-left col-sm-3">
		{{bold revision}} {{required}}:
			<div class="typeahead-container">
				<div class="typeahead-field">
					<span class="typeahead-query">
						<input id="field3" type="text" size="100" name="revision" required/>
					</span>
				</div>
			</div>
		</div>
	</div>
<result-fetch></result-fetch>
	<div id="printable">
		<div id="log-wrapper" class="col-offset-1 col-xs-12 right-scroll"></div>
	</div>

	<div class="row no-print">
		<div id="scan_input" class="input-group input-group-lg col-sm-12 col-centered">
		  <span class="input-group-addon" id="sizing-addon1">Scan Input</span>
		  <input type="text" id="scan_result" name="scan_input" class="form-control" placeholder="{{lang_013}}" aria-describedby="sizing-addon1">
		</div>
	</div>{{! /row }}

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
</script>

<script>
	/*global loadScript */
	loadScript({
		path: UTILS_BOWER_URL+"/jquery/",
		ref: "jquery-1.11.3.min.js",
		// async: true
	},{
		path: UTILS_BOWER_URL+"/handlebars/",
		ref: "handlebars-v4.0.4.js"
	},{
		path: UTILS_BOWER_URL+"/jquery-typeahead-2.1.3/dist/",
		ref: "jquery.typeahead.min.js"
	},{
		path: UTILS_BOWER_URL+"/jQuery-Plugin-Js/",
		ref: "jQuery.print.js"
	},{
		path: PLUGIN_URL_SERIALS+"/js/view_controller/",
		ref: "ui_view_load.js"
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
echo "<div id='lang_get'>". lang_get( 'plugin_url' ) . "</div>";
// echo lang_get( 'word_separator' );
?>