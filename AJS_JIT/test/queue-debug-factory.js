/**
 *
 *  PB_JIT
 *
 */

 
 // Main Delivery
 function d_queue_history(){
   d_queue_history =  [];
    return {
       d_queue_history,
        update: function(d_Delivery){
             this.d_queue_history = d_queue_history;
            // update Q1AW_dropdown_table on topbat
            //angular.element('#Q1AW_dropdown_table').scope().Q1AW_dropdown_table();
            // update tables
            //angular.element('body').scope().update_tables();
        }
    }
 }

angular
    .module('PB_jit')
      .factory('d_queue_history', d_queue_history) 