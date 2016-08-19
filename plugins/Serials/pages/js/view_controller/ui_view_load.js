(function(){
  var content = document.getElementById('ui_data');
  var data = JSON.parse(localStorage.getItem("tpl_data"));

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

  var template = Handlebars.compile(document.getElementById('ui-template').innerHTML);
  content.innerHTML += template(data);

	/*$('#assembly .typeahead').prop( "disabled", true );
	$('#revision .typeahead').prop( "disabled", true );
	document.getElementById('sales_order').addEventListener("change",function(){
    this.style.color = (document.getElementsByName('sales_order')[0].value == "" ) ? "red" : "black";
  });
	*/
	document.getElementById('typeahead-field1').style.color="Red";
	document.getElementById('typeahead-field2').style.color="Red";
	document.getElementById('typeahead-field3').style.color="Red";
})();