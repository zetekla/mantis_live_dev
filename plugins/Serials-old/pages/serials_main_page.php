<?php
require( "serials_api.php" );
require_once( 'current_user_api.php' );
access_ensure_global_level( plugin_config_get( 'serials_view_threshold' ) );
$row = user_get_row( auth_get_current_user_id() );
extract( $row, EXTR_PREFIX_ALL, 'u' );
html_page_top1();
html_page_top2();
?>
<link rel="stylesheet" href="plugins/Serials/pages/jquery-typeahead-2.1.3/dist/jquery.typeahead.min.css"> 
<script>
  function preventBack(){window.history.forward();
  setTimeout("preventBack()", 0);
  window.onunload=function(){null};
}
  function printDiv() {
	 var printpage = window.open ("","F05400-5 Rev 01 02/24/16");
	 var printcontents="";
	 var now = new Date();
	 var today = now.toLocaleDateString();
	    printcontents += "<html>";
	    printcontents += "<head>";
		printcontents += "<title>F05400-5 Rev 01 02/24/16<\/title>";
		printcontents += "<style>";
		printcontents += "   input[type=\"text\"]{font-family:arial;font-size:12;padding:5px;font-weight:bold;width:100%;display:table-cell;margin:0px 10px;}";
		printcontents += "   input[type=\"checkbox\"]{transform:scale(1.5);font-family:arial;font-size:12;padding:5px;font-weight:bold;}";
		printcontents += "   div[class=\"box\"]{float:left;display:table;width:100%;}";
		printcontents += "   div{float:left;}";
		printcontents += "   .col-md-3{float:left;min-width:150px;max-width:315px;margin-left:5px;margin-right:5px}";
		printcontents += "   p{display:table-cell;width:1px;white-space: nowrap;}";
		printcontents += "<\/style>";
		printcontents += "<div style=\"width:670px;font-family:arial;font-size:12px;\">";
		printcontents += "   <img src=\"http:\/\/www.eminc.com\/skin\/skin1\/images\/en\/framework\/top_banner.jpg\" width=\"670\">";
		printcontents += "   <p1 style=\"margin-left:15px;font-size:12;font-weight:bold;width:670px;\">3519 W. WARNER AVE., SANTA ANA, CA 92704<\/p1>";
		printcontents += "   <hr>";
		printcontents += "   <h2 style=\"text-align:center;\">Serial List<\/h2>";
		printcontents += "   <div style=\"width:425px;margin:0px 15px;font-weight:bold;\">";
		printcontents += "      <div class=\"box\"><p>Assembly Number: <\/p><input type=\"text\" value=\""+ $('input[name="assembly"]').val()+"\"\/><\/div>";
		printcontents += "      <div class=\"box\"><p>Customer Name: <\/p><input type=\"text\" value=\"" + $('input[name="customer"]').val()+"\"\/><\/div>";
		printcontents += "      <div class=\"box\"><p>Customer P.O. Number: <\/p><input type=\"text\" \/><\/div>";
		printcontents += "      <div class=\"box\"><p>Sales Order Number: <\/p><input type=\"text\" value=\""+ $('input[name="sales_order"]').val()+"\"\/><\/div>";
		printcontents += "      <div class=\"box\"><p>Quantity Shipped: <\/p><input type=\"text\" \/><\/div>";
		printcontents += "      <div class=\"box\"><p>Date of Shipment: <\/p><input type=\"text\" value=\""+ today +"\"\/><\/div>";
		printcontents += "   <\/div>";
		printcontents += "   <div style=\"width:185px;float:left;font-weight:bold;margin-right:15px;\">";
		printcontents += "      <div class=\"box\"><p>Rev: <\/p><input type=\"text\" value=\"" + $('input[name="revision"]').val() +"\"\/><\/div>";
		printcontents += "      <div style=\"height:54px;width:193px;\"> <\/div>";
		printcontents += "      <div class=\"box\"><p>Lot Date Code: <\/p><input type=\"text\" \/><\/div>";
		printcontents += "      <div class=\"box\"><p>Order Quantity: <\/p><input type=\"text\" \/><\/div>";
		printcontents += "   <\/div>";
		printcontents += "   <div style=\"width:670\"><hr><\/div> ";
		printcontents += "<\/div>";
		printcontents += "<\/head>";
		printcontents += "<div style=\"width:670px;font-family:arial;font-size:12px;\">";
		printcontents += "   <div style=\"margin-left:10px;width:640\">"+ $("#log-wrapper").html() +"<\/div>";
		printcontents += "<\/div>";
		printcontents += "<\/html>";
     printpage.document.write(printcontents);
     printpage.document.close();
}
  function reload(){
	  location.reload();
}
  function cofc(){
	    var cofcpage = window.open ("","ASF0509-1 Rev 03 02/24/16");
		var cofcContent="";
		var now = new Date();
		var today = now.toLocaleDateString();
	    cofcContent += "<html>";
	    cofcContent += "<head>";
		cofcContent += "<title>ASF0509-1 Rev 03 02/24/16<\/title>";		
		cofcContent += "<style>";
		cofcContent += "   input[type=\"text\"]{font-family:arial;font-size:12;padding:5px;font-weight:bold;width:100%;display:table-cell;margin:0px 10px;}";
		cofcContent += "   input[type=\"checkbox\"]{transform:scale(1.5);font-family:arial;font-size:12;padding:5px;font-weight:bold;}";
		cofcContent += "   div{float:left;}";
		cofcContent += "   div[class=\"box\"]{float:left;display:table;width:100%;}";
		cofcContent += "   p{display:table-cell;width:1px;white-space: nowrap;}";
		cofcContent += "<\/style>";
		cofcContent += "<div style=\"width:670px;font-family:arial;font-size:12px;\">";
		cofcContent += "   <img src=\"http:\/\/www.eminc.com\/skin\/skin1\/images\/en\/framework\/top_banner.jpg\" width=\"670\">";
		cofcContent += "   <p1 style=\"margin-left:15px;font-size:12;font-weight:bold;width:670px;\">3519 W. WARNER AVE., SANTA ANA, CA 92704<\/p1>";
		cofcContent += "   <hr>";
		cofcContent += "   <h2 style=\"text-align:center;\">CERTIFICATE OF COMPLIANCE<\/h2>";
		cofcContent += "   <div style=\"width:425px;margin:0px 15px;font-weight:bold;\">";
		cofcContent += "      <div class=\"box\"><p>Assembly Number: <\/p><input type=\"text\" value=\""+ $('input[name="assembly"]').val()+"\"\/><\/div>";
		cofcContent += "      <div class=\"box\"><p>Customer Name: <\/p><input type=\"text\"\ value=\""+ $('input[name="customer"]').val()+"\"\/><\/div>";
		cofcContent += "      <div class=\"box\"><p>Customer P.O. Number: <\/p><input type=\"text\" \/><\/div>";
		cofcContent += "      <div class=\"box\"><p>Sales Order Number: <\/p><input type=\"text\" value=\""+ $('input[name="sales_order"]').val()+"\"\/><\/div>";
		cofcContent += "      <div class=\"box\"><p>Quantity Shipped: <\/p><input type=\"text\" \/><\/div>";
		cofcContent += "      <div class=\"box\"><p>Date of Shipment: <\/p><input type=\"text\" value=\""+ today +"\"\/><\/div>";
		cofcContent += "   <\/div>";
		cofcContent += "   <div style=\"width:185px;float:left;font-weight:bold;margin-right:15px;\">";
		cofcContent += "      <div class=\"box\"><p>Rev: <\/p><input type=\"text\" value=\"" + $('input[name="revision"]').val() +"\"\/><\/div>";
		cofcContent += "      <div style=\"height:54px;width:193px;\"> <\/div>";
		cofcContent += "      <div class=\"box\"><p>Lot Date Code: <\/p><input type=\"text\" \/><\/div>";
		cofcContent += "      <div class=\"box\"><p>Order Quantity: <\/p><input type=\"text\" \/><\/div>";
		cofcContent += "   <\/div>";
		cofcContent += "   <div style=\"width:670px;margin-left:15px\">";
		cofcContent += "      <div style=\"width:640px;font-weight:bold;\"><br><br>This is to certify that the above shipping quantity against the referenced Purchase Order is in compliance with the contract requirements, specifications, and drawings. Please Check the Box to meet the customer's (Test or 2RoHS)<\/div>";
		cofcContent += "      <div style=\"width:75px;font-weight:bold;\"><br><input type=\"checkbox\"> TEST<\/div>";
		cofcContent += "      <div style=\"width:565px\"><br>This is to certify that the printed wiring assemblies listed below have been tested conforming to specifications requirements. Test reports are on file and will be made available for further examination to any authorized representative upon written request.<\/div>";
		cofcContent += "      <div style=\"width:75px;font-weight:bold;\"><br><input type=\"checkbox\"> 2RoHS<\/div>";
		cofcContent += "      <div style=\"width:565px;\"><br>This is to declare that our Surface Mount Technology and Through factory at Express Manufacturing Inc. is capable of manufacturing products meeting requirements of Restriction on Hazardous Substance 2RoHS.<br><br>\"EMI certifies the following assemblies were manufactured in compliance with the EU Directive 2015\/863\/EU, Restriction of Use of Hazardous Substances 2RoHS Published June 4,2015. EMI certifies that all materials they provide and use in assembling this product meet the requirements of the directive.\"<\/div>";
		cofcContent += "      <div style=\"width:640px\"><br><textarea style=\"width:640px;height:220px;padding:5px;font-size:12px;font-family:arial\"><\/textarea><\/div>";
		cofcContent += "      <div style=\"width:640px;\"><br><\/div>";
		cofcContent += "      <div><h4 style=\"text-align:center;width:670px\">Quality Assurance Representative<\/h4><\/div>";
		cofcContent += "      <div style=\"width:270px;display:table;\"><p>Name: <\/p><input style=\"width:225px;\" value=\"" + $('input[name="real_name"]').val() +"\"\/><\/div>";
		cofcContent += "      <div style=\"width:100px;display:table;\"><p>ID: <\/p><input style=\"width:75px;\"><\/div>";
		cofcContent += "      <div style=\"width:270px;display:table;\"><p>Signature: <\/p><input style=\"width:210px;\"><\/div>";
		cofcContent += "   <\/div>";
		cofcContent += "   <div><br>";
		cofcContent += "   <hr style=\"width:670px;float:left\">";
		cofcContent += "<\/div> ";
    cofcpage.document.write(cofcContent);
	cofcpage.document.close();
}	
</script>
<link rel="stylesheet" href="plugins/Serials/pages/bootstrap/css/bootstrap.css">
<link href="http://esp21/development/mantislive/client/css/manextis.client.style.css" rel="stylesheet" />
<div style="margin-left:30px ; margin-top:10px">
<?php
if ( access_has_project_level( plugin_config_get('format_threshold') ) ) {
    global $g_config_page;
    print_bracket_link( $g_format_page, plugin_lang_get( 'format_title') );
}
?>
<button type="button" id="cofc" class="btn btn-primary" onclick="cofc()"><span class="glyphicon glyphicon-list-alt"></span><b> C of C</b></button>

</div>
	<form ng-app="myApp">
	<result-fetch></result-fetch>	
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
				<div class="col-md-9">
					<div class="input-group pull-left col-md-3" id="typeahead-field1">
						<b>Sales Order:</b></br> 
						<div class="typeahead-container">
							<div class="typeahead-field">
								<input id="field1" type="text" size="100" name="sales_order" required ng-model='query'/>
							</div>
						</div>
					</div>

					<div class="input-group pull-left col-md-3" id="typeahead-field2">
						<b>Customer:</b></br>

						<div class="typeahead-container">
							<div class="typeahead-field">
								<span class="typeahead-query">
									<input id="field2" type="text" size="100" name="customer" required/>
								</span>
							</div>
						</div>
					</div>
					<div class="input-group pull-left col-md-3" id="typeahead-field3">
						<b>Assembly:</b></br>

						<div class="typeahead-container">
							<div class="typeahead-field">
								<span class="typeahead-query">
									<input id="field3" type="text" size="100" name="assembly" required/>
								</span>
							</div>
						</div>
					</div>

					<div class="input-group pull-left col-md-3" id="typeahead-field4">
						<b>Revision:</b></br>

						<div class="typeahead-container">
							<div class="typeahead-field">
								<span class="typeahead-query">
									<input id="field4" type="text" size="100" name="revision" required/>
								</span>
							</div>
						</div>
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
<script src="plugins/Serials/pages/jquery/jquery-1.11.3.min.js" type="text/javascript" ></script>
<script src="plugins/Serials/pages/jquery-typeahead-2.1.3/dist/jquery.typeahead.min.js" type="text/javascript" ></script>	
<script src="plugins/Serials/pages/js/format_proc_api.js" type="text/javascript"></script>	
<script src="plugins/Serials/pages/js/front.js" type="text/javascript"></script>
<script src="http://esp21/development/mantislive/client/js/jquery.js"></script>
<script src="http://esp21/development/mantislive/client/js/angular.js"></script>
<script src="http://esp21/development/mantislive/client/js/moment.min.js"></script>
<script src="http://esp21/development/mantislive/client/js/angular-momentjs.min.js"></script>
<script src="http://esp21/development/mantislive/client/js/manextis.client.filters.js"></script>
<script src="http://esp21/development/mantislive/client/js/manextis.client.directives.js"></script>
<script src="http://esp21/development/mantislive/client/js/manextis.client.controller.js"></script>
<div style="margin-left:5px ; margin-right:5px">

<?php
html_page_bottom1();
?>
