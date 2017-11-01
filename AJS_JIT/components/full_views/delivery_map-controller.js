
// dewlivery map fullscreen modal window

var deliveryMapCtrl = function($scope,$timeout,close,s_Main,d_Delivery){
 
  
	// leaflet map
 	$timeout(function view_map(){ 
    		// call leaflet map
   		delivery_map('osm_big_delivery',s_Main,d_Delivery,1);
    
	 } ,100);
	   
	 
  
	$scope.close = function () {

       close();
       // remove class from body
       jQuery('body').removeClass('modal-open').removeAttr('style');

       // remove grey frame fullscreen
       jQuery('.modal-backdrop').remove(); 
    };

}
// add control deps
deliveryMapCtrl.$inject =['$scope','$timeout','close','s_Main','d_Delivery'];


angular
    .module('PB_jit')
    .controller('deliveryMapCtrl',deliveryMapCtrl)
