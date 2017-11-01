/*       
*  PB_JIT -- Brief_Q-controller.js
*                                                       
* Author:  @pablo                           
*                                                       
* Purpose:  controller for Brief Q page   
*                                                       
* Version:  1.0.0                                             
*            
*/ 

var BriefQCtrl = function($scope,$timeout,d_Q,d_Q_brief,d_orders){
   	
 
  $scope.orders = d_orders.d_orders;
   //   $scope.briefQueue = d_Q_brief.d_Q_brief;

  
  // convert into single array

  // convert queue name to isotpe class, this is using on filtering
  $scope.convert_to_class = function(queue_name){
        // call main parameters
        result = set_parameters(queue_name);
        return result[0];
  }

  // get complete %
  $scope.complete_percent = function(queue_name){
        // call main parameters
        result = set_parameters(queue_name);
        return result[1];
  }
// get status txt
$scope.complete_txt = function(queue_name){
        // call main parameters
        result = set_parameters(queue_name);
        return result[2];
  }
// get status img
$scope.complete_img = function(queue_name){
        // call main parameters
        result = set_parameters(queue_name);
        return result[3];
  }
  // set parameters to use on visual progress of queue
  function set_parameters(queue_name){

          switch(queue_name){
              case 'Q1AW':
                 c_class = 'Q1 Q1A Q1AW';
                 c_perc = 10;
                 c_status_txt = 'Incoming';
                 c_status_img = 'img/phone.png';
              break; 
              case 'Q1A':
                 c_class = 'Q1 Q1A';
                 c_perc = 20;
                 c_status_txt = 'Getting Data';
                 c_status_img = 'img/pc.jpg';
              break;   
              case 'Q2AW':
                 c_class = 'Q2 Q2A Q2AW';
                 c_perc = 30;
                 c_status_txt = 'wt.Assembly';
                 c_status_img = 'img/make.jpg';
              break;   
              case 'Q2A':
                 c_class = 'Q2 Q2A';
                 c_perc = 40;
                 c_status_txt = 'Assembly';
                 c_status_img = 'img/make.jpg';
              break;
              case 'Q2BW':
                 c_class = 'Q2 Q2B Q2BW';
                 c_perc = 50;
                 c_status_txt = 'wt.Cook';
                 c_status_img = 'img/cook.jpg';
              break;   
              case 'Q2B':
                 c_class = 'Q2 Q2B';
                 c_perc = 60;
                 c_status_txt = 'Cook';
                 c_status_img = 'img/cook.jpg';
              break;   
              case 'Q2CW':
                 c_class = 'Q2 Q2C Q2CW';
                 c_perc = 70;
                 c_status_txt = 'wt.P.T.Delivery';
                 c_status_img = 'img/ptdelivery.jpg';
              break;   
              case 'Q2C':
                 c_class = 'Q2 Q2C';
                 c_perc = 80;
                 c_status_txt = 'P.T.Delivery';
                 c_status_img = 'img/ptdelivery.jpg';
              break;   
              case 'Q3AW':
                 c_class = 'Q3 Q3A Q3AW';
                 c_perc = 90;
                 c_status_txt = 'StBy';
                 c_status_img = 'img/delivery.jpg';
              break;   
              case 'Q3A':
                 c_class = 'Q3 Q3A';
                 c_perc = 95;
                 c_status_txt = 'Delivery';
                 c_status_img = 'img/delivery.jpg';
              break;        
          }

          return [c_class,c_perc,c_status_txt,c_status_img];
  }
	$scope.show = function(){
		//console.log("Q2 Var:"+varQ1.varq2);
	};
    
     // init
    $timeout(function(){
	 	});

 

};
BriefQCtrl.$inject =['$scope','$timeout','d_Q','d_Q_brief','d_orders'];


angular
    .module('PB_jit')
    .controller('BriefQCtrl', BriefQCtrl)
  