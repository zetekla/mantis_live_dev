function format_config(e){
	dnm_data.work_order  	= $('input[name="work_order"]').val();
	dnm_data.unique_key 	= e;
	var postdata ={
		work_order: dnm_data.work_order,
		unique_key:	dnm_data.unique_key
	};
	$.ajax({
		type:'POST',
		url: 'plugin.php?page=Serials/controllers/format_config.php',
		data: postdata,
	}).done(function(data){
		data = JSON.parse(data);
		if (!data[0]){
			$("#format").val("");
			$("#format_example").val("");
		}else{
			$("#format").val(data[0].format);
			$("#format_example").val(data[0].example);
		}
		var e = document.getElementById("format");
		var scope = angular.element(e).scope();
		var f = document.getElementById("format_example");
		var scope = angular.element(f).scope();
		scope.format = $('#format').val();
		scope.format_example = $('#format_example').val();
		scope.$digest();
		});
}

var maxWidth = 0;

var format_update =function(){
	dnm_data.unique_key 	= $("#key").val();
	dnm_data.format 	= $("#format").val();
	dnm_data.format_example 	= $("#format_example").val();
	var postdata ={
		unique_key:	dnm_data.unique_key,
		format:	dnm_data.format,
		format_example:	dnm_data.format_example
	};
	
	if (!dnm_data.format || !dnm_data.format_example ){
		$("#format_update").removeClass("btn-primary")
			.addClass("btn-warning");
		setTimeout(reset_btn,5000);
	}else{
		$.ajax({
			type:'POST',
			url: 'plugin.php?page=Serials/controllers/format_update.php',
			data: postdata,
		}).done(function(data){
			if (data == 'success'){
			$("#format_update").removeClass("btn-primary")
				.addClass("btn-success");
			setTimeout(reset_btn,5000);
			} else {
			$("#format_update").removeClass("btn-primary")
				.addClass("btn-warning");
			setTimeout(reset_btn,5000);
			}
		});
	}
	
};

var reset_btn = function(){
	$("#format_update")
		.removeClass("btn-warning")
		.removeClass("btn-success")
		.addClass("btn-primary");	
};

var search_process = function(){
	dnm_data.work_order  	= $('input[name="work_order"]').val();
	dnm_data.scan_input   = $("#scan_result").val();
	dnm_data.unique_key 	= $("#key").val();
	dnm_data.session_id		= $("#session_id").val();
	dnm_data.retrieval		= document.getElementById("retrieval").checked;
	var postdata ={
		work_order: dnm_data.work_order,
		serial_scan: dnm_data.scan_input,
		unique_key:	dnm_data.unique_key,
		session_id: dnm_data.session_id,
		retrieval: dnm_data.retrieval
	};
	$.ajax({
		type:'POST',
		url: 'plugin.php?page=Serials/controllers/search.php',
		data: postdata,
	}).done(function(data){
		data = JSON.parse(data);
		if(!document.getElementById("retrieval").checked){
		if(data.all.count >0){
			$("#log-wrapper").empty();
			$("#search-wrapper") .empty();
			$("#virhe").empty().append("<b>Search Results:</b>")
				.addClass("alert-info")
				.css({  "max-height":"300px",
					"overflow-y" : "auto" });
			var serials = [];
				if (data.all){
				dnm_data.work_order = data.all.response[0].work_order;		
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
							var c = 0;
							for(var j in d){
								++c;
								if (c==8){
									output2 += '<td class="text-center"><a href="#" id="klikaa" class="klikaa_span" onClick="klikaaMethod(this)">' + d[j] + '</a></td>';
								}else{
									output2 += '<td class="text-center">' + d[j] + '</td>';
								}
							}
							output2 += '<td class="text-center">' + String(idx+1) + '</td></tr>' ;
						});
					}
					$("#search-wrapper")
						.append( output2 + "<br/>")
						.addClass("bg-success")
						.css({  "max-height":"300px", "overflow-y" : "auto" })
						.animate({"scrollTop": $("#search-wrapper")[0].scrollHeight}, "slow");

					  if($("#session_id").val()){
						var output1 = "";
						var i = 0;
						dnm_data.quantity_count = 0;
						serials.map(elem =>{
						  i++;
						  dnm_data.quantity_count++;
						  output1 += '<div  class="pull-left" style="padding:2px 25px; display: inline-block"><font color="blue">' + String(i) + '. </font>' + elem + '</div>';
						});
						$("#log-wrapper")
						  .append(output1)
						  .addClass("bg-success")
						  .css({  "max-height":"300px", "overflow-y" : "auto" })
						  .animate({"scrollTop": $("#log-wrapper")[0].scrollHeight}, "slow");
						}
					$("#log-wrapper").children().each(function(index){
						if (($(this).width() + 50) > maxWidth){
							maxWidth = $(this).width() + 50;
						}
					});
					$("#log-wrapper").children().each(function(index){
						var c_width = $(this).width();
						if (c_width < maxWidth){
							$(this).css( {"width" : maxWidth + "px"} );
						}
					});	  
			}
		}else {
			$("#log-wrapper").empty();
			$("#search-wrapper") .empty();
			$("#virhe").empty().append("<b>Search returned No Results!</b>")
				.addClass("alert-danger")
				.css({  "max-height":"300px",
					"overflow-y" : "auto" });
		}
		}else {
			var serials = [];
			var retrivel_error = false;
			var skip=false;
			var output2 = ` <style>
									  tr:nth-child(odd){ background-color: white;}
									  td { nowrap; padding: 1px 3px;}
								  </style>
								  <table class="col-md-12">
								  <tbody id="retrieval_log">
								  <tr>`;
			try{
				data.retrieval.response.map(function(d, idx){					
					serials.push(d.serial_scan);
					if (idx < 1){
						for(var i in d){
							if(!$("#swdiv").html().includes(i)){
							output2 += '<th class="text-center text-uppercase">' + i + '</th>';	
							}else{
								skip=true;
								break;
							}
							
						}
						if(!skip){
						output2 += '<th class="text-center text-uppercase">Count</th></tr>';	
						}
					}
					if (skip){
					output2 = '<tr>';
					}else{
						output2 += '<tr>';
					}
					var c = 0;
					for(var j in d){
						++c;
						output2 += '<td class="text-center">' + d[j] + '</td>';
					}
					output2 += '<td class="text-center">' + String(idx+1) + '</td></tr>' ;
			});
			}catch(e){
				if(e){
					$('#scan_result').val($('#scan_result').val() + ' was not found');
					retrivel_error = true;
				}
			}
			var output1 = "";
				var i = 0;
				serials.map(elem =>{
					if (!$("#printable").html().includes(elem)){
					 i++;
					  dnm_data.quantity_count++;
					  output1 += '<div  class="pull-left" style="padding:2px 25px; display: inline-block"><font color="blue">' + dnm_data.quantity_count + '. </font>' + elem + '</div>';
					  retrivel_error = false;				  
					}else{
					  $('#scan_result').val($('#scan_result').val() + ' is a Duplication');	
					  retrivel_error = true;
					}
				});
				if(!retrivel_error){
					if(skip){
						$("#retrieval_log")
						.append( output2 );
					}else{
						$("#search-wrapper")
						.append( output2 + "<br/>")
						.addClass("bg-success")
						.css({  "max-height":"300px", "overflow-y" : "auto" })
						.animate({"scrollTop": $("#search-wrapper")[0].scrollHeight}, "slow");
					}
				}
					
				document.getElementById('scan_result').select();	
				$("#log-wrapper")
				  .append(output1)
				  .addClass("bg-success")
				  .css({  "max-height":"300px", "overflow-y" : "auto" })
				  .animate({"scrollTop": $("#log-wrapper")[0].scrollHeight}, "slow");
				$("#log-wrapper").children().each(function(index){
					if (($(this).width() + 50) > maxWidth){
						maxWidth = $(this).width() + 50;
					}
				});
				$("#log-wrapper").children().each(function(index){
					var c_width = $(this).width();
					if (c_width < maxWidth){
						$(this).css( {"width" : maxWidth + "px"} );
					}
				});
		}	
	});
};

/* Source: https://github.com/zenithtekla/nodeMySQL/blob/master/config/assets/utils.js */
String.prototype.re = function(pattern){
  pattern = (typeof pattern ==='string') ? new RegExp(pattern) : pattern;
  return pattern.test(this);
};

var klikaaMethod = function(v){
	$("#session_id").val(v.innerHTML);
	$("#scan_result").val("");
	document.getElementById('session_id').disabled=true;
	search_process();
}

var scan_process = function(v){
	dnm_data.sales_order  = $('#field7').val();
	dnm_data.revision     = $("#field3").val();
	dnm_data.unique_key  	= $('#key').val();
	dnm_data.work_order     = $('#field0').val();
	dnm_data.customer_name = $('#field1').val();
	if(!document.getElementById('session_id').disabled){
		document.getElementById('session_id').disabled=true;
		$("#session_id").val("");
	}
	dnm_data.session_id		= $("#session_id").val();
	$("#search-wrapper").empty();
	var rework = angular.element(document.querySelector('[ng-model="rework"]')).scope().rework;
	var postdata ={
		new_scan: v,
		customer_id:       dnm_data.customer_id,
		assembly_id:       dnm_data.assembly_id,
		sales_order:       dnm_data.sales_order,
		format:            dnm_data.format,
		format_example:    dnm_data.format_example,
		revision:          dnm_data.revision,
		unique_key:		   dnm_data.unique_key,
		work_order:		   dnm_data.work_order,
		session_id:		   dnm_data.session_id,
		customer_name:	   dnm_data.customer_name,
		rework:			   rework
	};
	$.ajax({
		type:'POST',
		url: 'plugin.php?page=Serials/controllers/scan_proc.php',
		data: postdata,
	}).done(function(data){
		try{
			var data_in = JSON.parse( data );
		}
		catch(e){
		if (e.constructor == SyntaxError){
			var data_in = JSON.parse('[{"error_code":"undefined"}]');
		}}
		switch (data_in[0].error_code){
			case 'undefined':
			if (data.indexOf('ERROR')>-1){
				$("#virhe") .removeClass("alert-success")
					.addClass("alert-danger");
				$("#virhe").empty().append("Attention: " + data)
					.css({  "max-height":"300px",
						"overflow-y" : "auto" });
				$("#error_log").append("<div>This entry was a Duplication - " + postdata.new_scan);		
			}else {
				$("#virhe") .removeClass("alert-danger")
					.addClass("alert-success");
				dnm_data.quantity_count++;
				if (!$("#session_id").val()){
					document.getElementById('session_id').disabled=true;
					$("#session_id").val(data_in[0].session_id);
				}
				
				document.getElementById('scan_result').select();
				var data_output = '<div class="pull left" style="padding:2px 25px; display: inline-block"><font color="blue">' + dnm_data.quantity_count + '. </font>' + data_in[0].scan + '</div>';
				$("#virhe").empty().append("<div class='text-center'><b>Scan Successful!</b>: " + data_output + "</div>");
				$("#log-wrapper").append( data_output )
					.addClass("bg-success")
					.css({  "max-height":"300px",
						"overflow-y" : "auto" })
					.animate({"scrollTop": $("#log-wrapper")[0].scrollHeight}, "slow");
				$("#log-wrapper").children().each(function(index){
					if (($(this).width() + 50) > maxWidth){
						maxWidth = $(this).width() + 50;
					}
				});
				$("#log-wrapper").children().each(function(index){
					var c_width = $(this).width();
					if (c_width < maxWidth){
						$(this).css( {"width" : maxWidth + "px"} );
					}
				});	
			}
			break;
			case 'Error 20':
				var data = JSON.parse( data );
				$("#virhe").empty().append("<div>" + data[0].error_code + " - " + data[0].error_msg + " : Format - " + data[0].format + " Example - " + data[0].format_example + " SCAN: " + postdata.new_scan)
				.css({  "max-height":"300px",
					"overflow-y" : "auto" })
				.addClass("alert-danger");
				$("#error_log").append("<div>" + data[0].error_code + " - " + data[0].error_msg + " : Format - " + data[0].format + " Example - " + data[0].format_example + " SCAN: " + postdata.new_scan);
			break;
			case 'Error 99':
				var data = JSON.parse( data );
				$("#virhe").empty().append("<div>" + data[0].error_code + " - " + data[0].error_msg + " SCAN: " + postdata.new_scan)
				.css({  "max-height":"300px",
					"overflow-y" : "auto" })
				.addClass("alert-danger");
				$("#error_log").append("<div>" + data[0].error_code + " - " + data[0].error_msg + " SCAN: " + postdata.new_scan);
				break;	
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
  var o = ['sales_order','customer','purchase_order','quantity_count','assembly','revision'];
  for (var i in o){
    var k = o[i];
    if (dnm_data.hasOwnProperty(k)){
      if (dnm_data[k].length)
        $t_str +=  "<div style='padding: 5px 25px'>" + k + ": " + dnm_data[k] + "</div>";
    } else {
      var v = $('input[name="'+k+'"]').val();
      $t_str += "<div style='padding: 5px 25px'>" + k + ": " + v + "</div>";
    }
  }
  return $t_str;
};

var customer_assembly = function(){
	var emi_assembly = $("input[name='assembly']").val();
	var assy_prefix = [
		"^[0-9]{3}-",
		"^ACE[0-9]{3}-",
		"^ALC[0-9]{3}-",
		"^ALF[0-9]{3}-",
		"^CE[0-9]{3}-",
		"^I[0-9]{3}-",
		"^LC[0-9]{3}-",
		"^LF[0-9]{3}-",
		"^SLC[0-9]{3}-"
		];
	for (var i = 0; i < assy_prefix.length; i++){
		var find = new RegExp(assy_prefix[i]);
		emi_assembly = emi_assembly.replace(find,"");
	}
	
	return emi_assembly;
};

var print_html = function(){
	var now = new Date();
	var today = now.toLocaleDateString();
	var header_Content= `<html>
    <head>
	<title>Serial List - Session ID: ` + $("#session_id").val() + `</title>
	<style>
        input[type="text"]{font-family:arial;font-size:12;padding:5px;font-weight:bold;width:100%;display:table-cell;margin:0px 10px;}
        input[type="checkbox"]{transform:scale(1.5);font-family:arial;font-size:12;padding:5px;font-weight:bold;}
        div{float:left;}
        div[class="box"]{float:left;display:table;width:100%;}
        p{display:table-cell;width:1px;white-space: nowrap;}
	</style>
    </head><body style="width:670px">
    <div style="width:670px;font-family:arial;font-size:12px;">
      <img src="http://www.eminc.com/skin/skin1/images/en/framework/top_banner.jpg" width="670">
      <p1 style="margin-left:15px;font-size:12;font-weight:bold;width:670px;">3519 W. WARNER AVE., SANTA ANA, CA 92704</p1>
      <hr>
      <h2 style="text-align:center;">Serial List</h2>
      <div style="width:375px;margin:0px 15px;font-weight:bold;">
          <div class="box"><p>Assembly Number: </p><input type="text" value="` + customer_assembly() +`"/></div>
          <div class="box"><p>Customer Name: </p><input type="text" value="` + $("input[name='customer']").val() +`"/></div>
          <div class="box"><p>Customer P.O. Number: </p><input type="text" value="` + $("input[name='purchase_order']").val() +`"/></div>
		  <div class="box"><p>Sales Order Number: </p><input type="text" value="` + $("input[name='sales_order']").val() +`"/></div>
          
          <div class="box"><p>Date of Shipment: </p><input type="text" value="`+ today +`"/></div>
      </div>
      <div style="width:235px;float:left;font-weight:bold;margin-right:15px;">
         <div class="box"><p>Rev: </p><input type="text" value="` + $("input[name='revision']").val() +`"/></div>
		 
         <div style="height:27px;width:243px;"> </div>
         <div class="box"><p>Lot Date Code: </p><input type="text" /></div>
         <div class="box"><p>Order Quantity: </p><input type="text" value="` + $("input[name='quantity']").val() +`"/></div>
		 <div class="box"><p>Quantity Shipped: </p><input type="text" value="` + dnm_data.quantity_count +`"/></div>
      </div>
	 </div>
	 <hr style="width:670px;float:left">`;
  var x=window.open ("","Serial List");
  var remove_css = "max-height: 300px;";
  var str = $("#printable").html().replace(remove_css,"")
  var replace_width = new RegExp( "width\: " + maxWidth , "g" );
  var new_width = "width: " + (maxWidth * .75);
  str = str.replace(replace_width, new_width);
  x.document.open().write(header_Content +'<div style="max-width:670px;font-family:arial;font-size:12px">' + str + '</div></body></html>');
  x.document.close();
};

var cofc = function(){
  var cofcpage = window.open ("","ASF0509-1 Rev 03 02/24/16");
	var now = new Date();
	var today = now.toLocaleDateString();
  var cofcContent= `<html>
    <head>
	<title>ASF0509-1 Rev 03 02/24/16</title>
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
          <div class="box"><p>Assembly Number: </p><input type="text" value="` + customer_assembly() +`"/></div>
          <div class="box"><p>Customer Name: </p><input type="text" value="` + $("input[name='customer']").val() +`"/></div>
          <div class="box"><p>Customer P.O. Number: </p><input type="text" value="` + $("input[name='purchase_order']").val() +`"/></div>
          <div class="box"><p>Sales Order Number: </p><input type="text" value="` + $("input[name='sales_order']").val() +`"/></div>
          <div class="box"><p>Quantity Shipped: </p><input type="text" value="` + dnm_data.quantity_count +`"/></div>
          <div class="box"><p>Date of Shipment: </p><input type="text" value="`+ today +`"/></div>
      </div>
      <div style="width:185px;float:left;font-weight:bold;margin-right:15px;">
         <div class="box"><p>Rev: </p><input type="text" value="` + $("input[name='revision']").val() +`"/></div>
         <div style="height:54px;width:193px;"> </div>
         <div class="box"><p>Lot Date Code: </p><input type="text" /></div>
         <div class="box"><p>Order Quantity: </p><input type="text" value="` + $("input[name='quantity']").val() +`"/></div>
      </div>
      <div style="width:670px;margin-left:15px">
         <div style="width:640px;font-weight:bold;"><br><br>This is to certify that the above shipping quantity against the referenced Purchase Order is in compliance with the contract requirements, specifications, and drawings. Please Check the Box to meet the customer's (Test or 2RoHS)</div>
         <div style="width:75px;font-weight:bold;"><br><input type="checkbox"> TEST</div>
         <div style="width:565px"><br>This is to certify that the printed wiring assemblies listed below have been tested conforming to specifications requirements. Test reports are on file and will be made available for further examination to any authorized representative upon written request.</div>
         <div style="width:75px;font-weight:bold;"><br><input type="checkbox"> 2RoHS</div>
         <div style="width:565px;"><br>This is to declare that our Surface Mount Technology and Through factory at Express Manufacturing Inc. is capable of manufacturing products meeting requirements of Restriction on Hazardous Substance 2RoHS.<br><br>"EMI certifies the following assemblies were manufactured in compliance with the EU Directive 2015/863/EU, Restriction of Use of Hazardous Substances 2RoHS Published June 4,2015. EMI certifies that all materials they provide and use in assembling this product meet the requirements of the directive."</div>
         <div style="width:640px"><br><textarea style="width:640px;height:250px;padding:5px;font-size:12px;font-family:arial"></textarea></div>
         <div style="width:640px;"><br></div>
         <div><h4 style="text-align:center;width:670px">Quality Assurance Representative</h4></div>
         <div style="width:270px;display:table;"><p>Name: </p><input style="width:225px;" value="` + user +`"/></div>
         <div style="width:100px;display:table;"><p>ID: </p><input style="width:75px;" value="` + userid +`"/></div>
         <div style="width:270px;display:table;"><p>Signature: </p><input style="width:210px;"></div>
      </div>
    </div>`;
  cofcpage.document.write(cofcContent);
  cofcpage.document.close();
};