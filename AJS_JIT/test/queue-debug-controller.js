/**
 *
 *  PB_JIT
 *
 */

 

/* +++ Q2 +++ */

/* +++ Q3 +++ */



/* +++ All Q +++ */


 
/* Test Page */
var QueueDebugCtrl = function($scope,$timeout,$interval,fetchAsync,d_Q,d_orders,d_queue_history){

	// px of now() axis
 	var now_px=160;

 
   // data[queue_id,task_index,task_thread,task_id,task_start,tast_length]

    function insert_bar(data){
		
		var svg_graph;

    	 // task length in px (by now data.task_length = minutes)
   		 var task_length_px = parseInt(data.task_length)*30; //time to px (30px -> 1')
 
     	switch(data.task_thread){
    		case '1':
    			var y=120;
    		break;
    		case '2':
    			var y=89;
    		break;
			case '3':
				var y=55;
    		break;
			case '4':
				var y=22;
    		break;
    	}
    	
      	// get time (unix '')
    	now_tm = get_now();
        // update axis x
       update_x_axis(now_tm);
 
    	// get task_start diff
    	diff_tm = (data.task_start*1000) - now_tm;

     	// convert to px
    	// time to px (30px -> 1')
    	x = (((diff_tm/1000)/60)*30)+now_px;
 
   		// if go out graph block x and reduce length
  	 	if(x<=10){ task_length_px = task_length_px - (10-x); x = 10; }
     	//	if(x<=10){   x = 10; }
 		
   		// if out return
   	 	if(task_length_px<=0){ return;} 
 	//	console.log('process:'+JSON.stringify(data));
   		console.log('queue'+data.queue_id+'x:'+x+' y:'+y+ ' task_length:'+task_length_px); 
  
   		// cancel previous graph
   		jQuery('#'+data.task_id+data.queue_id).parent().remove();
     	// convert unixtime to readable form
    	var TTD_txt = new Date(parseInt(data.task_start));
 	 
    	// svg graph
    	var svgNS = "http://www.w3.org/2000/svg";  
    	// create <g
    	var myG = document.createElementNS(svgNS,"g");
    		myG.setAttributeNS(null,"class","bar");   

    	// fill color
    	var fill = "#ed5565";
    	if(x<=now_px){ fill = "#1ab394"; }
    	if((x+task_length_px)<=now_px){ fill = "#1c84c6";}

    	// create <rect
	    var myRectangle = document.createElementNS(svgNS,"rect"); 
	        myRectangle.setAttributeNS(null,"id",data.task_id+data.queue_id);
	        myRectangle.setAttributeNS(null,"x",x);
	        myRectangle.setAttributeNS(null,"y",y);
	        myRectangle.setAttributeNS(null,"fill",fill);
	 		myRectangle.setAttributeNS(null,"width",task_length_px);
	        myRectangle.setAttributeNS(null,"height",30);
	        myRectangle.setAttributeNS(null,"class","bar bar"+data.task_id);
	    // create <text
	    var myText = document.createElementNS(svgNS,"text"); 
	    	myText.setAttributeNS(null,"class","bar"); 
	     	myText.setAttributeNS(null,"x",x+10);
	        myText.setAttributeNS(null,"y",y+8);
			myText.setAttributeNS(null,"text-anchor","left"); 
	     	myText.setAttributeNS(null,"alignment-baseline","central"); 
	   
	//    jQuery(myText).text('#'+data.task_id+' - idx:'+data.task_index+' thr:'+data.task_thread+' status:'+data.task_status+' / '+TTD_txt.getHours()+':'+((TTD_txt.getMinutes()<10)?'0'+TTD_txt.getMinutes():TTD_txt.getMinutes())); 
   	    jQuery(myText).text(data.item_id_view+' '+data.task_start_time); 
   	// append
	  	jQuery(myG).prepend(myText);  
	    // append
        jQuery(myG).prepend(myRectangle);
		
	   	// insert code
	   	switch(data.queue_id){
	   		case '1':
	   			return;
	   		break;
	   		case '2':
	   			return;
	   		break;
	   		case '3':
	   			svg_graph ='#Q2a_svg_path';
	   		break;
	   		case '4':
	   			svg_graph ='#Q2a_svg_path';
	   		break;
	   		case '5':
	   			svg_graph ='#Q2b_svg_path';
	   		break;
	   		case '6':
	   			svg_graph ='#Q2b_svg_path';
	   		break;
	   		case '7':
	   			svg_graph ='#Q2c_svg_path';
	   		break;
	   		case '8':
	   			svg_graph ='#Q2c_svg_path';
	   		break;
	   		case '9':
	   			svg_graph ='#Q3_svg_path';
	   		break;
	   		case '10':
	   			svg_graph ='#Q3_svg_path';
	   		//	console.log('q3:'+data.queue_id+'-'+data.task_start_time);
	   		break;
	   	}
	   	console.log(svg_graph+"-"+myG);
    	jQuery(svg_graph).append(myG);
    	 
    }

    // update x axis (time)
    function update_x_axis(now_tm){
    	var t0 = new Date(now_tm);
    	var t1 = new Date(now_tm+300000);
    	var t2 = new Date(now_tm+600000);
    	var t3 = new Date(now_tm+900000);
    	var t4 = new Date(now_tm+1200000);
   		var t5 = new Date(now_tm+1500000);
   
    	jQuery('.t0').text(t0.getHours()+':'+((t0.getMinutes()<10)?'0'+t0.getMinutes():t0.getMinutes()));
	   	jQuery('.t1').text(t1.getHours()+':'+((t1.getMinutes()<10)?'0'+t1.getMinutes():t1.getMinutes()));
	   	jQuery('.t2').text(t2.getHours()+':'+((t2.getMinutes()<10)?'0'+t2.getMinutes():t2.getMinutes()));
	   	jQuery('.t3').text(t3.getHours()+':'+((t3.getMinutes()<10)?'0'+t3.getMinutes():t3.getMinutes()));
	   	jQuery('.t4').text(t4.getHours()+':'+((t4.getMinutes()<10)?'0'+t4.getMinutes():t4.getMinutes()));
	   	jQuery('.t5').text(t5.getHours()+':'+((t5.getMinutes()<10)?'0'+t5.getMinutes():t5.getMinutes()));

    }

    // async queue_history request
     stopFetchAsync = $interval(fetch_async, 5000);
   

	// fetch Async
function fetch_async(){ 
	
	data = {'fnc_async':'QueueHistory','data':0};
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
	 	 
	 		// ********** TESTING ***********
	 		case 'd_queue_history':
	 			d_queue_history.update(data_fetched);
	 			// view data
	 			angular.forEach(data_fetched,function(value,index){
	 				// go into queue
					angular.forEach(value,function(value1,index1){

						data = {
							queue_id:index,
							task_id: value1['task_id'],
							task_id_view: value1['task_id_view'],
							item_id_view: value1['item_id_view'],
							task_start: value1['task_start'],
							task_start_time: value1['task_start_time'],
							task_index: value1['task_index'],
							task_thread: value1['task_thread'],
							task_status: value1['task_status'],
							task_length: value1['task_length']
						};
						// call view function
						insert_bar(data);

					});


	 			})
	 		break;
	 	}
		
	//	console.log(">>"+JSON.stringify(data)+"<<<>>"+ data[0].fetch_response_data+"<<<");
	
	}
}
QueueDebugCtrl.$inject =['$scope','$timeout','$interval','fetchAsync','d_Q','d_orders','d_queue_history'];
 
 

angular
    .module('PB_jit')
      .controller('QueueDebugCtrl', QueueDebugCtrl) 