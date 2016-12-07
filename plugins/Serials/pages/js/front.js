
var dnm_data = {
  user: $('.login-info-left :first-child').text(),
  time: $('.login-info-middle :first-child').text(),
  quantity_count: 0
};

$(document).ready(function() {
//document.getElementById('key').disabled=true;
	$("#field0").focus();
	key.disabled=true;
  $(document).on('keyup',function(f){
    f.preventDefault;
    if (f.which == 120) // F9
    {
      console.log(f.which);
      print_dialog;
    }
  });

  $("#html-painike").on({
    click: print_html
  });

 	$("#reset").on('click', function(e) {
 	    e.preventDefault();
 	    localStorage.clear();
		  location.reload(true);
  });

	$("#search").on({
		click: function(e){
		  e.preventDefault();
		  search_process();
		}
	});

	$("#cofc").on({
		click: function(e){
		  e.preventDefault();
		  cofc();
		}
	});
	
	$("#format_config").on({
		click: function(e){
		  e.preventDefault();
		  format_config($("#key").val());
		}
	});	
	
	$("#format_update").on({
		click: function(e){
		  e.preventDefault();
		  format_update();
		}
	});	

  $("#field1,#field2,#field3,#field7")
  .on('keyup', function(e){
    // e.preventDefault();
    if (e.which == 118) // F7
    {
      console.log($(this).attr("id"), " F7 key pressed");
      search_process();
    }
  });

  $("#scan_result").on({
    mouseenter: function(){
        $(this).css("background-color", "lightgray");
    },
    mouseleave: function(){
        $(this).css("background-color", "lightblue");
    },
    click: function(){
        $(this).css("background-color", "yellow");
		document.getElementById('scan_result').select();
    },
    keyup: function(e){
      e.preventDefault();
      switch (e.which) {
        case 118:
          search_process();
        break;
        case 13:
        if( $("#field7").val() == "" ){
			document.getElementById('typeahead-field7').style.color= "red";
		}else{
          document.getElementById('typeahead-field7').style.color="black";
  				document.getElementById('field1').disabled=true;
  				document.getElementById('field2').disabled=true;
  				document.getElementById('field3').disabled=true;
  				document.getElementById('field7').disabled=true;
				document.getElementById('field0').disabled=true;
				document.getElementById('retrieval').disabled=true;
        }
		if(document.getElementById("retrieval").checked){
			document.getElementById('session_id').disabled=true;
			search_process();
		}else{
			scan_process($(this).val());
		}      
        break;
      }
    }
  });
  
  $("button[type='button']").click(function() {
	 switch(this.id){
		 case 'numeric' :
		  var response = prompt("How many Numeric Values", "");
		  if (!(/[a-z]/i.test(response))) {
			  var e = document.getElementById("format");
			  var scope = angular.element(e).scope();
			  scope.format = $('#format').val() + '[0-9]{' + response + '}';
			  scope.$digest();
			  $('#format').focus();
			}
		 break;
		 case 'alpha-numeric' :
		  var response = prompt("How many Numeric Values", "");
		  if (!(/[a-z]/i.test(response))) {
			  var e = document.getElementById("format");
			  var scope = angular.element(e).scope();
			  scope.format = $('#format').val() + '[0-9A-Z]{' + response + '}';
			  scope.$digest();
			  $('#format').focus();
			}
		 break;
	 }
  });
});