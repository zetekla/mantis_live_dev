(function() {
  var app = angular.module('SerialScan', []);
  app.controller('TemplateController', function(){
	this.fields = values
	//this.assembly = json.assembly;
	//this.customer = json.customer;
	//this.format = json.format;	 
  });
  
var values =[
	{
		display:'Work Order',
		model:'work_order',
		name:'work_order',
	},{
		display:'Customer',
		model:'customer',
		name:'customer',
	},{
		display:'Assembly',
		model:'assembly',
		name:'assembly',
	},{
		display:'Revision'
		model:'revision',
		name:'revision',		
	}];  
  
})();