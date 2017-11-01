<?php
/**
 *
 * Copyright (c) 2017. @pablo
 *
 * Test Code
 */

namespace common\controllers\ESI;

use Yii; 

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExternalServicesInterfaceController.
 */
class ESIController extends Controller
{


    /* OSRM Routing */

    public function osrmRouting($data){

        // osrm modes
        $service = 'trip';
        $profile = 'driving';
        $server_url = 'http://192.168.0.34:5000';

        $waypoints_data = '';
        // get waypoints data
        foreach($data as $key => $value){
            // invert lat - long >:-(
            $coords = split(',',$data[$key]['coords']);
            $coords_data = $coords[1].','.$coords[0];
            // get data from array
            $waypoints_data = $waypoints_data.$coords_data.';';
        }
        // delete last ';'
        $waypoints_data = substr($waypoints_data,0,-1);
        
        // make url
        $url = $server_url.'/'.$service.'/v1/'.$profile.'/'.$waypoints_data.'?steps=true&overview=false';
        
     
        // request data
        $resp_json = ESIController::curl_file_get_contents($url);
        $resp = json_decode($resp_json, true);

        if($resp['code']=='Ok'){
            // make response array
            $address_resp = $resp;
           
        }else{
            $address_resp = array(
                'code' => $resp['code'],
                    );
        }

        return $address_resp;
    }

    /* OSRM Single Route */
    // compute single route to user

    public function osrmSingleRoute($data){
       
        // osrm modes
        $service = 'route';
        $profile = 'driving';
        $server_url = 'http://192.168.0.34:5000';

         // invert lat - long >:-(
        $coords = split(',',$data['home']);
        $coords_home = $coords[1].','.$coords[0];
        $coords = split(',',$data['customer']);
        $coords_customer= $coords[1].','.$coords[0];
      
        $waypoints_data = $coords_home.';'.$coords_customer;

        // make url
        $url = $server_url.'/'.$service.'/v1/'.$profile.'/'.$waypoints_data.'?steps=false&overview=false';
  
  
        // request data
        $resp_json = ESIController::curl_file_get_contents($url);
        $resp = json_decode($resp_json, true);

        if($resp['code']=='Ok'){
            // make response array
            $address_resp = array(
                'code' => $resp['code'],
                'shop_distance_route' => number_format(($resp['routes'][0]['distance']/1000), 2, '.', ''), 
                'shop_time_route' => gmdate('H:i:s', $resp['routes'][0]['duration'])
                );
                }else{
            $address_resp = array(
                'code' => $resp['code'],
                );
        }

        return $address_resp;
    }
	/* Geocoding */
	// $address=urlencode("1600 Amphitheatre Parkway, Mountain View, CA");
 
    public function getCoordsFromAddress($address){
        
        $url_a = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=";

       	$url = $url_a.urlencode($address);
        
        $resp_json = ESIController::curl_file_get_contents($url);
        $resp = json_decode($resp_json, true);
		
		if($resp['code']=='OK'){
			// make response array
	        $address = $resp['results'][0]['geometry']['location'];

	        $address_resp = array(
		        'lat' => $address['lat'],
		        'lng' => $address['lng'],
		      	'status' => $resp['status']
				);
           
        }else{
            $address_resp = array(
            	'code' => $resp['code']
            	);
        }

		return $address_resp;
    }
 

	/* Reverse Geocoding */
	// $latlng=45,44;
 
    public function getAddressFromCoords($latlng){
        
        $url_a = "http://maps.google.com/maps/api/geocode/json?sensor=false&latlng=";

       	$url = $url_a.urlencode($latlng);
        
        $resp_json = ESIController::curl_file_get_contents($url);
        $resp = json_decode($resp_json, true);
 		
 		if($resp['status']=='OK'){
       
	   		// make response array
	        $address = $resp['results'][0]['address_components'];

	        $address_resp = array(
		        'street_name' => $address[1]['short_name'],
		        'street_number' => $address[0]['long_name'],
		        'locality' => $address[2]['long_name'],
				'area_level_3' => $address[3]['long_name'],
		  		'area_level_2_long' => $address[4]['long_name'],
				'area_level_2_short' => $address[4]['short_name'],
		  		
				'area_level_1' => $address[5]['long_name'],
				'country' => $address[6]['short_name'],
				'postal_code' => $address[7]['short_name'],
				'geo_loc_data' => $latlng,
				'status' => $resp['status']
				);
           
        }else{
            $address_resp = array(
            	'status' => $resp['status']
            	);
        }

		return $address_resp;

    }

    /* Common Private Functions */

    /* URL Curl Access */

    static private function curl_file_get_contents($URL){
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents) return $contents;
            else return FALSE;
    }












}
    