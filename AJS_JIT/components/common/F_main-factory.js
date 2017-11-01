/*       
*  PB_JIT -- D_main-factory.js
*                                                       
* Author:  @pablo                           
*                                                       
* Purpose:  common main factory  
*                                                       
* Version:  1.0.0                                             
*            
*/ 
// Global Vars

// reponse var call as: varQ1.varq1
// update var data call as: varQ1.update(value)

 // Main Queue
 function d_Q(d_orders,d_Q_brief){
 	d_Q =  [];
 	return {
 		d_Q,
 		update: function(value){
 			// create brief object
 			brief_q = [];
 			// get actual d_Q
 			old_d_Q = this.d_Q;
 			// create temp object from d_Q
 			console.log('tp:'+jQuery.type(this.d_Q));
 		 	if(jQuery.type(this.d_Q)==='object'){ 
 				// object exist so cp
 				updated_d_Q = this.d_Q;

 			} else {
 				// create new object
 	 		 	updated_d_Q = new Map(this.d_Q);
  			 }
 		 	// update single element 
 			// go trought queues names [Q3A]--
			angular.forEach(value,function(q_value,q_index){
		 		// go into single queue
		 		//+++console.log('check queue:'+jQuery.type(old_d_Q[q_index]));
		 			// check if queue object exist
					if(jQuery.type(old_d_Q[q_index])==='undefined'){
						// doesn't exist so create queue and load 1st value
					 	updated_d_Q[q_index] = q_value;
					 	// put on brief
					 	angular.forEach(q_value,function(q_value_single){
					 		brief_q.push(q_value_single);
					 	});
					 	
					 } else { 
					 	// Check if elements on queue must be deleted
						// go trought old queue
						angular.forEach(old_d_Q[q_index],function(old_q_el_value,old_q_el_index){
							to_delete = true;
							element_to_delete = old_q_el_value.task_id;
							// go trought new queue
							angular.forEach(q_value,function(q_el_value,q_el_index){
								// che if element is on new queue
								if(element_to_delete==q_el_value.task_id){
									// element is present
									to_delete = false;
								}
							});
							if (to_delete == true){
								//++console.log('element to delete:'+element_to_delete+' indexold:'+old_q_el_index+' q:'+JSON.stringify(updated_d_Q[q_index]));
								// element is deleted so cancel on queue update
 								updated_d_Q[q_index].splice(old_q_el_index);
 								// remove on d_order
							}
						});	
						// go into queue
				 		angular.forEach(q_value,function(q_el_value,q_el_index){
				 		 	// check if task_id exist on this queue
							task_exist = false;

							angular.forEach(old_d_Q[q_index],function(old_q_el_value,old_d_el_index){
								// check single element on queue
								if(old_q_el_value.task_id==q_el_value.task_id){
									// task exist so check timestamp
									task_exist = true;
									if(old_q_el_value.timestamp!=q_el_value.timestamp){
										// update element and alert it
										/****************************************************************/
										//++console.log('update element:'+JSON.stringify(updated_d_Q[q_index][old_d_el_index]));
										updated_d_Q[q_index][old_d_el_index] = q_el_value; 
										// update on brief

										// insert task data on order
									/*	console.log('order:'+d_orders.d_orders[q_el_value.task_id]);
										if(d_orders.d_orders[q_el_value.task_id]){
											// order exist insert task data
											d_orders.d_orders[q_el_value.task_id]['task_data'] = q_el_value;
										}*/
									}
									 
								}
						 	});
						 	// check if task exists
							if(!task_exist){
								// insert task
								console.log('insert task length'+q_index+':'+(old_d_Q[q_index].length));
								updated_d_Q[q_index][(old_d_Q[q_index].length)] = q_el_value; //<<-------------------------------
							 	// put on brief
						 		brief_q.push(q_el_value);
						
							}
						});	
						
			
				 	}
				 		//this.d_orders[index] = value;
		 	//	console.log('->'+JSON.stringify(value[0]));
		 	//	console.log('=>'+value[0].task_id);
		 		// table update
		 		// the index is cut to use last digits (X) [YYYMMDDXXX] - [20161104000]
		 		//this.d_orders_update[parseInt(index.substring(8,index.length))] = { 'task_id': value.task_id, 'timestamp': value.timestamp}; 

			}); 
			// update d_Q
			this.d_Q = updated_d_Q;
			// set brief
	//		d_Q_brief.set(brief_q);
	 		//console.log('value:'+JSON.stringify(value)+' updated_d_Q:'+JSON.stringify(updated_d_Q));
 		 	// delete tasks
 		 	/************************************************************/
  			// update Q1AW_dropdown_table on topbat
			angular.element('#Q1AW_dropdown_table').scope().Q1AW_dropdown_table();
	 		// update tables
			angular.element('body').scope().update_tables();
  	 	}
 	}
 } 
// Main brief Queue
 function d_Q_brief($timeout){
 	d_Q_brief =  [];
 	return {
 		d_Q_brief,
 		update: function(new_d_Q_brief){
 			 old_d_Q_brief = this.d_Q_brief;
 			 console.log('go d_Q_brief');
 			 if(old_d_Q_brief.length==0){
 				// empty so set all
 				this.d_Q_brief = new_d_Q_brief;
 			} else { 
 				// copy old data 
	 			updated_d_Q_brief = old_d_Q_brief;
 				// Check if elements on queue must be deleted
				// go trought old queue
				angular.forEach(old_d_Q_brief,function(old_q_el_value,old_q_el_index){
					to_delete = true;
					element_to_delete = old_q_el_value.task_id;
					// go trought new queue
					angular.forEach(new_d_Q_brief,function(q_el_value,q_el_index){
						// che if element is on new queue
						if(element_to_delete==q_el_value.task_id){
							// element is present
							to_delete = false;
						}
					});
					if (to_delete == true){
						//++console.log('element to delete:'+element_to_delete+' indexold:'+old_q_el_index+' q:'+JSON.stringify(updated_d_Q[q_index]));
						// element is deleted so cancel on queue update
						// updater_d_Q_brief is still old_d_Q_brief so the index are the same
						updated_d_Q_brief.splice(old_q_el_index);
						// remove on d_order
					}
				});	
				// new and updates
		 		angular.forEach(new_d_Q_brief,function(q_el_value,q_el_index){
		 		 	// check if task_id exist on this queue
					task_exist = false;

					angular.forEach(old_d_Q_brief,function(old_q_el_value,old_d_el_index){
						// check single element on queue
						if(old_q_el_value.task_id==q_el_value.task_id){
							// task exist so check timestamp
							task_exist = true;
							if(old_q_el_value.timestamp!=q_el_value.timestamp){
								// update element and alert it
								/****************************************************************/
								//++console.log('update element:'+JSON.stringify(updated_d_Q[q_index][old_d_el_index]));
								updated_d_Q_brief[old_d_el_index] = q_el_value; 
								// alert element
								// show bg alert on counter
								elem = jQuery('#task_'+q_el_value.task_id);
								elem1 = jQuery('#task_'+q_el_value.task_id+' .brief_txt'); 
		                        elem.addClass('brief_element_alert');
		                        elem1.addClass('brief_element_alert_txt');
		                        $timeout(function(){
		                         	elem.removeClass('brief_element_alert');
		                         	elem1.removeClass('brief_element_alert_txt')
		                        },2500); 

							}
							 
						}
				 	});
				 	// check if task exists
					if(!task_exist){
						// insert task at end of array
						console.log('insert task length:'+(old_d_Q_brief.length));
						updated_d_Q_brief[(old_d_Q.length)] = q_el_value;  
					}
				});
		 	// update queue
			this.d_Q_brief = updated_d_Q_brief;			 
 			}
			// update Q1AW_dropdown_table on topbat
			angular.element('#Q1AW_dropdown_table').scope().Q1AW_dropdown_table();
	 		// update tables
			angular.element('body').scope().update_tables();
 	
	   	 	}
 	}
 } 
 // Main Delivery
 function d_Delivery(){
 	d_Delivery =  [];
 	return {
 		d_Delivery,
 		update: function(d_Delivery){
 			 this.d_Delivery = d_Delivery;
  			// update Q1AW_dropdown_table on topbat
			//angular.element('#Q1AW_dropdown_table').scope().Q1AW_dropdown_table();
	 		// update tables
			//angular.element('body').scope().update_tables();
  	 	}
 	}
 }
// orders data
function d_orders(){
	d_orders = [];
	d_orders_update =[]
	return {  		
 		d_orders,
 		d_orders_update,						 
 		update: function(value){
 			// update single element 
			angular.forEach(value,function(value,index){
		 		// update orders
		 		this.d_orders[index] = value;
		 		// table update
		 		// the index is cut to use last digits (X) [YYYMMDDXXX] - [20161104000]
		 		this.d_orders_update[parseInt(index.substring(8,index.length))] = { 'task_id': value.task_id, 'timestamp': value.timestamp}; 

			}); 
			// remove null values from d_orders_update
		 	this.d_orders_update = this.d_orders_update.filter(Boolean);
 		}
 	}; 

} 
 // main vars
 function s_Main(){
 	s_Main = { 
 			 Q1AW_count:0,
 			 sync_functions:[
 			 	{code:'queue_status', modified:'0'},
 		 	 	{code:'queue_brief_status', modified:'0'},
 				{code:'routing_map', modified:'0'},
 		 	 	{code:'orders_update',modified:'0'},
 			 	],
 			 osm:   
 			 	{ 
 			 		HomeMarker: { 
 			 			lat:44.999618,
 			 			lng:11.296847,
 			 			delivery_area:2
 			 		}
 			 	},
		          
			};
	// reponse var call as: s_Main.s_Main.[object name]
	// update var_name:[object_name], value:[new value of object]
 	return {  		
 		s_Main,								
 		update: function(var_name,value){	
 			// if sync_function set on the code 
 			// var_name:sync_functions value:{code:'queue_status',modified:'2016-04-11 11:22'}
 			if(var_name=='sync_functions'){
 				angular.forEach(this.s_Main.sync_functions,function(s_Main_value,index){
 					// check the index of selected code and update it
 					if(s_Main_value.code==value.code){
 						this.s_Main.sync_functions[index].modified = value.modified;
 					}
 				})
 			} else {
	 			// update object
	 			this.s_Main[var_name]=value; 
	 		}
 		}
 	}; 

 }


  angular
    .module('PB_jit')
    .factory('d_orders',d_orders)
    .factory('d_Q',d_Q)
    .factory('d_Q_brief',d_Q_brief)
    .factory('s_Main',s_Main)
    .factory('d_Delivery',d_Delivery)