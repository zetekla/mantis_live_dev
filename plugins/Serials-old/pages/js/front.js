(function(){
    /*var content = document.getElementById('myData');
    var html = '';
    var data = {
        sales_order: 'Sales Order',
        customer: 'Customer',
        assembly: 'Assembly',
        revision: 'Revision',
		lang_013:"new serial number (auto-submit)",
		list_count: 'List Count',
		printbtn: 'Print',
		searchbtn: 'Search',
		resetbtn: 'Reset',
    };
	localStorage.removeItem("xhr");
    Handlebars.registerHelper('heading',function(text){
        text = Handlebars.escapeExpression(text);
       return new Handlebars.SafeString('<h2>'+text+'</h2>');
    });
    Handlebars.registerHelper('bold',function(text){
        text = Handlebars.escapeExpression(text);
       return new Handlebars.SafeString('<b>'+text+'</b>');
    });
    Handlebars.registerHelper('italic',function(text){
        text = Handlebars.escapeExpression(text);
       return new Handlebars.SafeString('<i>'+text+'</i>');
    });
    Handlebars.registerHelper("required", function(){
			return new Handlebars.SafeString('<span class="required"> * </span>');
	});
	Handlebars.registerHelper("notGreater", function(num1, num2, options){
		if (num2 > num1){
			return options.fn(this);
		} else {
			return options.inverse(this);
		}
	});		
    var template = Handlebars.compile(document.getElementById('url-template').innerHTML);
    content.innerHTML += template(data);*/

	document.getElementById('typeahead-field2').style.color="Red";
	document.getElementById('typeahead-field3').style.color="Red";
	document.getElementById('typeahead-field4').style.color="Red";
	$('#field1').focus();
    "use strict";
/*     var dnm_data = {
        time: $('.login-info-middle :first-child').text(),
        list_count: 0
    }; */

    var xhr = {};
    var old;
    var oldh = {};

    var fn1 = function(){
        return new Promise(function(suc, err){
            extTypeahead({
                slt: "#field2",
                url: "/development/mantislive/plugin.php?page=Serials/json/customer.php",
                callback: function(item){
                    var hash = {};
                    if (localStorage.getItem("xhr")) {
                        old = localStorage.getItem("xhr");
                        oldh = JSON.parse(localStorage.getItem("xhr"));
                        hash = oldh;
                    }
                    hash.id = item.id;
					hash.name = item.nimi;
					hash.group = item.group;
					
                    localStorage.setItem("xhr", JSON.stringify(hash));
                    console.log(" # post-assign@fn1 $ print xhr ");
                    console.log(localStorage.getItem("xhr"));
                    console.log(" # post-assign@fn1 $ print old ");
                    console.log(old);
                    setTimeout(function(){
                        suc(hash);
                    },10);
					/* $('#field2').typeahead('close'); */
					document.getElementById('typeahead-field2').style.color="Black";
					$('#field3').focus();
					$('#field9').val("0");
					$('#field8').val(hash.id);
                }
            });
        });
    };
	
    /*dnm_data.customer_name = xhr.nimi;
    dnm_data.customer_id = xhr.id;*/
    var fn2 = function (o){
        return new Promise(function(suc, err){
            extTypeahead({
                slt: "#field3",
                url: {
                    type: "POST",
                    url : "/development/mantislive/plugin.php?page=Serials/json/assembly.php",
                    data: { 'customer_id' : o.id }
                },
                callback: function(item){
                    var hash = JSON.parse(localStorage.getItem("xhr"));
                    o.number = item.nimi;
                    hash.number = item.nimi;
                    // console.log(dnm_data);
                    localStorage.setItem("xhr", JSON.stringify(hash));
                    console.log(" # post-async@fn2 $ ");
                    console.log(localStorage.getItem("xhr"));
                    // $("#field3").val('');
                    setTimeout(function(){
                        suc(o);
                    },10);
					document.getElementById('typeahead-field3').style.color="Black";
					$('#field4').focus();
                }
            });
        });
    };

    var fn3 = function (o){
        return new Promise(function(suc,err){
            /* global extTypeahead */
            extTypeahead({
                slt: "#field4",
                url: {
                    type: "POST",
                    url: "/development/mantislive/plugin.php?page=Serials/json/revision.php",
                    data : {
                        number: o.number,
                        id: o.id
                    }
                },
                callback: function(item){
                    var hash = JSON.parse(localStorage.getItem("xhr"));
                    hash.revision = item.nimi;
                    hash.assembly_id = item.id;

                    o = {
                        revision: item.nimi,
                        assembly_id: item.id
                    };

                    localStorage.setItem("xhr", JSON.stringify(hash));
                    console.log(" # post-async@fn3 $ print O and xhr ");
                    console.log(JSON.stringify(o));
                    console.log(localStorage.getItem("xhr"));

                    /* global ajaxPost */
                    ajaxPost({
                        url: "/development/mantislive/plugin.php?page=Serials/json/format.php",
                        d: { "id" : o.assembly_id },
                        callback: addformat
                    });
                    setTimeout(function(){
                        suc(o);
                    },10);
					$('#scan_result').focus();
					document.getElementById('typeahead-field4').style.color="Black";
					$('#field7').val( o.assembly_id );
                    
                }
            });
        });
    };
    // A().then(B).then(C).then(D);
    var exec = function(){
        fn1()
        .then(function(v){
                return fn2(v);
            })
        .then(function(v){
                return fn3(v);
                // $("#field3").focus();
            })
        ;
    };
	exec();
	
    $("#field3").on({
		keyup:function(e){
        e.preventDefault();
        var v = $(this).val();
        console.log(" ?oldh " + oldh.assembly + " ?current " + v);
        // define 'o' prior to call rinse(o.a) here;
        
        if ( t_cond(e.which) ){
            // $("#typeahead-field3 > div > .typeahead-result").remove();
            var o ={
                // cond: oldh.assembly !== v && v && $("#field1").val()===oldh.customer,
                cond: oldh.assembly !== v && v,
                id: $(this).attr("id"),
                a: [4,5,6,7,8]
            };
            keyupFn(o);
        }
		}
    });

    $("#field2").on('keyup', function(e){
        e.preventDefault();
        // e.stopPropagation();
        var v = $(this).val();

        // if ( (47 < e.which && e.which < 91) || ( 95 < e.which && e.which < 106) ){
        if ( t_cond(e.which) ){
            var o ={
                // cond: oldh.customer !== v && oldh.customer_name && v && $("#field2").val(),
                cond: oldh.customer !== v && v,
                id: $(this).attr("id"),
                a: [3,4,5,6,7,8]
            };
            keyupFn(o);
        }
    });
	
	$("#field1").on({
		keyup: function(e){
			e.preventDefault();
			// e.stopPropagation();
			var v = $(this).val();
			if ( t_cond(e.which) ){
				var o ={
					// cond: oldh.customer !== v && oldh.customer_name && v && $("#field2").val(),
					cond: oldh.customer !== v && v,
					id: $(this).attr("id"),
					a: [2,3,4,5,6,7,8]
				};
				keyupFn(o);
			}
		}
    });

	
    var keyupFn = (function(){
        return function(_){
        if (_.cond) {
            var s = "#typeahead-" + _.id + " > div";
            // console.log($(s).find('.typeahead-result').length);
            if (!$(s).find('.typeahead-result').length)
            {
                // $(s).find('.typeahead-result').not(":eq(n)").remove();
                console.log(" # INIT " + _.id);
                switch (_.id) {
					case 'field2':
						rinse(_.a);
						/* document.getElementById('field2').style.color="Red";
                        $("#typeahead-field2 > div > .typeahead-result").remove(); */
                        oldh = {};
                        exec();
                        break;	

                    case 'field3':
						rinse(_.a);
						/* document.getElementById('field3').style.color="Red"; */
                        fn2(oldh)
                        .then(function(v){
                            return fn3(v);
                        });
                        break;
                    case 'field4':
						rinse(_.a);
						/* document.getElementById('field4').style.color="Red"; */
                        fn3(oldh);
                        break;

                    default:
                        // code
                }
            }
        }
    };
    })();
 	
    var rinse = (function(){
        return function(a){
            for (var i of a) {
				if (i < 5) {
					$("#field"+i).val('');
					$("#typeahead-field"+i).css('color','Red');
					$("#field"+i).siblings().remove();
					$("#typeahead-field"+ i +" > div > .typeahead-result").remove();
				}
				else {
					$("#field"+i).val('');
				}
                
            }
        };
    })();
	
	var addformat = function(data){
        $.map(data, function(obj) {
           /*  dnm_data.format = obj.format;
            dnm_data.format_id = obj.format_id;
            dnm_data.format_example = obj.format_example; */
            $("#field5").val(obj.nimi);
			$("#field6").val(obj.sample);
            // console.log(" # check if old is accessible inside ajax of fn3 " + old);
        });
    };
	
	var t_cond = (function(){
        return function(x){
        return range(x,47,91) || range(x,95,106);
        };
    })();

    var range = (function(){
        return function(x,min,max){
        return (min < x && x < max);
        };
    })();

    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();
	
	$("#search").on({
		click: function(){
		var postdata ={
			sales_order: $("#field1").val(),
			scan_input: $("#scan_result").val(),
			customer_id: $("#field8").val(),
			assembly_id: $("#field7").val(),
			assembly_number: $("#field3").val(),
		};
		$.ajax({
			type:'POST',
			url: '/development/mantislive/plugin.php?page=Serials/search.php',
			data: postdata,
			//contentType: "application/json",
			// dataType: 'json'
		}).done(function(data){
			$("#search-wrapper").empty().append( data + "<br/>")
                             .addClass("bg-success")
                             .css({  "max-height":"300px", "overflow-y" : "auto" })
							 .animate({"scrollTop": $("#search-wrapper")[0].scrollHeight}, "slow");
		});
		$.ajax({
			type:'POST',
			url: '/development/mantislive/plugin.php?page=Serials/search-list.php',
			data: postdata,
			//contentType: "application/json",
			// dataType: 'json'
		}).done(function(data){
			$("#log-wrapper").empty().append( data + "<br/>")
                             .addClass("bg-success")
                             .css({  "max-height":"300px", "overflow-y" : "auto" })
							 .animate({"scrollTop": $("#log-wrapper")[0].scrollHeight}, "slow");
		});
		}	
	});
    $("#scan_result").on({
        focus: function(){
            $(this).css("background-color", "lightblue");
        },
        blur: function(){
            $(this).css("background-color", "lightgrey");
        },
        keyup: function(e){
            e.preventDefault();
			if( $("#field1").val() == "" )
            {
                document.getElementById('typeahead-field1').style.color="Red";
            }
            else
            {
				document.getElementById('typeahead-field1').style.color="Black";
				document.getElementById('field1').disabled=true;
				document.getElementById('field2').disabled=true;
				document.getElementById('field3').disabled=true;
				document.getElementById('field4').disabled=true;
            }
			
            switch (e.which) {
                case 13:
                    var postdata ={
						new_scan: $(this).val(),
						customer_id: $("#field8").val(),
						assembly_id: $("#field7").val(),
						sales_order: $("#field1").val(),
						format: $("#field5").val(),
						format_example: $("#field6").val(),
						list_count: $("#field9").val(),
						revision: $("#field4").val(),
					};
                    $.ajax({
                        type:'POST',
                        url: '/development/mantislive/plugin.php?page=Serials/scan_proc.php',
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
						var newcount = Number($("#field9").val()) + Number(1);			
						$("#field9").val(newcount);
						document.getElementById('scan_result').select();
                        $("#virhe").empty().append("<b>Last Scanned: </b>" + data )
                        $("#log-wrapper")  .append("<div class='col-md-3'>" + data + "</div>")
                                            .addClass("bg-success")
                                            .css({  "max-height":"300px",
                                                    "overflow-y" : "auto" })
                        .animate({"scrollTop": $("#log-wrapper")[0].scrollHeight}, "slow");
                        }
                    }).fail(function(jqXHR,textStatus, errorThrown){
                        $("#virhe") .removeClass("alert-success")
                                    .addClass("alert-danger")
                                    .empty().append('!ERROR: ' + textStatus + ", " + errorThrown);
                        console.log('ERROR', textStatus, errorThrown);
                    });
                    break;
            }
        }
    });	
	$("#reset").on('click', function() {
		location.reload();
    });
	$('#field1').focus();
})();
