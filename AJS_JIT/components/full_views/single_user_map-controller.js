
// map fullscreen modal window

var singleUserMapCtrl = function($scope,$timeout,coords,close,s_Main,d_orders){
 
 	order_data = (d_orders.d_orders)[2016110401];
	
 	// map 
	$scope.map_mode_v = true;
	$scope.map_mode_r = 'Map';
   
	// leaflet map
 	$timeout(function view_map(){ 
 	  	// init map
 	  	single_customer_map('osm_big',order_data,s_Main);
 	  	
 	  } ,100); 
	   
	 // map
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

	$scope.close = function () {

       close();
       // remove class from body
       jQuery('body').removeClass('modal-open').removeAttr('style');

       // remove grey frame fullscreen
       jQuery('.modal-backdrop').remove(); 
    };

}
// add control deps
singleUserMapCtrl .$inject =['$scope','$timeout','coords','close','s_Main','d_orders'];


angular
    .module('PB_jit')
    .controller('singleUserMapCtrl ',singleUserMapCtrl 
    	) 
