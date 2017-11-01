/*       
*  PB_JIT -- All_Q-directives.js
*                                                       
* Author:  @pablo                           
*                                                       
* Purpose:  directives of all_q and top_bar pages        
*                                                       
* Version:  1.0.0                                             
*            
*/ 

// counter queue Q1a + class
var counterQueueQ1aw = function(d_Q,toasty){
    return{
        restrict: 'E',
        replace: true,
        template: '<span class="table-counter Q1AW_count label label-green" >{{counterQueueQ1aw}}</span>', 
        link: function(scope, elem, attrs){
            // check id d_Q is changed
            scope.$watch( function(){ return d_Q.d_Q },
                     // execute on value change

                     function changeValue( newValue, oldValue ) {
                        // set 0 if there is not value
                        Q1AW_length = (d_Q.d_Q.Q1AW)?d_Q.d_Q.Q1AW.length:0;
                        // check and update warning on Q1AW queue
                         if(Q1AW_length!=scope.counterQueueQ1aw){
                            toasty.info({
                                title: 'Information',
                                msg: 'New Request is Arrived'
                            });
                          }
                        // update counter
                        scope.counterQueueQ1aw = Q1AW_length;
                        // set counter css
                        switch (true) {
                            case (Q1AW_length == 0):
                                // hide
                                jQuery('.incoming-spinner').css('opacity','0');
                                elem.addClass('label-green');
                                elem.removeClass('label-danger');
                                elem.removeClass('label-warning');
                    
                            break;
                            case (Q1AW_length <= 3):
                                // show spin + label
                                jQuery('.incoming-spinner').css('opacity','1');
                                elem.addClass('label-warning');
                                elem.removeClass('label-danger');
                                elem.removeClass('label-green');
                 
                            break;
                            case (Q1AW_length <= 4):
                                // alert
                                jQuery('.incoming-spinner').css('opacity','1');
                                elem.addClass('label-danger');
                                elem.removeClass('label-warning');
                                elem.removeClass('label-green');
                                toasty.warning({
                                    title: 'Warning',
                                    msg: 'There Are '+Q1AW_length+' requests on hold'
                                });
                            break;
                            case (Q1AW_length > 4):
                                // sound
                            break;
                        }
                        if(Q1AW_length > 1){
                             // set warn gif

                        } else {
                            if(Q1AW_length > 3){
                            // set warning label

                            }
                        }

                     }
             );


        } 
    }
} 
counterQueueQ1aw.$inject =['d_Q','toasty'];

// counter queue Q1b + class
var counterQueueQ1a = function(d_Q){
    return{
        restrict: 'E',
        replace: true,
        template: '<span class="table-counter Q1A_count " >{{counterQueueQ1a}}</span>',
        link: function(scope, elem, attrs){
            // check id d_Q is changed
            scope.$watch( function(){ return d_Q.d_Q },
                 function changeValue( newValue, oldValue ) {
                     counterQueueQ1a = (d_Q.d_Q.Q1A)?d_Q.d_Q.Q1A.length:0;
                });
            }
      
      }
}
counterQueueQ1a.$inject =['d_Q'];

// counter queue Q2a + class
counterQueueQ2aw = function ($timeout,d_Q){
    return{
        restrict: 'E',
        replace: true,
        template: '<span class="table-counter Q2AW_count label label-green "  >{{counterQueueQ2aw}}</span>',
         link: function(scope, elem, attrs){
             // check id d_Q is changed
            scope.$watch( function(){ return d_Q.d_Q },
                 function changeValue( newValue, oldValue ) {

                  console.log('old:'+oldValue+'-new:'+newValue);
                   Q2AW_length = (d_Q.d_Q.Q2AW)?d_Q.d_Q.Q2AW.length:0;
                   if(Q2AW_length!=scope.counterQueueQ2aw){ 
                       // show bg alert on counter
                       elem.parent().addClass('queue_counter_alert');
                       $timeout(function(){
                         elem.parent().removeClass('queue_counter_alert');
                       },1500); 
                    }
                    scope.counterQueueQ2aw =  Q2AW_length;
                 });
            }
      
      }
}
counterQueueQ2aw.$inject =['$timeout','d_Q'];

// counter queue Q2b + class
counterQueueQ2a = function ($timeout,d_Q){
    return{
        restrict: 'E',
        replace: true,
        template: '<span class="table-counter Q2A_count label label-success" )">{{counterQueueQ2a}}</span>',
         link: function(scope, elem, attrs){
             // check id d_Q is changed
            scope.$watch( function(){ return d_Q.d_Q },
                 function changeValue( newValue, oldValue ) {
                   Q2A_length = (d_Q.d_Q.Q2A)?d_Q.d_Q.Q2A.length:0;
                   if(Q2A_length!=scope.counterQueueQ2a){ 
                       // show bg alert on counter
                       elem.parent().addClass('queue_counter_alert');
                       $timeout(function(){
                         elem.parent().removeClass('queue_counter_alert');
                       },1500); 
                    }
                    scope.counterQueueQ2a =  Q2A_length;
                 });
            }
      }
}
counterQueueQ2a.$inject =['$timeout','d_Q'];
// counter queue Q2c + class
counterQueueQ2bw = function ($timeout,d_Q){
    return{
        restrict: 'E',
        replace: true,
        template: '<span class="table-counter Q2BW_count label label-green"  ">{{counterQueueQ2bw}}</span>',
         link: function(scope, elem, attrs){
             // check id d_Q is changed
            scope.$watch( function(){ return d_Q.d_Q },
                 function changeValue( newValue, oldValue ) {
                    Q2BW_length = (d_Q.d_Q.Q2BW)?d_Q.d_Q.Q2BW.length:0;
                   if(Q2BW_length!=scope.counterQueueQ2bw){ 
                       // show bg alert on counter
                       elem.parent().addClass('queue_counter_alert');
                       $timeout(function(){
                         elem.parent().removeClass('queue_counter_alert');
                       },1500); 
                    }
                    scope.counterQueueQ2bw =  Q2BW_length;
                  });
            }
      }
}
counterQueueQ2bw.$inject =['$timeout','d_Q'];
// counter queue Q2a + class
counterQueueQ2b = function ($timeout,d_Q){
    return{
        restrict: 'E',
        replace: true,
        template: '<span class="table-counter Q2B_count label label-success"  >{{counterQueueQ2b}}</span>',
         link: function(scope, elem, attrs){
             // check id d_Q is changed
            scope.$watch( function(){ return d_Q.d_Q },
                 function changeValue( newValue, oldValue ) {
                    Q2B_length = (d_Q.d_Q.Q2B)?d_Q.d_Q.Q2B.length:0;
                   if(Q2B_length!=scope.counterQueueQ2b){ 
                       // show bg alert on counter
                       elem.parent().addClass('queue_counter_alert');
                       $timeout(function(){
                         elem.parent().removeClass('queue_counter_alert');
                       },1500); 
                    }
                     scope.counterQueueQ2b =  Q2AW_length;
                 });
            }
      }
}
counterQueueQ2b.$inject =['$timeout','d_Q'];
// counter queue Q2b + class
counterQueueQ2cw = function ($timeout,d_Q){
    return{
        restrict: 'E',
        replace: true,
        template: '<span class="table-counter Q2CW_count label label-green"  >{{counterQueueQ2cw}}</span>',
         link: function(scope, elem, attrs){
             // check id d_Q is changed
            scope.$watch( function(){ return d_Q.d_Q },
                 function changeValue( newValue, oldValue ) {
                     Q2CW_length = (d_Q.d_Q.Q2CW)?d_Q.d_Q.Q2CW.length:0;
                    if(Q2CW_length!=scope.counterQueueQ2cw){ 
                       // show bg alert on counter
                       elem.parent().addClass('queue_counter_alert');
                       $timeout(function(){
                         elem.parent().removeClass('queue_counter_alert');
                       },1500); 
                    }
                     scope.counterQueueQ2cw =  Q2CW_length;
                 });
            }
      }
}
counterQueueQ2cw.$inject =['$timeout','d_Q'];
// counter queue Q2c + class
counterQueueQ2c = function ($timeout,d_Q){
    return{
        restrict: 'E',
        replace: true,
        template: '<span class="table-counter Q2C_count label label-success"  ">{{counterQueueQ2c}}</span>',
         link: function(scope, elem, attrs){
             // check id d_Q is changed
            scope.$watch( function(){ return d_Q.d_Q },
                 function changeValue( newValue, oldValue ) {
                      Q2C_length = (d_Q.d_Q.Q2C)?d_Q.d_Q.Q2C.length:0;
                     if(Q2C_length!=scope.counterQueueQ2c){ 
                       // show bg alert on counter
                       elem.parent().addClass('queue_counter_alert');
                       $timeout(function(){
                         elem.parent().removeClass('queue_counter_alert');
                       },1500); 
                    }
                     scope.counterQueueQ2c =  Q2C_length;
                     });
            }
      }
}
counterQueueQ2c.$inject =['$timeout','d_Q'];
// counter queue Q3a + class
counterQueueQ3aw = function ($timeout,d_Q){
    return{
        restrict: 'E',
        replace: true,
        template: '<span class="table-counter Q3AW_count label label-green" >{{counterQueueQ3aw}}</span>',
         link: function(scope, elem, attrs){
             // check id d_Q is changed
            scope.$watch( function(){ return d_Q.d_Q },
                 function changeValue( newValue, oldValue ) {
                  Q3AW_length = (d_Q.d_Q.Q3AW)?d_Q.d_Q.Q3AW.length:0;
                   if(Q3AW_length!=scope.counterQueueQ23w){ 
                       // show bg alert on counter
                       elem.parent().addClass('queue_counter_alert');
                       $timeout(function(){
                         elem.parent().removeClass('queue_counter_alert');
                       },1500); 
                    }
                     scope.counterQueueQ3aw =  Q3AW_length;
                 });
            }
      }
}
counterQueueQ3aw.$inject =['$timeout','d_Q'];
// counter queue Q3b + class
counterQueueQ3a = function ($timeout,d_Q){
    return{
        restrict: 'E',
        replace: true,
        template: '<span class="table-counter Q3A_count label label-success" >{{counterQueueQ3a}}</span>',
         link: function(scope, elem, attrs){
             // check id d_Q is changed
            scope.$watch( function(){ return d_Q.d_Q },
                 function changeValue( newValue, oldValue ) {
                     Q3A_length = (d_Q.d_Q.Q3A)?d_Q.d_Q.Q3A.length:0;
                     if(Q3A_length!=scope.counterQueueQ3a){ 
                       // show bg alert on counter
                       elem.parent().addClass('queue_counter_alert');
                       $timeout(function(){
                         elem.parent().removeClass('queue_counter_alert');
                       },1500); 
                    }
                     scope.counterQueueQ3a =  Q3A_length;
                  });
            }
      }
}
counterQueueQ3a.$inject =['$timeout','d_Q'];

 
angular
    .module('PB_jit')

    .directive('counterQueueQ1aw',counterQueueQ1aw)
    .directive('counterQueueQ1a',counterQueueQ1a)
    .directive('counterQueueQ2aw',counterQueueQ2aw)
    .directive('counterQueueQ2a',counterQueueQ2a)
    .directive('counterQueueQ2bw',counterQueueQ2bw)
    .directive('counterQueueQ2b',counterQueueQ2b)
    .directive('counterQueueQ2cw',counterQueueQ2cw)
    .directive('counterQueueQ2c',counterQueueQ2c)
    .directive('counterQueueQ3aw',counterQueueQ3aw)
    .directive('counterQueueQ3a',counterQueueQ3a)
 
 