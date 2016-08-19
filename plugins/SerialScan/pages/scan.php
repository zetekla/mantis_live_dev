<?php 
require( "ss_api.php" );
require_once( 'core.php' );
require_once( 'current_user_api.php' );
access_ensure_global_level( plugin_config_get( 'ss_view_threshold' ) );
html_page_top1();
html_page_top2();
?>
<div ng-app="SerialScan">
<script src="js/ui-bootstrap-tpls-2.0.0.min.js"></script>
<script src="js/angular.min.js"></script>
<div style="margin-left:30px ; margin-top:10px"></div>
<?php
if ( access_has_project_level( plugin_config_get('format_threshold') ) ) {
    global $g_config_page;
    print_bracket_link( $g_format_page, plugin_lang_get( 'format_title') );
}
?>
<button type="button" id="cofc" class="btn btn-primary" onclick="cofc()"><span class="glyphicon glyphicon-list-alt"></span><b> C of C</b></button>
	<form>
		<div class="container-fluid">
			<div class="row">
				<div id="top-function-wrapper" class="col-md-3">
					</br>
						<button type="button" id="search" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span>
							<b>Search</b></button>
						<button type="button" id="print" class="btn btn-primary print" onclick="printDiv()"><span class="glyphicon glyphicon-print"></span>
							<b>Print</b></button>
						<button type="button" id="reset" class="btn btn-secondary reset" onclick="reload()"><span class="glyphicon glyphicon-refresh" ></span>
							<b>Reset</b></button>
				</div>		
				<div class="col-md-9" ng-controller="TemplateController as template">
					<div class="input-group pull-left col-md-3" ng-repeat="field in template.fields" id="{{$index}}"><div>
						<b>{{$index}}{{field.display}}</b>
						<input type="text" ng-model="model.$index">{{model.$index}}</div>
                    </div>
      </div>
			</div>
			<div class="row" style="padding-left:20px ; padding-right:20px ;padding-top:20px">	
				<div id="log-wrapper" class="col-md-12 right-scroll " style="border-radius: 4px; border: 1px solid transparent"></div>
			</div>
			<div class="row">
				<div class="container col-sm-12 no-print">
				<div class="row no-print">
					<div id="scan_input" class="input-group input-group-lg col-sm-12 col-centered">
					  <span class="input-group-addon" id="sizing-addon1">Scan Input</span>
					  <input type="text" id="scan_result" name="scan_input" class="form-control" placeholder="Scan Serial Number Barcode" aria-describedby="sizing-addon1">
					</div>
				</div>
				<input type="hidden" name="format" id="field5">
				<input type="hidden" name="format_example" id="field6">
				<input type="hidden" name="assembly_id" id="field7">
				<input type="hidden" name="customer_id" id="field8">
				<input type="hidden" name="list_count" id="field9"> 
                <input type="hidden" name="real_name" value=<?php echo '"'. $u_realname . '"';?> id="field10">
				<div class="hidden no-print" id="result"></div>
				<div id="konsoli_loki">
					<div id="virhe" class="alert"></div>
					<div id="virhe_kuvaus" class="alert"></div>
				</div>
				<div class="row" style="padding-left:20px ; padding-right:20px ;padding-top:20px">	
					<div id="search-wrapper" class="col-md-12 right-scroll " style="border-radius: 4px; border: 1px solid transparent"></div>
				</div>
				</div>
			</div>
		</div>
	
	</form>
<script <?php echo 'src="' , plugin_page() , 'jquery/jquery-1.11.3.min.js"'; ?> type="text/javascript" ></script>
<script <?php echo 'src="' , plugin_page() , 'js/format_proc_api.js"'; ?> type="text/javascript"></script>	
<script <?php echo 'src="' , plugin_page() , 'js/front.js" type="text/javascript">'; ?></script>
<script src="client/js/jquery.js"></script>
<script src="client/js/angular.js"></script>
<script src="client/js/moment.min.js"></script>
<script src="client/js/angular-momentjs.min.js"></script>
<script src="client/js/manextis.client.filters.js"></script>
<script src="client/js/manextis.client.directives.js"></script>
<script src="client/js/manextis.client.controller.js"></script>
</div>
<?php
html_page_bottom1();
?>
