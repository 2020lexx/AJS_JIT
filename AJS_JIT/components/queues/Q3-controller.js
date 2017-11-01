/*       
*  PB_JIT -- Q3-controller.js
*                                                       
* Author:  @pablo                           
*                                                       
* Purpose:  controller for Q3 page   
*                                                       
* Version:  1.0.0                                             
*            
*/ 

var  Q3Ctrl = function($scope,$timeout,$state,s_Main,d_Q,d_Delivery){
   


	//  delivery svg graph

   	function set_delivery_grahp(){

    	   // svg graph % 
	    perc_completed = 90;
	    //s et width
	 //   perc_completed_px = (perc_completed * 600)/100;
	    
		var svgNS = "http://www.w3.org/2000/svg";  
		// create <g
		var myG = document.createElementNS(svgNS,"g");
			myG.setAttributeNS(null,"class","bar");   
		// create <rect
	    var myCircle = document.createElementNS(svgNS,"circle"); 
	        myCircle.setAttributeNS(null,"cx",150);
	        myCircle.setAttributeNS(null,"cy",120);
		    myCircle.setAttributeNS(null,"r",101);
       	    myCircle.setAttributeNS(null,"stroke","#1ab394");
      	    myCircle.setAttributeNS(null,"stroke-dashoffset","200%");
	 	    myCircle.setAttributeNS(null,"id","perc_circle");
			myCircle.setAttributeNS(null,"fill","none");
	        myCircle.setAttributeNS(null,"stroke-width",2); 
	    // create home line
	     var myLine = document.createElementNS(svgNS,"line"); 
	        myLine.setAttributeNS(null,"x1",150);
	      	myLine.setAttributeNS(null,"x2",300);
  			myLine.setAttributeNS(null,"y1",120);
	        myLine.setAttributeNS(null,"y2",120);
	     	myLine.setAttributeNS(null,"stroke","#CCC"); 
	     // create perc line
	     var myLineP = document.createElementNS(svgNS,"line"); 
	        myLineP.setAttributeNS(null,"x1",150);
	      	myLineP.setAttributeNS(null,"x2",300);
  			myLineP.setAttributeNS(null,"y1",120);
	      	myLineP.setAttributeNS(null,"id","perc_line");
	  		myLineP.setAttributeNS(null,"y2",120);
	     	myLineP.setAttributeNS(null,"stroke","#1ab394"); 
   			myLineP.setAttributeNS(null,"stroke-width",2); 
   		// circle perc
   		var myCircleP = document.createElementNS(svgNS,"circle"); 
	        myCircleP.setAttributeNS(null,"cx",250);
	        myCircleP.setAttributeNS(null,"cy",120);
		    myCircleP.setAttributeNS(null,"r",8 );
       	    myCircleP.setAttributeNS(null,"stroke",'#1c84c6');
	 		myCircleP.setAttributeNS(null,"fill",'#1c84c6');
	        myCircleP.setAttributeNS(null,"stroke-width",1); 
      		myCircleP.setAttributeNS(null,"id","perc_var_circle");
	
		// create <text
	    var myText = document.createElementNS(svgNS,"text"); 
	    	myText.setAttributeNS(null,"class","block-txt-perc"); 
	     	myText.setAttributeNS(null,"x",120);
	        myText.setAttributeNS(null,"y",120);
			myText.setAttributeNS(null,"text-anchor","left"); 
	     	myText.setAttributeNS(null,"alignment-baseline","central"); 
	   
		    jQuery(myText).text('0 %'); 
	    	// append
		  	jQuery(myG).prepend(myText);  
		 
			// draw delivery points
			// angles<->sides.. 1 point delivery means add home point 
			polygon_angles = [0,0,120,90,72,60,51,45,40,36,33,30];

			delivery = [{order:0,ttd:'home'},{order:1,ttd:'21:30'},{order:2,ttd:'21:30'},{order:3,ttd:'21:30'},{order:4,ttd:'21:30'}];
			
			// angle of polygon
			angle = polygon_angles[delivery.length-1]
			for(i=0;i<delivery.length;i++){
      			// single angle
      			angle_s = angle * i;
      			// label data
      			data = delivery[i];
      			jQuery(myG).append(set_delivery_dest(data,angle_s,svgNS,myG));

			}
    		// append
	      	jQuery(myG).prepend(myCircleP);
			jQuery(myG).prepend(myLineP);
			// append
	        jQuery(myG).prepend(myCircle);
		  	jQuery(myG).prepend(myLine);
		
	    	// insert code
	    	jQuery('#delivery_svg_path').append(myG);

	    	// make % dinamic graph
	    	var d_graph_timer = setInterval(function(){ dinamic_perc_graph();},10);
	    	var per_var = 0;
	    	function dinamic_perc_graph(){
	    		// txt
	    		jQuery('#delivery_svg_path .block-txt-perc').text(per_var+' %');
	    		// circle
	    	 	jQuery('#delivery_svg_path #perc_circle').attr('stroke-dashoffset',(200-(per_var*2)+'%'));
	    		// line
	    		// deg to rad
    			angle=((per_var*360/100)*Math.PI)/180;
    			l_x=101*Math.cos(angle);l_y=101*Math.sin(angle);
	 			jQuery('#delivery_svg_path #perc_line').attr('x2',150+l_x).attr('y2',120+l_y);
	    		jQuery('#delivery_svg_path #perc_var_circle').attr('cx',150+l_x).attr('cy',120+l_y);
	    		// circle

	    		// stop
	    		if(per_var==perc_completed){
	    			clearInterval(d_graph_timer);
	    			return;
	    		}
	    		per_var++;
	    	}
	    	 
    }	 


    // set destination circles
    // data[order,TTD]

    function set_delivery_dest(data,angle,svgNS,myG){

    	// main circle radius
    	r=101; 
    	// deg to rad
    	angle=((angle)*Math.PI)/180;
    	// polar to cartesian
    	x=r*Math.cos(angle);y=r*Math.sin(angle);
	    x_t=(r+20)*Math.cos(angle);y_t=(r+20)*Math.sin(angle);
		
		console.log(data.order+'-'+x+'-'+y);


	    var myCircle = document.createElementNS(svgNS,"circle"); 
	        myCircle.setAttributeNS(null,"cx",x+150);
	        myCircle.setAttributeNS(null,"cy",y+120);
		    myCircle.setAttributeNS(null,"r",(data.ttd=='home'?8:5));
       	    myCircle.setAttributeNS(null,"stroke",(data.ttd=='home'?'#1c84c6':'#1ab394'));
	 		myCircle.setAttributeNS(null,"fill",(data.ttd=='home'?'#1c84c6':'#1ab394'));
	        myCircle.setAttributeNS(null,"stroke-width",1); 
    	// create <text
	    var myText = document.createElementNS(svgNS,"text"); 
	    	myText.setAttributeNS(null,"class","block-txt"); 
	     	myText.setAttributeNS(null,"x",x_t+140);
	        myText.setAttributeNS(null,"y",y_t+120);
			myText.setAttributeNS(null,"text-anchor","left"); 
	     	myText.setAttributeNS(null,"alignment-baseline","central"); 
			// no text on home
			jQuery(myText).text(data.ttd=='home'?'':'#'+data.order+'-'+data.ttd);

		   	// append
		  	jQuery(myG).prepend(myCircle);  
  			jQuery(myG).prepend(myText);  

		  	return myG;
		

    }
   // delivery map
   function set_delivery_map(){
   		// call leaflet map
   		delivery_map('osm_delivery',s_Main,d_Delivery)
   }

  // show Queue
  function view_queue(){

  		$scope.filterQ3=true;


		// update counters
	 	angular.element('body').scope().update_counters();
	 	
	 //	$scope.table_queueQ3aw = d_Q.d_Q.Q3AW;
  	//	$scope.table_queueQ3a = d_Q.d_Q.Q3A;

	}

	// init functions 
   $timeout(function(){
   	 
  // 	 view_queue();

   	 $scope.main.radioModel3 = 'AllQ3';
	 console.log(d_Delivery.d_Delivery);
	 	set_delivery_grahp();
	 	set_delivery_map();
	 //+++   $scope.iddly_bt.iddly = '';
	 });
 
};
Q3Ctrl.$inject =['$scope','$timeout','$state','s_Main','d_Q','d_Delivery'];

angular
    .module('PB_jit')
    .controller('Q3Ctrl', Q3Ctrl)
