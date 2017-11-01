
/*       
*  PB_JIT -- C_main-controller.js
*                                                       
* Author:  @pablo                           
*                                                       
* Purpose:  common main controller  
*                                                       
* Version:  1.0.0                                             
*            
*/ 
var  MainCtrl = function($scope,$timeout,$interval,$state,fetchAsync,fetchSync,s_Main,toasty,ModalService,d_orders,d_Q,d_Delivery,d_Q_brief) {

  	 
	
	// update tables of queues view
	$scope.update_tables = function(){

	 	$scope.table_queueQ1aw = d_Q.d_Q.Q1AW; 
		
		$scope.table_queueQ2aw = d_Q.d_Q.Q2AW;
		$scope.table_queueQ2bw = d_Q.d_Q.Q2BW;
		$scope.table_queueQ2cw = d_Q.d_Q.Q2CW;
		$scope.table_queueQ2a = d_Q.d_Q.Q2A;
		$scope.table_queueQ2b = d_Q.d_Q.Q2B;
		$scope.table_queueQ2c = d_Q.d_Q.Q2C;

	  	$scope.table_queueQ3aw = d_Q.d_Q.Q3AW;
	  	$scope.table_queueQ3a = d_Q.d_Q.Q3A;

	    $scope.briefQueue = d_Q_brief.d_Q_brief;


	}
	// Q1AQ drop down table on topbar
	$scope.Q1AW_dropdown_table = function(){
		$scope.table_queueQ1aw = d_Q.d_Q.Q1AW;
	}
	// page access
	$scope.q2 = function(){
		$state.go('index.Q2');
	}
	$scope.q3 = function(){
			$state.go('index.Q3');
 	}
 	$scope.allq = function(){
			$state.go('index.All_Q');
 	}

// auto set label colors on delay field
$scope.defineClassDelay = function(value){

/********************************************/
return;
	var data = value.indexOf('-');
 
 	 	if(data==-1){
			return ('label label-warning');
		} else {
			// green if time is negativo so before ttd
			return ('label label-green');
		}
	}
   
 // fetch sync
 function fetch_sync(data){ 

 		// get data from s_Main.sync_functions
		data = { 
	 			fnc_sync:'sync_data',
	 			data: s_Main.s_Main.sync_functions
	 		 	 };

 		// execute fetch
		fetchSync.fetch(data)
                .then(
                    fetched_sync_data,
                    function( errorMessage ) {
                        console.log("AJAX Sync Error:"+ errorMessage );
                    }
                )  
    }

 function fetched_sync_data(data){
		
		// get and process data
	 	data_fetched = data[0].fetch_response_data;
	 
		// check response
	 	switch(data[0].fetch_response_var){
	 		// sync_data
	 		case 'sync_data':
	 			// 
	 			angular.forEach(data_fetched,function(value){
	 				// check if modified
	 				if(value.modified!=0){
	 					console.log('update:'+value.var_name);
	 					// JSON to JS Object
	 					js_object = JSON.parse(value.data);
	 					// update var
	 					switch(value.var_name){
	 						// queue
	 						case 'd_Q':
	 							d_Q.update(js_object);
	 						break;
	 						// queue brief
	 						case 'd_Q_brief':
	 							d_Q_brief.update(js_object);
	 						break;
	 						// delivery map
	 						case 'd_Delivery':
	 							d_Delivery.update(js_object);
	 						break;
	 						// orders data
	 						case 'd_orders_update':
	 							// the orders where modified
	 							// send in async mode local mpdified table, response arc an array with modified orders
	 							data = {'fnc_async':'GetModifiedOrders','data':d_orders.d_orders_update};
	 							fetch_async(data);
	 						break;
	 					}
	 			 		// update modified time
						s_Main.update('sync_functions',{code:value.code,modified:value.modified});


					//++	console.log(JSON.stringify(s_Main.s_Main));
	 				}
	 			//	console.log(value);

	 			})
	 		break;
	 	}
		
	//	console.log(">>"+JSON.stringify(data)+"<<<>>"+ data[0].fetch_response_data+"<<<");
	
	}

// fetch Async
function fetch_async(data){ 

	// execute fetch
	fetchAsync.fetch(data)
            .then(
                fetched_async_data,
                function( errorMessage ) {
                    console.log("AJAX ASync Error:"+ errorMessage );
                }
            )  
}
function fetched_async_data(data){
		
		// get and process data
	 	data_fetched = data[0].fetch_response_data;
	 
		// check response
	 	switch(data[0].fetch_response_var){
	 		// d_orders
	 		case 'd_orders':
 	 			// update main var
	 			d_orders.update(data_fetched);
	 		break;
	 	}
		
	//	console.log(">>"+JSON.stringify(data)+"<<<>>"+ data[0].fetch_response_data+"<<<");
	
	}

// show single task
 $scope.show_single_task = function (Q) {
 
		ModalService.showModal({ 
            templateUrl: 'components/full_views/single_task.html',
             inputs: {
        		Q: Q
      		},
            controller: singleTaskCtrl
        }).then(function(modal) {
     			 modal.element.modal();
     			 modal.close.then(function(result) {
		          //++  console.log(result);
		      }); 
    		});
    };


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

// show delivery  map
 $scope.show_delivery_map= function (coords) {
 
		ModalService.showModal({ 
            templateUrl: 'components/full_views/delivery_map.html',
             inputs: {
        		coords: coords
      		},
            controller: deliveryMapCtrl
        }).then(function(modal) {
     			 modal.element.modal();
     			 modal.close.then(function(result) {
		          //++  console.log(result);
		      }); 
    		});
    };

    var count = 1;

     function count_timer(){
   // 	console.log(count);
    	count += 1;
//$scope.counterQueueQ1aw =count;
    }
    // Fetch Sync
    stopFetchSync = $interval(fetch_sync, 5000);

    // init
    $timeout(function(){ 
    	s_Main.update()
//    	$scope.update_counters();
    	$scope.Q1AW_dropdown_table();
	});
};
// add control deps
MainCtrl.$inject =['$scope','$timeout','$interval','$state','fetchAsync','fetchSync','s_Main','toasty','ModalService','d_orders','d_Q','d_Delivery','d_Q_brief'];
 

angular
    .module('PB_jit')
    .controller('MainCtrl', MainCtrl)
 