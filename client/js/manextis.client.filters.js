angular.module('manextis.filter', ['angular-momentjs'])
.filter('boolText', boolText)
.filter('dateFormat', ['$moment', dateFormat]);

function boolText(){
	return function(boolValue){
		return (boolValue === "1") ? "TestMODE" : "ProdMODE";
	}
}
function dateFormat($moment){
    return function(time) {
      	return (time) ? $moment.unix(time).format('MM/DD/YYYY - hh:mm:ss a')  : time;
    };
}