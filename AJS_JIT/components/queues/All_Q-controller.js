/*       
*  PB_JIT -- All_Q-controller.js
*                                                       
* Author:  @pablo                           
*                                                       
* Purpose:  controller for All_Q page   
*                                                       
* Version:  1.0.0                                             
*            
*/ 

var All_QCtrl = function($scope,$timeout,d_Q,d_orders){


	$scope.orders = d_orders.d_orders;

// show Queues
function view_queue(){

	$scope.filterQ1=true;
	$scope.filterQ2=true;
	$scope.filterQ3=true;


	// update counters
 //	angular.element('body').scope().update_counters();
  
//	$scope.table_queueQ1aw = d_Q1.d_Q1;
/* 	$scope.table_queueQ1aw = d_Q.d_Q.Q1AW; 
	$scope.table_queueQ2aw = d_Q.d_Q.Q2AW;
	$scope.table_queueQ2bw = d_Q.d_Q.Q2BW;
	$scope.table_queueQ2cw = d_Q.d_Q.Q2CW;
	$scope.table_queueQ2a = d_Q.d_Q.Q2A;
	$scope.table_queueQ2b = d_Q.d_Q.Q2B;
	$scope.table_queueQ2c = d_Q.d_Q.Q2C;

  	$scope.table_queueQ3aw = d_Q.d_Q.Q3AW;
  	$scope.table_queueQ3a = d_Q.d_Q.Q3A;
*/
}
//    $scope.table_queueQ2 = (d_Q2.d_Q2.a.concat(d_Q2.d_Q2.b)).concat(d_Q2.d_Q2.c);
 
 	// init functions 
   $timeout(function(){
  		view_queue();
  }); 
 
 }
// add control deps
All_QCtrl.$inject =['$scope','$timeout','d_Q','d_orders'];

angular
    .module('PB_jit')
   .controller('All_QCtrl', All_QCtrl)
 