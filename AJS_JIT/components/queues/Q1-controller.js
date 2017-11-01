/*       
*  PB_JIT -- Q1-controller.js
*                                                       
* Author:  @pablo                           
*                                                       
* Purpose:  controller for Q1 page   
*                                                       
* Version:  1.0.0                                             
*            
*/ 

var Q1Ctrl = function($scope,$timeout,$state,s_Main,d_orders){
	
	 

	order_data = (d_orders.d_orders)[2016110401];
	
 	// map 
	$scope.map_mode_v = true;
	$scope.map_mode_r = 'Map';
  
  	// leaflet map
	single_customer_map('osm_q1',order_data,s_Main);
 	
    $scope.centerx = order_data.user_data.coords.split(',')[0];
	$scope.centery = order_data.user_data.coords.split(',')[1];
	$scope.zoom = 15;
	$scope.type = 'MapTypeId.SATELLITE';
 
	// map mode
	$scope.map_mode = function(mode){

		if(mode=='map'){
			$scope.map_mode_v = true;
		} else {
			$scope.map_mode_v = false;
		}
		 
	}

  
	function show_data(order_data_array){
		
		// set
		$scope.order_data = order_data; 
		// times
		$scope.preferred_delivery_time = timestamp_to_hm(order_data.preferred_delivery_time);
		$scope.stimated_delivery_time = 0000; //++++ timestamp_to_hm(order_data.altlp.stimated_delivery_time);
		$scope.delay_delivery_time = 1111; ///+++ timestamp_to_hm(order_data.altlp.stimated_delivery_time-order_data.delivery.preferred_delivery_time-3600);
		// items
		$scope.items = order_data.items;

		console.log(order_data.items[0]);
		
	}
  

     // init
    $timeout(function(){
    	show_data(22);
	});
   
 
};
Q1Ctrl.$inject =['$scope','$timeout','$state','s_Main','d_orders'];

angular
    .module('PB_jit')
    .controller('Q1Ctrl', Q1Ctrl)