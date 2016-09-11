'use strict';
var URL_JSON_MANTIS_MANEX = "manextis_inc.php";

/*// Add to RegExp prototype http://aramk.com/blog/2012/01/16/preg_match_all-for-javascript/
RegExp.prototype.execAll = function(string) {
	var matches = [];
	var match = null;
	while ( (match = this.exec(string)) != null ) {
		var matchArray = [];
		for (var i in match) {
			if (parseInt(i) == i) {
				matchArray.push(match[i]);
			}
		}
		matches.push(matchArray);
	}
	return matches;
}*/

angular.module("manextis.directive",[])
.directive("resultFetch", ["$location", "$http", resultFetch ]);

function resultFetch($location, $http){
	return {
		link: function(scope, elem, attrs){
			scope.$watch('query',function(newVal, oldVal){
		        $location.search("query=" + newVal);
		        scope.currSearch = $location.search();
		        var data = scope.currSearch;
		        angular.extend(data, { Mocha: scope.mochaBox});
		        /*
		        // creator_id to be wired up.
		        var data = {
		            query: newVal,
		            creator_id: null
		        };
		        var config = {
		            params: { query : newVal},
		            headers : {'Accept' : 'application/json'}
		        };

		        $http.post( scope.dirURL + "/" + URL_JSON_MANTIS_MANEX, config)

		        // see short-hand below for the preferred GET method - searching the right way, user can SAVE their searched link, HENCE reusability is made possible: */
		        if (newVal.length >6){
		        	scope.xset = null;
	            	scope.tset = null;
	            	scope.shlog = null;
	            	scope.error = null;
	            	scope.stock = null;
	            	scope.no_query = true;
	            	scope.no_insertion = true;
					scope.assembly = null;
					scope.revision = null;
					scope.sales_order = null;
					scope.customer = null;
					scope.purchase_order = null;
					scope.quantity = null;
					scope.due_date = null;
					scope.unique_key = null;
					var hash = JSON.parse(localStorage.getItem("xhr"));
					//console.log(hash);
			        $http.get( URL_JSON_MANTIS_MANEX,
			        {
			            params: data
			        })
			        .then(function (res) {
			            scope.Mocha = {
			                test: res.data.Mocha
			            };
			            // console.log(res.data);
			            var o = {};
			            var ob = {};
			            var oc = {};
			            if (typeof res.data ==='string'){
				            // res.data = res.data.replace(/(<pre>|<\/pre>)/g, '').trim();
				            /*// Ratkaisu 01
				            var matches = [];
				            res.data.replace(/<pre>([\s\S]+?)<\/pre>/g, function(){
				            	//arguments[0] is the entire match
				            	matches.push(arguments[1]);
				            });
				            res.data = matches[0];*/

				            var re = /<pre\s*[^>]*>([\S\s]*?)<\/pre>/i;
							var match = re.exec(res.data);
							res.data = match[1];
				            res.data = JSON.parse(res.data);
			            }
			            console.log(res.data);
		            	var pipe = res.data.sync.pipe || null;
		            	var fullhouse = res.data.sync.fullhouse || null;
		            	var shell = res.data.sync.shell || null;

		            	console.log("Main findings: ");
		            	console.log(pipe.response);
			            var jobj = (typeof pipe.response ==='string') ? JSON.parse(pipe.response) : pipe.response;
			            try {
			            	if (!jobj) throw new Error("Undefined object, response: ");
			            	scope.no_result = Object.keys(jobj).length === 0 || jobj == null;


			            	if (scope.no_result) throw new Error("Unable to map the result object, response: ");
				            jobj.map(function(d,idx){
				                o[idx] = {
				                    key: d.UNIQ_KEY || '',
				                    wo: d.WO_NO || '',
				                    so: d.SO_NO || '',
				                    due_date: d.DUE_DATE || '',
				                    assembly: d.ASSY_NO || '',
				                    revision: d.REVISION || '',
				                    qty: d.QTY || '',
				                    customer_po: d.CUST_PO_NO || '',
				                    customer_name: d.CUST_NAME || '',
				                    timestamp: Math.floor(Date.now() / 1000)
				                };
				            });

				            if (fullhouse){
				            	//console.log('here fullHouse', fullhouse);
				            	if(!(fullhouse instanceof Array)) fullhouse = [fullhouse];
					            fullhouse.map(function(d,idx){
					                ob[idx] = {
					                    key: d.UNIQ_KEY || '',
					                    wo: d.WO_NO || '',
					                    so: d.SO_NO || '',
					                    due_date: d.DUE_DATE || '',
					                    assembly: d.ASSY_NO || '',
					                    revision: d.REVISION || '',
					                    qty: d.QTY || '',
					                    customer_po: d.CUST_PO_NO || '',
					                    customer_name: d.CUST_NAME || '',
					                    timestamp: Math.floor(Date.now() / 1000)
					                };
					                console.log('client.Fullhouse ', ob[0]);
									scope.assembly = ob[0].assembly;
									$('input[name="custom_field_1"]').val(ob[0].assembly);
									scope.revision = ob[0].revision;
									$('input[name="custom_field_2"]').val(ob[0].revision);
									scope.sales_order = ob[0].so;
									$('input[name="custom_field_3"]').val(ob[0].so);
									scope.customer = ob[0].customer_name;
									scope.purchase_order = ob[0].customer_po;
									scope.quantity = ob[0].qty;
									scope.due_date = convertTimestamp(ob[0].due_date);
									scope.unique_key = ob[0].key;																
					            });
				        	}
							
				        	if (shell){
				            	// console.log(shell);
				            	if(!(shell instanceof Array)) shell = [shell];
					            shell.map(function(d,idx){
					                oc[idx] = {
					                    bash: d.bash || '',
					                    timestamp: Math.floor(Date.now() / 1000)
					                };
					                // console.log('client.Shell ', oc);
					            });
				        	}
			            }
			            catch(e) {
			            	console.error(e.message, res.data);
			            }
			            finally {
			            	scope.xset = o;
			            	scope.tset = ob || null;
			            	scope.shlog = oc || null;
			            	scope.error = pipe.error || null;
			            	scope.stock = pipe.stock || null;
			            	scope.no_query = (!scope.stock);
			            	scope.no_insertion = (!scope.tset);
			            }
			        });
			    } else {
					scope.error = null;
					scope.assembly = null;
					scope.revision = null;
					scope.sales_order = null;
					scope.customer = null;
					scope.purchase_order = null;
					scope.quantity = null;
					scope.due_date = null;
					scope.unique_key = null;
					$("#msg").show();
				}
    		});
		},
		//templateUrl: 'manextis.client.view.resultFetch.html'
	};
}

angular
	.module('xt.directive', [])
	.directive('passingProfile', [passingProfile]);

function passingProfile(){
	return {
		restrict: 'E',
		scope: {
			marvel:'=',
			title:'=',
			id:'='
		},
		replace: true,
		templateUrl: 'templates/profile.html',
		link: function(scope, elem, attrs){
			console.log(arguments);
			elem.click(function(){
				alert('Your event hero is ' + scope.marvel.name);
			});
		},
		controller: function($scope){
			console.log($scope.marvel);
		}
	}
}
/*
restrict: 'E', apply to element which <passing-profile> is an element
restrict: 'A', apply to attribute which <div passing-profile></div> or <div data-passing-profile></div>, passing-profile is an attribute role here, same as data=marvel, data is an attribute.
if scope were to be:
scope: {
	myData:'=data'
},
controller:function($scope){
	console.log($scope.myData); // use myData instead
}
for the sake of consistency, use marvel=marvel everywhere then.
See next commit

use template with templateUrl:'templates/profile.html'

r36:
- pass scope.title data attribute from element pass-profile in view.html to
the directive
- use title in the profile template instead of ng-transclude
- replace to use normal div-wrapper in the template instead of <pass-profile> as a wrapper

compile: compile func is rather complicated

link: link func is not dependency inject
link: function(scope, elem, attrs){}
*/

function convertTimestamp(timestamp) {
  var d = new Date(timestamp * 1000),	// Convert the passed timestamp to milliseconds
		yyyy = d.getFullYear(),
		mm = ('0' + (d.getMonth() + 1)).slice(-2),	// Months are zero based. Add leading 0.
		dd = ('0' + d.getDate()).slice(-2),			// Add leading 0.
		hh = d.getHours(),
		h = hh,
		min = ('0' + d.getMinutes()).slice(-2),		// Add leading 0.
		ampm = 'AM',
		time;
			
	if (hh > 12) {
		h = hh - 12;
		ampm = 'PM';
	} else if (hh === 12) {
		h = 12;
		ampm = 'PM';
	} else if (hh == 0) {
		h = 12;
	}
	
	// ie: 2013-02-18, 8:35 AM	
	time = mm + '-' + dd + '-' + yyyy;
	$("#msg").hide();
	return time;
}
/*The main reason to change binding from @ to = is that
one way binding(@) only supports strings
whereas 2 way binding(=) supports from simple strings to complex objects. */