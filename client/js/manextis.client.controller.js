(function () {
  'use strict';
/*  COMMENT OUT TEST CODE 
var URL_ACQUIRE_DATE = 'model/manextis_test_acquire_date.php';
var URL_SO_WO = 'model/manextis_test_so_wo.php';
var URL_JSON_SAMPLE = 'sample_data/manextis_sample_json.json';
var URL_TRUNCATE = 'truncate.php';
 */
// [1,2,3].map(n => console.log(n + 1));
var app = angular
    .module('myApp', ['manextis.filter', 'manextis.directive', 'xt.directive', 'angular-momentjs'])
    .controller('myController', ['$scope', '$http', '$location', '$interval', '$timeout', myCtrlFn]);

function myCtrlFn ($scope, $http, $location, $interval, $timeout) {
    $scope.name = "Ellen";
    $http.defaults.headers.post['dataType'] = 'json';
    $http.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
    var relUrl = $location.absUrl();
    relUrl = relUrl.substring(0, relUrl.lastIndexOf('/'));
    $scope.dirURL = relUrl;
    console.log("my current#Url: ", window.location.href);

    $scope.theInvisible = true;
/*  COMMENT OUT TEST CODE
    $scope.sampleToggle = function() {
        $scope.theInvisible = !$scope.theInvisible;
    };
 */
    $scope.init = $interval( function(){
        $scope.Time = Math.round(new Date().getTime()/1000.0);
        $scope.formattedTime = $scope.Time;
    }, 1000);
/*  COMMENT OUT TEST CODE
    $scope.truncate = function(){
        $http.get( $scope.dirURL + "/" + URL_TRUNCATE,
        {
            params: {truncate : 1}
        })
        .then(function (res) {
            console.clear();
            $scope.truncated = res.data;
            $timeout(function(){
                $scope.truncated = '';
            }, 1300);
        });
    };
 */
    // $scope.heroes = '[{"name":"Ninja Turtle","attribute":"agile tactic","present":"chapter 44"},{"name":"Wolfverine","attribute":"claws attack","present":"chapter 5"},{"name":"Jean","attribute":"psychiatrist","present":"chapter 2"}]';
/* 
    $scope.$watch('field10_model',function(newVal, oldVal){
        console.log(newVal, oldVal);
    });
    $scope.$watch('entry.date_received',function(newVal){
        console.log(newVal);
    }); */
    /* ---------------------------------------------------------
    Sample dataDump
    ----------------------------------------------------------*/
/*     $http.get(relUrl + "/" + URL_ACQUIRE_DATE)
      .then(function (response) {
        $scope.custs = response.data;
        var o = {};
        response.data.map(function(d,idx){
            o[idx] = {
                name: d.CUST_NAME,
                status: d.STATUS,
                timestamp: ((new Date(d.ACCT_DATE)).getTime() > 0) ? d.ACCT_DATE : (Date.parse(d.ACCT_DATE)/1000).toString()
            };
        });
        $scope.customers = o;
      });
    $http.get(relUrl + "/" + URL_SO_WO)
      .then(function (response) {
        $scope.custs = response.data;
        var o = {};
        response.data.map(function(d,idx){
            o[idx] = {
                key: d.UNIQ_KEY,
                wo: d.WO_NO,
                so: d.SO_NO,
                due_date: d.DUE_DATE,
                assembly: d.ASSY_NO,
                revision: d.REVISION,
                qty: d.QTY,
                customer_po: d.CUST_PO_NO,
                customer_name: d.CUST_NAME,
                timestamp: Math.floor(Date.now() / 1000)
            };
        });
        $scope.orders = o;
      });
    $http.get(relUrl + "/" + URL_JSON_SAMPLE)
     .then(function (response) {
        $scope.mutants = JSON.stringify(response.data);
        $scope.marvels = response.data;
    });
 */	
};

}());

