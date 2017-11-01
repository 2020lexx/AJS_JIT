/**
 *
 *  PB_JIT
 *
 */

 

/* +++ Q2 +++ */

/* +++ Q3 +++ */



/* +++ All Q +++ */


 
/* Test Page */
var TestPageCtrl = function($scope,$timeout,fetchAsync,d_Q,d_orders){

	$scope.fnc_async = "QueueStatus";
	$scope.data = { "var1":0,"var2":1};

$scope.d_orders = d_orders;



$scope.d_Q = d_Q;

$scope.times = diff_time('22:00','20:10');

	$scope.ajax = function(){ 
					fetchAsync.fetch($scope)
                        .then(
                            fetched_data,
                            function( errorMessage ) {
                                console.log("AJAX Error:"+ errorMessage );
                            }
                        )};
	
 
	$scope.CheckoutDataBrief = function(){ 
					fetchAsync.fetch({'fnc_async':'CheckoutDataBrief','data':0})
                        .then(
                            fetched_data,
                            function( errorMessage ) {
                                console.log("AJAX Error:"+ errorMessage );
                            }
                        )};
 
  
/* 
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

L.marker([45.0007779,11.297037]).addTo(map)
    .bindPopup('A pretty CSS3 popup.<br> Easily customizable.')
    .openPopup();
 
	L.Routing.control({
	    waypoints: [
	        L.latLng(45.0714086,11.1338669),
	        L.latLng(45.0007779,11.297037)
	    ],
	    routeWhileDragging: true
	}).addTo(map);
*/
	/*$scope.varA = "Hi!";

	 
	$scope.q1 = function(){
		$scope.varA = Date();
		$scope.valueQ1a = "hi!!!!";
			$scope.valueQ1b = "hi!!!!";
	}*/
 	function fetched_data(data){


	//	console.log(">>"+JSON.stringify(data)+"<<<>>"+ data[0].fetch_response_data+"<<<");
		
	//	console.log('initial Q1:'+JSON.stringify(d_Q1.d_Q1));
		// process queue status data
		queue_status = data[0].fetch_response_data;

		console.log(queue_status);
 	 	d_orders.update('d_orders',queue_status);
 	 //	d_Q.update(queue_status); 
	 //	ds_Q.value = queue_status;
//angular.element('#Q1AW_dropdown_table').scope().Q1AW_dropdown_table();

 	  //	console.log('updated Q1:'+$scope.counterQueueQ1aw );
	 	}

}
TestPageCtrl.$inject =['$scope','$timeout','fetchAsync','d_Q','d_orders'];
/* PlugINs */
 
/* Top Bar */
var TopbarCtrl = function($scope,fetchAsync,varQ1){

	$scope.fnc_async = "QueueStatus";
	$scope.data = { "var1":0,"var2":1};

	$scope.show = function(){
		console.log("Var:"+varQ1.varq1);
	};
  
   $scope.update = function() {
    	 value = $scope.token;
    	 varQ1.update(value);
   		};
	
	$scope.ajax = function(){ 
					fetchAsync.fetch($scope)
                        .then(
                            fetched_data,
                            function( errorMessage ) {
                                console.log("AJAX Error:"+ errorMessage );
                            }
                        )};
	/*$scope.varA = "Hi!";

	 
	$scope.q1 = function(){
		$scope.varA = Date();
		$scope.valueQ1a = "hi!!!!";
			$scope.valueQ1b = "hi!!!!";
	}*/
	function fetched_data(data){
		console.log(">>"+JSON.stringify(data)+"<<<>>"+ data[0].fetch_response_data+"<<<");
	}
} 
// add control deps
TopbarCtrl.$inject =['$scope','fetchAsync','varQ1'];
/*
// common 
// timestamp to hours:minutes
function timestamp_to_hm(timestamp){
 	time_hm = new Date(timestamp*1000); 
 	time_m = (time_hm.getMinutes()<10)?'0'+time_hm.getMinutes():time_hm.getMinutes();
	return (time_hm.getHours()+':'+ time_m);
} 
 */
 

angular
    .module('PB_jit')
    .controller('TopbarCtrl',TopbarCtrl)
    
      .controller('TestPageCtrl', TestPageCtrl) 