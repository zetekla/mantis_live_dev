var search_process = function(){
	/*global localStorage*/
	/*var addUserData = function(data){
	 dnm_data.user_id        = data.id;
	 dnm_data.user_password  = data.password;
	 dnm_data.user_email     = data.email;
	 dnm_data.user_lastvisit = data.last_visit;
	 };
	 ajaxPost({
	 url: "plugin.php?page=Serials/model/json/userAuth.php",
	 callback: addUserData
	 });*/

	// var hash = JSON.parse(localStorage.getItem("xhr"));
	/*global dnm_data*/
	/*if (hash){
		dnm_data.customer_id      = hash.customer_id;
		dnm_data.assembly_id      = hash.assembly_id;
		dnm_data.assembly_number  = hash.assembly;
	}*/

	dnm_data.work_order  	= $('input[name="work_order"]').val();
	dnm_data.scan_input   = $("#scan_result").val();
	dnm_data.unique_key 	= $("#key").val();
	/*dnm_data.customer = $("#field1").val();
	 dnm_data.assembly = $("#field2").val();
	 dnm_data.revision = $("#field3").val();*/

	// For consistency, I mark it under the name of serial_scan for postdata because dbTable has it as a field name (dbModels should dictate controller logic)
	var postdata ={
		work_order: dnm_data.work_order,
		serial_scan: dnm_data.scan_input,
		unique_key:	dnm_data.unique_key
	};
	console.log(postdata);

	/* global $*/
	$.ajax({
		type:'POST',
		url: 'plugin.php?page=Serials/controllers/search.php',
		data: postdata,
		//contentType: "application/json",
		// dataType: 'json'
	}).done(function(data){
		data = JSON.parse(data);
		console.log(data);
		$("#log-wrapper").empty();
		$("#search-wrapper") .empty();

    var serials = [];

		if (data.all){
			var output2 = ` <style>
			                  tr:nth-child(odd){ background-color: white;}
			                  td { nowrap; padding: 1px 3px;}
		                  </style>
		                  <table class="col-md-12">
		                  <tr>`;
			if (data.all.count > 0){
				data.all.response.map(function(d, idx){
          serials.push(d.serial_scan);

					if (idx < 1){
						for(var i in d){
							output2 += '<th class="text-center text-uppercase">' + i + '</th>';
						}
						output2 += '<th class="text-center text-uppercase">Count</th></tr>';
					}

					output2 += '<tr>';
					for(var j in d){
						output2 += '<td class="text-center">' + d[j] + '</td>';
					}
					output2 += '<td class="text-center">' + String(idx+1) + '</td></tr>' ;
				});
      }
			$("#search-wrapper")
				.append( output2 + "<br/>")
				.addClass("bg-success")
				.css({  "max-height":"300px", "overflow-y" : "auto" })
				.animate({"scrollTop": $("#search-wrapper")[0].scrollHeight}, "slow");

      if(serials){
        var output1 = '<table class="col-md-12">';
        var i = 0;
        serials.map(elem =>{
            i++;
          output1 += '<div class="col-md-3">' + String(i) + '. ' + elem + '</div>';
        });

        output1 +=  '<div>';
        $("#log-wrapper")
          .append( output1 + "<br/>")
          .addClass("bg-success")
          .css({  "max-height":"300px", "overflow-y" : "auto" })
          .animate({"scrollTop": $("#log-wrapper")[0].scrollHeight}, "slow");
      }
		}
	});
};

var scan_process = function(v){
	dnm_data.sales_order  = $('input[name="sales_order"]').val();
	dnm_data.revision     = $("#field3").val();
	dnm_data.unique_key  	= $('input[name="unique_key"]').val();
	dnm_data.work_order     = $('input[name="work_order"]').val();

	var hash = JSON.parse(localStorage.getItem("xhr"));
	dnm_data.customer_id    = hash.customer_id;
	dnm_data.assembly_id    = hash.assembly_id;
	dnm_data.format         = hash.format;
	dnm_data.format_example = hash.format_example;

	var postdata ={
		new_scan: v,
		customer_id:       dnm_data.customer_id,
		assembly_id:       dnm_data.assembly_id,
		sales_order:       dnm_data.sales_order,
		format:            dnm_data.format,
		format_example:    dnm_data.format_example,
		revision:          dnm_data.revision,
		unique_key:		   dnm_data.unique_key,
		work_order:		   dnm_data.work_order
	};

	console.log(postdata);

	$.ajax({
		type:'POST',
		url: 'plugin.php?page=Serials/controllers/scan_proc.php',
		data: postdata,
		//contentType: "application/json",
		// dataType: 'json'
	}).done(function(data){
		if (data.indexOf('ERROR')>-1){
			$("#virhe") .removeClass("alert-success")
				.addClass("alert-danger");
			$("#virhe").empty().append("Attention: " + data)
				.css({  "max-height":"300px",
					"overflow-y" : "auto" });
		} else {
			$("#virhe") .removeClass("alert-danger")
				.addClass("alert-success");
			dnm_data.list_count += 1;
			document.getElementById('scan_result').select();
			var data_output =  "<b>" + dnm_data.list_count + ".</b> " + data;
			$("#virhe").empty().append("<div class='text-center'>last scan: " + data_output + "</div>");

			if (dnm_data.list_count % 3 === 0)
				data_output  = "<div class='col-xs-4'>" + data_output + "</div><div class='clearfix'></div>";
			else data_output = "<div class='col-xs-4'>" + data_output + "</div>";

			$("#log-wrapper")  .append( data_output )
				.addClass("bg-success")
				.css({  "max-height":"300px",
					"overflow-y" : "auto" })
				.animate({"scrollTop": $("#log-wrapper")[0].scrollHeight}, "slow");
		}
	}).fail(function(jqXHR,textStatus, errorThrown){
		$("#virhe") .removeClass("alert-success")
			.addClass("alert-danger")
			.empty().append('!ERROR: ' + textStatus + ", " + errorThrown);
		console.log(jqXHR, textStatus, errorThrown);
	});
};

var p_idx = function(n) {
	return this[ Object.keys(this)[n] ];
};

var print_top = function(){
	var $t_str = print_bot();
	$t_str += "<hr><br/>";
	$("#log-verify").empty().html($t_str);
	return $t_str;
};

var print_bot = function(){
	var $t_str = "<div class='txt-left'>SerialScan v1.1</div><div class='txt-right'>Extract on " + dnm_data.time + " by " + dnm_data.user + " </div><div class='col-xs-12'>";
	var o = ['sales_order','customer','assembly','revision'];
	for (var i in o){
		var k = o[i];
		if (dnm_data.hasOwnProperty(k)){
			if (dnm_data[k].length)
				$t_str +=  "<div class='col-xs-3'>" + k + ": " + dnm_data[k] + "</div>";
		} else {
			var v = $('input[name="'+k+'"]').val();
			$t_str += "<div class='col-xs-3'>" + k + ": " + v + "</div>";
		}
	}
	$t_str += "</div>";
	return $t_str;
};

var print_html = function(){
	var x=window.open('','', 'height='+ (screen.height - 120) +', width='+screen.width);
	x.document.open().write('<head><title>Full-window display</title><link rel="stylesheet" type="text/css" href="plugins/UTILS_plugin/bower_components/mantis_extended_kernel/client/css/print.css"></head>'+
		'<body><div class="container-fluid">'
		+ print_top() + $("#printable").html() +
		'</div></body>');
	// x.close();
};

var print_dialog = function(e){
	e.preventDefault;
	$("#printable").print({
		deferred: $.Deferred(),
		globalStyles : false,
		mediaPrint : false,
		stylesheet: "plugins/UTILS_plugin/bower_components/mantis_extended_kernel/client/css/print.css",
		timeout: 400,
		prepend: print_top()
	});
};

var cofc = function(){
	var cofcpage = window.open ("","ASF0509-1 Rev 03 02/24/16");
	var now = new Date();
	var today = now.toLocaleDateString();
	var cofcContent= `<html>
    <head>
    ASF0509-1 Rev 03 02/24/16
      <style>
        input[type="text"]{font-family:arial;font-size:12;padding:5px;font-weight:bold;width:100%;display:table-cell;margin:0px 10px;}
        input[type="checkbox"]{transform:scale(1.5);font-family:arial;font-size:12;padding:5px;font-weight:bold;}
        div{float:left;}
        div[class="box"]{float:left;display:table;width:100%;}
        p{display:table-cell;width:1px;white-space: nowrap;}
      </style>
    </head>
    <div style="width:670px;font-family:arial;font-size:12px;">
      <img src="http://www.eminc.com/skin/skin1/images/en/framework/top_banner.jpg" width="670">
      <p1 style="margin-left:15px;font-size:12;font-weight:bold;width:670px;">3519 W. WARNER AVE., SANTA ANA, CA 92704</p1>
      <hr>
      <h2 style="text-align:center;">CERTIFICATE OF COMPLIANCE</h2>
      <div style="width:425px;margin:0px 15px;font-weight:bold;">
          <div class="box"><p>Assembly Number: </p><input type="text" value=""+ $('input[name="assembly"]').val()+""/></div>
          <div class="box"><p>Customer Name: </p><input type="text" value=""+ $('input[name="customer"]').val()+""/></div>
          <div class="box"><p>Customer P.O. Number: </p><input type="text" /></div>
          <div class="box"><p>Sales Order Number: </p><input type="text" value=""+ $('input[name="sales_order"]').val()+""/></div>
          <div class="box"><p>Quantity Shipped: </p><input type="text" /></div>
          <div class="box"><p>Date of Shipment: </p><input type="text" value="`+ today +`"/></div>
      </div>
      <div style="width:185px;float:left;font-weight:bold;margin-right:15px;">
         <div class="box"><p>Rev: </p><input type="text" value="" + $('input[name="revision"]').val() +""/></div>
         <div style="height:54px;width:193px;"> </div>
         <div class="box"><p>Lot Date Code: </p><input type="text" /></div>
         <div class="box"><p>Order Quantity: </p><input type="text" /></div>
      </div>
      <div style="width:670px;margin-left:15px">
         <div style="width:640px;font-weight:bold;"><br><br>This is to certify that the above shipping quantity against the referenced Purchase Order is in compliance with the contract requirements, specifications, and drawings. Please Check the Box to meet the customer's (Test or 2RoHS)</div>
         <div style="width:75px;font-weight:bold;"><br><input type="checkbox"> TEST</div>
         <div style="width:565px"><br>This is to certify that the printed wiring assemblies listed below have been tested conforming to specifications requirements. Test reports are on file and will be made available for further examination to any authorized representative upon written request.</div>
         <div style="width:75px;font-weight:bold;"><br><input type="checkbox"> 2RoHS</div>
         <div style="width:565px;"><br>This is to declare that our Surface Mount Technology and Through factory at Express Manufacturing Inc. is capable of manufacturing products meeting requirements of Restriction on Hazardous Substance 2RoHS.<br><br>"EMI certifies the following assemblies were manufactured in compliance with the EU Directive 2015/863/EU, Restriction of Use of Hazardous Substances 2RoHS Published June 4,2015. EMI certifies that all materials they provide and use in assembling this product meet the requirements of the directive."</div>
         <div style="width:640px"><br><textarea style="width:640px;height:220px;padding:5px;font-size:12px;font-family:arial"></textarea></div>
         <div style="width:640px;"><br></div>
         <div><h4 style="text-align:center;width:670px">Quality Assurance Representative</h4></div>
         <div style="width:270px;display:table;"><p>Name: </p><input style="width:225px;" value="" + $('input[name="real_name"]').val() +""/></div>
         <div style="width:100px;display:table;"><p>ID: </p><input style="width:75px;"></div>
         <div style="width:270px;display:table;"><p>Signature: </p><input style="width:210px;"></div>
      </div>
      <div><br>
      <hr style="width:670px;float:left">
    </div>`;
	cofcpage.document.write(cofcContent);
	cofcpage.document.close();
};