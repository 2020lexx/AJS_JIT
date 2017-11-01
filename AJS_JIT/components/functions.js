/*       
*  PB_JIT -- functions.js
*                                                       
* Author:  @pablo                           
*                                                       
* Purpose: js common functions     
*                                                       
* Version:  1.0.0                                             
*            
*/ 
// user marker
function osm_user_marker(obj){
	  return obj.AwesomeMarkers.icon({icon: 'user',markerColor: 'cadetblue'});
 }
// home marker
function osm_home_marker(obj){
	return obj.AwesomeMarkers.icon({icon:'home',markerColor:'green'});
 }
 // tile layer
function osm_tile_layer(obj){
	return obj.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors | <a href="http://www.0xsystems.com">0X Systems Devs</a> | @pablo'
	});
}

// leaflet map
function single_customer_map(id,order_data,s_Main){

	map_coord_x = order_data.user_data.coords.split(',')[0];
	map_coord_y = order_data.user_data.coords.split(',')[1];
	
		// leaflet map
	var map = L.map(id).setView([map_coord_x, map_coord_y], 15);
	  
	  // user marker  
    L.marker([map_coord_x, map_coord_y],{icon:osm_user_marker(L)}).addTo(map)
    .bindPopup('<div style="text-align:center"><b>'+order_data.user_data.last_name+' '+order_data.user_data.first_name+'</b><br>'+order_data.user_data.street_name+' '+order_data.user_data.street_number+'<br><span class="fa fa-clock-o"></span> <i>'+order_data.shop_time_route+'<br><span class="fa fa-truck"></span> '+order_data.shop_distance_route+' km</i></div>')
    .openPopup();
     
	// home marker
	L.marker([s_Main.s_Main.osm.HomeMarker.lat,s_Main.s_Main.osm.HomeMarker.lng],{icon:osm_home_marker(L)}).addTo(map);
	  
    // shop delivery area
    var circle = L.circle([s_Main.s_Main.osm.HomeMarker.lat,s_Main.s_Main.osm.HomeMarker.lng], {
        color: '#23c6c8',
        fillColor: '#23c6c8',
        fillOpacity: 0,
        radius: (s_Main.s_Main.osm.HomeMarker.delivery_area)*1000,
    }).addTo(map);

    // line shop-client
    L.polyline(
        [[s_Main.s_Main.osm.HomeMarker.lat,s_Main.s_Main.osm.HomeMarker.lng],
        [map_coord_x, map_coord_y]]
        , {color: '#23c6c8'}).addTo(map);

    // layers
    var satLayer = L.gridLayer.googleMutant({
          type: 'satellite' // valid values are 'roadmap', 'satellite', 'terrain' and 'hybrid'
        });
    
    osm_tile_layer(L).addTo(map);

    L.control.layers({
            Satellite:satLayer,
            OpenStreetMap:osm_tile_layer(L),
  	        },{},{}).addTo(map);  
}

// leaflet map
function delivery_map(id,s_Main,d_Delivery,id_delivery){

	// get delivery id
 	delivery_data = d_Delivery.d_Delivery[id_delivery];

	// leaflet map
	var map = L.map(id).setView([s_Main.s_Main.osm.HomeMarker.lat,s_Main.s_Main.osm.HomeMarker.lng], 14);
	
	var route_color = ['#1ab394','#1c84c6','#23c6c8','#f8ac59','#ed5565','#1ab394','#1c84c6','#23c6c8','#f8ac59','#ed5565','#1ab394','#1c84c6','#23c6c8','#f8ac59','#ed5565'];
	// markers
 	angular.forEach(delivery_data.waypoints, function(value,key){
	    // coords
	    map_coord_x = value.coords.split(',')[0];
		map_coord_y = value.coords.split(',')[1];
		L.marker([map_coord_x, map_coord_y],{icon:osm_user_marker(L)})
    	.bindTooltip('<div style="text-align:center"><span class="label" style="color:white;background-color:'+route_color[key]+'">'+(key==0?'':key)+'</span>&nbsp; <b>'+value.user_data.name+'</b><br>'+(!value.user_data.addr?'':value.user_data.addr)+'</div>', {permanent: true, className: "marker_label", opacity:0.8 })
    	.addTo(map);
    	// routes
 		angular.forEach(value.geometry,function(value1,key1){

 	 		L.Polyline.fromEncoded(value1,{ color: route_color[key+1]}).addTo(map);

 		})
   
	}) 
 	
 	// home marker
 	L.marker([s_Main.s_Main.osm.HomeMarker.lat,s_Main.s_Main.osm.HomeMarker.lng],{icon:osm_home_marker(L)}).addTo(map);
	  
    // shop delivery area
    var circle = L.circle([s_Main.s_Main.osm.HomeMarker.lat,s_Main.s_Main.osm.HomeMarker.lng], {
        color: '#23c6c8',
        fillColor: '#23c6c8',
        fillOpacity: 0,
        dashArray: '20,15',
        radius: (s_Main.s_Main.osm.HomeMarker.delivery_area)*1000,
    }).addTo(map);

       // layers
    var satLayer = L.gridLayer.googleMutant({
          type: 'satellite' // valid values are 'roadmap', 'satellite', 'terrain' and 'hybrid'
        });
    
    osm_tile_layer(L).addTo(map);

    L.control.layers({
            Satellite:satLayer,
            OpenStreetMap:osm_tile_layer(L),
  	        },{},{}).addTo(map);  
}
// sleep
 function sleepFor( sleepDuration ){
    var now = new Date().getTime();
    while(new Date().getTime() < now + sleepDuration){ /* do nothing */ } 
}  

$(document).ready(function () {


    // Full height of sidebar
    function fix_height() {
        var heightWithoutNavbar = $("body > #wrapper").height() - 61;
        $(".sidebard-panel").css("min-height", heightWithoutNavbar + "px");

        var navbarHeigh = $('nav.navbar-default').height();
        var wrapperHeigh = $('#page-wrapper').height();

        if(navbarHeigh > wrapperHeigh){
            $('#page-wrapper').css("min-height", navbarHeigh + "px");
        }

        if(navbarHeigh < wrapperHeigh){
            $('#page-wrapper').css("min-height", $(window).height()  + "px");
        }

        if ($('body').hasClass('fixed-nav')) {
            if (navbarHeigh > wrapperHeigh) {
                $('#page-wrapper').css("min-height", navbarHeigh - 60 + "px");
            } else {
                $('#page-wrapper').css("min-height", $(window).height() - 60 + "px");
            }
        }

    }

    $(window).bind("load resize scroll", function() {
        if(!$("body").hasClass('body-small')) {
                fix_height();
        }
    })

    // Move right sidebar top after scroll
    $(window).scroll(function(){
        if ($(window).scrollTop() > 0 && !$('body').hasClass('fixed-nav') ) {
            $('#right-sidebar').addClass('sidebar-top');
        } else {
            $('#right-sidebar').removeClass('sidebar-top');
        }
    });

    setTimeout(function(){
        fix_height();
    });

});

// Minimalize menu when screen is less than 768px
$(function() {
    $(window).bind("load resize", function() {
        if ($(document).width() < 769) {
            $('body').addClass('body-small')
        } else {
            $('body').removeClass('body-small')
        }
    })
})

 
// time 
function get_now(){
    var t = new Date();
    return  t.getTime();
}
 
 
// timestamp to hours:minutes
function timestamp_to_hm(timestamp){
 	time_hm = new Date(timestamp*1000); 
 	time_m = (time_hm.getMinutes()<10)?'0'+time_hm.getMinutes():time_hm.getMinutes();
	return (time_hm.getHours()+':'+ time_m);
} 

// diff times
function diff_time(time1,time2){
    var t = new Date();
    date = t.getFullYear()+'/'+t.getMonth()+'/'+t.getDate();
    s_time1 = new Date(date+' '+time1);
    s_time2 = new Date(date+' '+time2);
     difference = s_time2.getTime() - s_time1.getTime();
    return  Math.round(difference/60000);
}