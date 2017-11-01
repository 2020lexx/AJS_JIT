/*       
*  PB_JIT -- Q2-controller.js
*                                                       
* Author:  @pablo                           
*                                                       
* Purpose:  controller for Q2 page   
*                                                       
* Version:  1.0.0                                             
*            
*/ 

var Q2Ctrl = function($scope,$timeout,d_Q){
   	
   	// task length in px
    var task_lenght_px=150;
 	// px of now() axis
 	var now_px=160;
    
   	// on hover item 
   	$scope.hover_item = function(item){
   		jQuery('#Q2_svg_path #'+item).css('fill','#f8ac59');
   		console.log("hover:"+item);
   	};
	// on leaveitem 
   	$scope.leave_item = function(item){
   		// check if color base is green or red
   		x = jQuery('#Q2_svg_path #'+item).attr('x');

   		jQuery('#Q2_svg_path #'+item).css('fill',(x<=now_px)?"#1ab394":"#ed5565");
   		console.log("hover:"+item);
   	};
 // show Queue
  function view_queue(){

  		$scope.filterQ2=true;

		// update counters
	 	angular.element('body').scope().update_counters();
	 	
/*	 	$scope.table_queueQ2aw = d_Q.d_Q.Q2AW;
	  	$scope.table_queueQ2a = d_Q.d_Q.Q2A;
		$scope.table_queueQ2bw = d_Q.d_Q.Q2BW;
  		$scope.table_queueQ2b = d_Q.d_Q.Q2B;
		$scope.table_queueQ2cw = d_Q.d_Q.Q2CW;
  		$scope.table_queueQ2c = d_Q.d_Q.Q2C;
*/
	}

	$scope.show = function(){
		//console.log("Q2 Var:"+varQ1.varq2);
	};
    
    // svg queue chart
    // data[queue_id,task_id,TTD,task_start,tast_length]

    function insert_bar(data){

    	switch(data.queue_id){
    		case 1:
    			var y=282;
    		break;
    		case 2:
    			var y=210;
    		break;
			case 3:
				var y=138;
    		break;
			case 4:
				var y=66;
    		break;
    	}
    	
    	// get time (unix '')
    	now_tm = get_now();
        // update axis x
      update_x_axis(now_tm);
 
    	// get task_start diff
    	diff_tm = data.task_start - now_tm;
    	// convert to px
    	// time to px (30px -> 1')
    	x = (((diff_tm/1000)/60)*30)+now_px;
 
   		// if go out graph block x and reduce length
   		if(x<=10){ task_lenght_px = task_lenght_px - (10-x); x = 10; }
   		
   		// if out return
   		if(task_lenght_px<=0){ return;}
    	   console.log("Q2 Controller x:"+x);
    	// convert unixtime to readable form
    	var TTD_txt = new Date(parseInt(data.task_start));
 	 
    	// svg graph
    	var svgNS = "http://www.w3.org/2000/svg";  
    	// create <g
    	var myG = document.createElementNS(svgNS,"g");
    		myG.setAttributeNS(null,"class","bar");   
    	// create <rect
	    var myRectangle = document.createElementNS(svgNS,"rect"); 
	        myRectangle.setAttributeNS(null,"id",data.task_id);
	        myRectangle.setAttributeNS(null,"x",x);
	        myRectangle.setAttributeNS(null,"y",y);
	        myRectangle.setAttributeNS(null,"fill",(x<=now_px)?"#1ab394":"#ed5565");
	 		myRectangle.setAttributeNS(null,"width",task_lenght_px);
	        myRectangle.setAttributeNS(null,"height",30);
	        myRectangle.setAttributeNS(null,"class","bar bar"+data.task_id);
	    // create <text
	    var myText = document.createElementNS(svgNS,"text"); 
	    	myText.setAttributeNS(null,"class","bar"); 
	     	myText.setAttributeNS(null,"x",x+10);
	        myText.setAttributeNS(null,"y",y+15);
			myText.setAttributeNS(null,"text-anchor","left"); 
	     	myText.setAttributeNS(null,"alignment-baseline","central"); 
	   
	    jQuery(myText).text('#'+data.task_id+' - '+TTD_txt.getHours()+':'+((TTD_txt.getMinutes()<10)?'0'+TTD_txt.getMinutes():TTD_txt.getMinutes())); 
    	// append
	  	jQuery(myG).prepend(myText);  
	    // append
        jQuery(myG).prepend(myRectangle);
		
	   	// insert code
    	jQuery('#Q2_svg_path').append(myG);
    	 
    }

    // update x axis (time)
    function update_x_axis(now_tm){
    	var t0 = new Date(now_tm);
    	var t1 = new Date(now_tm+300000);
    	var t2 = new Date(now_tm+600000);
    	var t3 = new Date(now_tm+900000);
    	var t4 = new Date(now_tm+1200000);
   		var t5 = new Date(now_tm+1500000);
   
    	jQuery('#t0').text(t0.getHours()+':'+((t0.getMinutes()<10)?'0'+t0.getMinutes():t0.getMinutes()));
	   	jQuery('#t1').text(t1.getHours()+':'+((t1.getMinutes()<10)?'0'+t1.getMinutes():t1.getMinutes()));
	   	jQuery('#t2').text(t2.getHours()+':'+((t2.getMinutes()<10)?'0'+t2.getMinutes():t2.getMinutes()));
	   	jQuery('#t3').text(t3.getHours()+':'+((t3.getMinutes()<10)?'0'+t3.getMinutes():t3.getMinutes()));
	   	jQuery('#t4').text(t4.getHours()+':'+((t4.getMinutes()<10)?'0'+t4.getMinutes():t4.getMinutes()));
	   	jQuery('#t5').text(t5.getHours()+':'+((t5.getMinutes()<10)?'0'+t5.getMinutes():t5.getMinutes()));

    }

    // init
    $timeout(function(){
	 //  view_queue();
   		data =  {queue_id:1,task_id:22,TTD:'0',task_start:'1482506913000',task_lenght:100};
 	insert_bar(data);

	});


};
Q2Ctrl.$inject =['$scope','$timeout','d_Q'];


angular
    .module('PB_jit')
    .controller('Q2Ctrl', Q2Ctrl)
  