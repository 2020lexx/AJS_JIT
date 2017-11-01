/*       
*  PB_JIT -- single_task-controller.js
*                                                       
* Author:  @pablo                           
*                                                       
* Purpose:  controller for single task full view   
*                                                       
* Version:  1.0.0                                             
*            
*/ 

// single task modal window
var singleTaskCtrl = function($scope,ModalService,$timeout,Q,close,d_orders,s_Main){
	

	console.log('stask:'+JSON.stringify(Q));
	// get array
	var order_ar = d_orders.d_orders[Q.task_id] 
/*	angular.forEach(d_orders.d_orders ,function(value){
 		if(value.id==task_id) { this.push(value); }
	
	},order_ar);
 */ 
 	$scope.order_data = order_ar;
  
	// times
 //-	$scope.stimated_delivery_time = timestamp_to_hm(order_ar[0].altlp.stimated_delivery_time);
 //-	$scope.delay_delivery_time = timestamp_to_hm(order_ar[0].altlp.stimated_delivery_time-order_ar[0].delivery.preferred_delivery_time-3600);
	// items
	$scope.table_invoice = order_ar.items;

  	// show id on view
	$scope.task_id = Q.task_id;

	// show general or delivery graph
	$scope.on_delivery = false;

	// map
	 map_coord_x = order_ar.realtime_coords.split(',')[0];
	 map_coord_y = order_ar.realtime_coords.split(',')[1];

 
 	 // leaflet map
 	$timeout(function view_map(){ 
 	  	// init map
 	  	single_customer_map('osm_stask',order_ar,s_Main);
 	  	
 	  } ,100); 
	   

	$scope.coords = order_ar.realtime_coords;
	$scope.map_mode_r = 'Map';
	$scope.map_mode_v = true;
 	$scope.centerx = map_coord_x;
	$scope.centery = map_coord_y;
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

// show  map
 $scope.show_map= function (coords) {
 
		ModalService.showModal({ 
            templateUrl: 'components/full_views/single_user_map.html',
             inputs: {
        		coords: coords
      		},
            controller: singleUserMapCtrl
        }).then(function(modal) {
     			 modal.element.modal();
     			 modal.close.then(function(result) {
		          //++  console.log(result);
		      }); 
    		});
    };

	$scope.close = function () {

       close();
       // remove class from body
       jQuery('body').removeClass('modal-open').removeAttr('style');

       // remove grey frame fullscreen
       jQuery('.modal-backdrop').remove(); 
    };

    $scope.cancel = function () {
       close(result, 200);
    };

    function set_perc_bar(){
       // svg graph % 
	    perc_completed = 90;
	    //s et width
	    perc_completed_px = (perc_completed * 600)/100;
	    
		var svgNS = "http://www.w3.org/2000/svg";  
		// create <g
		var myG = document.createElementNS(svgNS,"g");
			myG.setAttributeNS(null,"class","bar");   
		// create <rect
	    var myRectangle = document.createElementNS(svgNS,"rect"); 
	        myRectangle.setAttributeNS(null,"x",50);
	        myRectangle.setAttributeNS(null,"y",101);
	        myRectangle.setAttributeNS(null,"fill","#1ab394");
	 		myRectangle.setAttributeNS(null,"width",perc_completed_px);
	        myRectangle.setAttributeNS(null,"height",30); 
	    // create <line
	     var myLine = document.createElementNS(svgNS,"line"); 
	        myLine.setAttributeNS(null,"x1",49+perc_completed_px);
	      	myLine.setAttributeNS(null,"x2",49+perc_completed_px);
  			myLine.setAttributeNS(null,"y1",101);
	        myLine.setAttributeNS(null,"y2",20);
	     	myLine.setAttributeNS(null,"stroke","#1ab394"); 
	    // create <text
	    var myText = document.createElementNS(svgNS,"text"); 
	    	myText.setAttributeNS(null,"class","block-txt"); 
	     	myText.setAttributeNS(null,"x",perc_completed_px);
	        myText.setAttributeNS(null,"y",112);
			myText.setAttributeNS(null,"text-anchor","left"); 
	     	myText.setAttributeNS(null,"alignment-baseline","central"); 
	   
		    jQuery(myText).text(perc_completed+' %'); 
	    	// append
		  	jQuery(myG).prepend(myText);  
		    // append
	        jQuery(myG).prepend(myLine);
			// append
	        jQuery(myG).prepend(myRectangle);
	
	    	// insert code
	    	jQuery('#single_task_svg_path').append(myG);
    }	
     function set_del_bar(){
       // svg graph % 
	    perc_completed = 10;
	    //s et width
	    perc_completed_px = (perc_completed * 600)/100;
	    
		var svgNS = "http://www.w3.org/2000/svg";  
		// create <g
		var myG = document.createElementNS(svgNS,"g");
			myG.setAttributeNS(null,"class","bar");   
		// create <rect
	    var myRectangle = document.createElementNS(svgNS,"rect"); 
	        myRectangle.setAttributeNS(null,"x",50);
	        myRectangle.setAttributeNS(null,"y",101);
	        myRectangle.setAttributeNS(null,"fill","#1ab394");
	 		myRectangle.setAttributeNS(null,"width",perc_completed_px);
	        myRectangle.setAttributeNS(null,"height",30); 
	    // create <line
	     var myLine = document.createElementNS(svgNS,"line"); 
	        myLine.setAttributeNS(null,"x1",49+perc_completed_px);
	      	myLine.setAttributeNS(null,"x2",49+perc_completed_px);
  			myLine.setAttributeNS(null,"y1",101);
	        myLine.setAttributeNS(null,"y2",20);
	     	myLine.setAttributeNS(null,"stroke","#1ab394"); 
	    // create <text
	    var myText = document.createElementNS(svgNS,"text"); 
	    	myText.setAttributeNS(null,"class","block-txt"); 
	     	myText.setAttributeNS(null,"x",perc_completed_px);
	        myText.setAttributeNS(null,"y",112);
			myText.setAttributeNS(null,"text-anchor","left"); 
	     	myText.setAttributeNS(null,"alignment-baseline","central"); 
	   
		    jQuery(myText).text(perc_completed+' %'); 
	    	// append
		  	jQuery(myG).prepend(myText);  
		    // append
	        jQuery(myG).prepend(myLine);
			// append
	        jQuery(myG).prepend(myRectangle);
			
			jQuery('#delivery_time').text('Delivery at 21:40 - Time left: 00:20 ');
	    	// insert code
	    	jQuery('#single_task_svg_delivery').append(myG);
    }	  
  // init
    $timeout(function(){ 
    	set_perc_bar();
    	//set_del_bar();
	});

}
// add control deps
singleTaskCtrl.$inject =['$scope','ModalService','$timeout','Q','close','d_orders','s_Main'];

angular
    .module('PB_jit')
   .controller('singleTaskCtrl',singleTaskCtrl)
 