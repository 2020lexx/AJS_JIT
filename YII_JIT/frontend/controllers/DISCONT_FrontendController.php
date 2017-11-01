<?php
/**
 *
 * Copyright (c) 2017. @pablo
 *
 * Test Code
 */

namespace frontend\controllers;
 

use common\controllers\PRO\PROController; 
use common\controllers\MPC\MPCController;
use common\controllers\MKR\MKRController;
use common\controllers\ESI\ESIController;
use common\controllers\ZSA\ZSAController;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class FrontendController extends \yii\web\Controller
{
    
	// disable CSFR - fetch error	
	public $enableCsrfValidation = false;
	
	/*  Main Mobile Asynchronous Interface */

	/* { token: []
		 fnc_async:[]
		 data: {
			[1,2,3,4]
			}
		 }} */

	public function actionMobileasync(){


		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
		  // POST method
		  $data_recieved = json_decode(file_get_contents("php://input"),true);
		  
		  // check token
		  $token = $data_recieved['token'];

		  // call funcion
		  $call_function = $data_recieved['fnc_async'];
		  $call_function_data = $data_recieved['data'];

		  $result = $this->$call_function($call_function_data);
 		  
 		  // return to mobile
		  $data_to_send = json_encode($result); 
		  echo $data_to_send;
		
		} 
		else
		{
		   // GET method
		 	$data=$this->init_sync_data('0');
		//   $data=MPCController::getJSONcache(['items_data_init_fetch']);
		 //  $data=$this->action6([12,23,24]);
		   print_r($data);
		//   echo json_encode($data);
		}
 
	}

	/*  Main Mobile Synchronous Interface */

	/* { token: []
		 fnc_async:[]
		 data: {
			[1,2,3,4]
			}
		 }} */

	public function actionMobilesync(){

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
		  $data = json_decode(file_get_contents("php://input"));
		  // check token
		  // $data['token']

		  // call funcion
		 //-- $call_function=$data['fnc_async'];
		//--  $call_function_data=$data['data'];

		//--  $result = $call_funcion($call_function_data);
 		  // return to mobile
		 //--json_encode($call_function);
		}

	}

	/* init sync data */
	/* recieved [{var:var_name,timestamp:timestamp}] of mobile data storaged */

	public function init_sync_data($data){
 	
   /*
 	$data=[];
		$data['var_data']= array([ 'code'=>'items_data_init','modified'=>'2016-05-15 17:26:10'],
      ['code'=>'middle_menu_cat','modified'=>'2016-05-15 17:27:03']);  
 */ 
		// get var name array
		foreach ($data['var_data'] as $key => $value) {
			$query_var[] = $value['code'];
		}
		// get cache table
		$cache_data=MPCController::getJSONcache($query_var);
		$response=[];
		// check modified timestamp
	 	foreach ($data['var_data'] as $key => $value) {
			$var_to_check=$value['code'];
			$timestamp_to_check=$value['modified']; 
			foreach ($cache_data as $key1 => $value1) {
				// compare modified timestamp
				if($var_to_check==$value1['code']){
					if($timestamp_to_check!=$value1['modified']){
						// modified 
						$modified = $value1['modified'];
						$data = $value1['data'];
					} else {
						// not modified
						$modified = 0;
						$data = 0;
					}
					$response[]=[
								'code'=>$value1['code'],
								'data'=>$data,
								'modified'=>$modified
								];

				}
			}
		}   
	  $result = $this->MobileFetchResponse([['init_sync_data',$response]]);
      return $result;
	  
	}

	/* checkout add procedure */

	public function checkout_procedure(){


		//$data['customer_id'] = 
		
		// insert data	
		MKRController::CheckoutInsert($data);

	}
	/* Test - data exchange */
	public function actionTest1(){ 
		// $this->get_geoloc_address("enrico de nicola,14,46028, Sermide,MN,IT");
		$result = MKRController::PromoSingleProduct();

		print_r($result);
	//	 $this->get_neraby_shops("45.00084262,11.29684459");
		//return  "test_funcion - arg:".implode(" ",$arg) ;
	}

	/* Reverse Geocoding Query */

	public function get_geoloc_address($latlng){

	 $address = ESIController::getAddressFromCoords($latlng);
		//	$address = ESIController::getCoordsFromAddress($latlng);
	
	  $result = $this->MobileFetchResponse([['geoloc_address',$address]]);
     
      return $result;
	

	}

	/* Get Nearby Shops of Customer's Position */

	public function get_neraby_shops($latlng){

		$nearby_shops = ZSAController::getNearbyShops($latlng);
		$result = $this->MobileFetchResponse([['nearby_shops',$nearby_shops]]);
     	
     	return $result;
	}
	/*
    *   test
    */
    public function action1(){


		// return fields
		$fields=['pro_product.id',
				 'pro_product.name',
				 'pro_category.name AS category_name',
				 'pro_product.images',
				 'pro_product.short_desc',
				 'pro_product.long_desc'];
        
        //$product=ProProduct::findOne(5);
        $product=PROController::GetAllIngredients($fields);
        
        print_r($product);
        echo "<br><br>";
    //    echo $product->category0->name;

    }
	public function action2(){
 			
 			// return fields
 			$fields=['id','name','images','short_desc','long_desc'];

 			// Get All Categories Array
	        $product=PROController::GetAllCategories($fields);
	     
	        print_r($product);
	        echo json_encode($product);
	    }

	public function action3(){
 			
 	 		// groups - Search elements + all his secondary elements
 			$groups=[57,56];
 			// Get Secondary elements from groups
	        $product=PROController::GetGroupsAndElements($groups);
	     	$product=PROController::ProductList(0);

	        print_r($product);
	    }

	    public function action4(){
 			
 	 		// groups - Search element match secondary-elements
 			$elements=[1,23];
 			// Get All Categories Array
	        $product=PROController::GetElementsAndGroups($elements);
	     
	        print_r($product);
	    }

	     public function action5(){
 			
 	 	 	// Get All Categories Array
	        $product=MKRController::PromoList();
	     
	        print_r($product);
	    }

	public function actionMakecache(){

		MPCController::setJSONcacheMobile('promo_categories');
		MPCController::setJSONcacheMobile('promo_single_product');
	MPCController::setJSONcacheMobile('promo_list');

		echo "OK";
	}

/* in PRO
	 public function MobileCategoriesMenu(){
 			
 			// App Categories middle_menu

 	 		// return fields
 			$fields=['`id`AS `handle`','name','parent_id','level','concat("L",(level+1),"_",id) AS `toHandle`','concat("L",(level-1)) AS `fromHandle`','last'];

 			// Get All Categories Array
	        $product=PROController::GetAllCategories($fields);
	      	
	      	$catArray = [];
	      	
	      	foreach ($product as $key => $value) {
	      	    $catArrayInner = [];
	      		foreach ($product[$key] as $key1 => $value1) {
	      			$catArrayInner[]=$value1;
 
	      		} 
	      		$catArrayInner1=[];
	      		foreach ($catArrayInner as $key2 => $value2) {
                        $catArrayInner1["L".$value2['level']."_".$value2['parent_id']][$key2]=$value2;
                    } 
                 $catArrayInner2 =[];
                 foreach ($catArrayInner1 as $key3 => $value3) {
                 	 $catArrayInner2[]=array(
                 	 	"handle"=>$key3,
                 	 	"data"=>$value3
                 	 	 );

                 	 
                 }
	      		 $catArray[]=array(
	      			"catLevel"=>$key,
	      			"catName"=>"L".$key,
	      			"catData"=>$catArrayInner2,
	      			//"catData1"=>$catArrayInner2,
	      			);
	      	} 
	      /*	foreach ($product as $key => $value) {
	      	    $catArrayInner = [];
	      		foreach ($product[$key] as $key1 => $value) {
	      			$catArrayInner[]=$value;
	      			# code...
	      		} 
	      		 $catArray[]=array(
	      			"catLevel"=>$key,
	      			"catName"=>"L".$key,
	      			"catData"=>$catArrayInner
	      			);
	      	}  
	       print_r($catArray);
	    }

	public function action6($cat = 0){
			
			// Product List Items
			// mobile: items_data_init_fetch[]
			// cat=0 means all categories

 			// return fields
 			$fields=['pro_product.id',
 					 'pro_product.price',
 					 'pro_category.name AS CategoryName',
 					 'pro_product.name AS ProductName'];
 			// Get All Categories Array
	        $product=PROController::GetAllElements($cat,$fields);
	     	
	     	$groups=[];
	     	// Get id's from products
		  	foreach ($product as $key => $catGroup) {
	     		foreach ($catGroup as $key1 => $value) {
	     			$groups[]=$value['id'];
	     		}
	     	}

	       	// groups - Search element match secondary-elements
 	        $product1=PROController::GetGroupsAndElements($groups);
	     	
	     	$product3=[];
	     	// put on each product his secondary element
	     	foreach ($product as $key => $element) {
	     		foreach ($element as $key1 => $value) {
	     			$product_id=$element[$key1]['id'];
	     			// check if product has secondary elements
	     			if(array_key_exists($product_id, $product1)){
						// add secondary elements to array
						$element[$key1]['elements']=$product1[$product_id];
	     			}
	    		 $product3[$key][]=$element[$key1];
	     		} 
	     	} 
	     	// groups - Search Single Product format and cost 
	     	$fields=['pro_product_unit_cost.name AS name',
	     			 'pro_product_unit.value AS format',
	     			 'pro_product_unit_2.value AS format2',
	     			 'pro_product_unit_3.value AS format3',
	     			 'pro_product_unit_cost.value AS price'];

	     	$product4=PROController::GetAllUnits($fields);
			
			// groups - Search Full Category format and cost
		  	$fields=['pro_product_unit_cost.name AS name',
	     			 'pro_product_unit.value AS format',
	     			 'pro_product_unit_2.value AS format2',
	     			 'pro_product_unit_3.value AS format3',
	     			 'pro_product_unit_cost.value AS factor'];
			$product5=PROController::GetAllUnitsCat($fields);
	      
			$product6=[];
	     	// put on each product his container
	     	foreach ($product3 as $key => $container) {
	     		foreach ($container as $key1 => $value) {
	     			$product_id=$container[$key1]['id'];
	     			$category_id=$container[$key1]['category'];
	     			$product_price=$container[$key1]['price'];
	     			// check if product has a container
	     			if(array_key_exists($product_id, $product4)){
						// add secondary elements to array
						$container[$key1]['format']=$product4[$product_id];
	     			} 
	     			// check if all product's category has a container
	     			elseif(array_key_exists($category_id,$product5)&&(!is_null($product_price))){
	     				// execute operation (x)
	     				$price = floatval($product_price);
	     				//$factor = floatval($product5[$category_id][0]);
	     				$key2 = 0;
	     				foreach ($product5[$category_id] as $key2 => $value2) {
	     					  // get factor value
	     					  $factor = floatval($product5[$category_id][$key2]['factor']);
	     					  // make operation and add price to array
	     					  $product5[$category_id][$key2]['price'] = strval($factor*$price);
	     				}
	     				// add secondary elements to array
						$container[$key1]['format']=$product5[$category_id];
	     			}
	     		 $product6[$key][]=$container[$key1];
	     		} 
	     	}

			 
print_r($product6); return;
 		  $result = $this->fetch_response([['items_data_init_fetch',$product6]]);
	      return $result;
	         
	}
*/
	/* Promo functions */

/*	Public function action7($cat){

 			// return fields
 			$fields=['mkr_promo_table.code AS promo_code',
 					 'mkr_promo_table.name AS promo_name',
 					 'mkr_promo_table.desc AS promo_desc',
 					 'mkr_promo_table.date_end AS promo_end',
 					 'mkr_promo_table.price AS promo_price',
 					 'mkr_promo_table.factor AS promo_factor'];
 			
 			// Get All Promo of products format
	        $promo_table=MKRController::GetPromo($fields);
	     	
	     	// Get All products

 			// return fields
 			$fields=['pro_product.id','price'];

 			// Get All Categories Array
	        $product=PROController::GetAllElements($cat,$fields);
	     	
	     	$groups=[];

	     	// Get id's from products
		  	foreach ($product as $key => $catGroup) {
	     		foreach ($catGroup as $key1 => $value) {
	     			$groups[]=$value['id'];
	     		}
	     	}
			$product3=[];
	     	// put on each product his secondary element
	     	foreach ($product as $key => $element) {
	     		foreach ($element as $key1 => $value) {
	     			$product_id=$element[$key1]['id'];
	     			$product3[$key][]=$element[$key1];
	     		} 
	     	}
	      	// groups - Search Single Product format and cost 
	     	$fields=['pro_product_unit_cost.value AS price'];

	     	$product4=PROController::GetAllUnits($fields);
			
			// groups - Search Full Category format and cost
		  	$fields=['pro_product_unit_cost.value AS factor'];
			$product5=PROController::GetAllUnitsCat($fields);
	      
			$product6=[];
	     	// put on each product his container
	     	foreach ($product3 as $key => $container) {
	     	 	 foreach ($container as $key1 => $value) {
	     			$product_id=$container[$key1]['id'];
	     			$category_id=$container[$key1]['category'];
	     		  	$product_price=$container[$key1]['price'];
	     			// check if product has a container
	     		 	if(array_key_exists($product_id, $product4)){
						// add secondary elements to array
						$container[$key1]['format']=$product4[$product_id];
	     			} 
	     			// check if all product's category has a container
	     			elseif(array_key_exists($category_id,$product5)&&(!is_null($product_price))){
	     				// execute operation (x)
	     				$price = floatval($product_price);
	     				//$factor = floatval($product5[$category_id][0]);
	     				$key2 = 0;
	     				foreach ($product5[$category_id] as $key2 => $value2) {
	     					  // get factor value
	     					  $factor = floatval($product5[$category_id][$key2]['factor']);
	     					  // make operation and add price to array
	     					  $product5[$category_id][$key2]['price'] = strval($factor*$price);
	     				}
	     				// add secondary elements to array
						$container[$key1]['format']=$product5[$category_id];
	     			}  
	     		 $product6[$key][]=$container[$key1];
	     		} 

	     	}
	      	// make the promo apply array
	      	$promo = [];
	      	// category
	      	foreach ($product6 as $key => $value) {
	      		$product_cat=$key;
	      		// single product
	      		foreach ($container as $key1 => $value1) {
	      			// formats
	      			foreach ($container[$key1]['format'] as $key2 => $value2) {
	      				$product_id_array=$container[$key1]['id'];
	      				// format
	      				foreach ($container[$key1]['format'][$key2] as $key3 => $value3) {
		      			 	// check if there a promo for this format_id
		      		 		$format_id=$container[$key1]['format'][$key2]['format_id'];
		      		 		// *** by now 1 promo for each format_id ***
		      		 		if(array_key_exists($format_id,$promo_table)){
		      		 			$old_price=$container[$key1]['format'][$key2]['price'];
		      			  		// set price->old_price
		      					$promo[$product_cat][$product_id_array][$key2]['old_price']=$old_price;
		      					$promo[$product_cat][$product_id_array][$key2]['format_id']=$format_id;
		      		 			$promo[$product_cat][$product_id_array][$key2]['promo_name']=$promo_table[$format_id]['promo_name'];
		      		 	  		$promo[$product_cat][$product_id_array][$key2]['promo_desc']=$promo_table[$format_id]['promo_desc'];
		      		 	  		// only data su end
		      		 	  		$end_promo = explode(" ",$promo_table[$format_id]['promo_end']);
		     					$promo[$product_cat][$product_id_array][$key2]['promo_end']=$end_promo[0];
		     					// check if fix price or factor
		     					if($promo_table[$format_id]['promo_price']!=0){
		     						// fix
		     						$new_price=$promo_table[$format_id]['promo_price'];
		     					} else {
		     						// factor
		     						// execute operation (x)
	     							$old_price_f= floatval($old_price); 
	     							// get factor value
	     					  		$factor_f = floatval($promo_table[$format_id]['promo_factor']);
	     							$new_price=strval($factor*$old_price_f);
	     						 }
		     				 	$promo[$product_cat][$product_id_array][$key2]['new_price']=$new_price;
		     
		      		 		}
		      		 	}
	      		 	}
	      		}

	      	}
print_r($promo);
	     // 	return $promo; 

	}
*/
	public function actionTest(){
 
		 $result=MPCController::setJSONcacheMobile('items_data_init');
		 $result=PROController::ProductList(23);
		print_r($result);
		echo json_encode($result);

	}
    public function actionIndex()
    {
        return $this->render('index');
    }



	/* Common Functions */

	/* fetch response */
	public function MobileFetchResponse($fetch_response)
	{
		// fetch_response: [[varname,vardata],[varname2,vardata2]]
		//
		// response:{ var_name, var_data }
		
		$result = [];

	 	foreach ($fetch_response as $key => $value) {
	 		  $result_item = []; 
			  foreach ($fetch_response[$key] as $key1 => $value1) {
			   
			   	 if($key1==0){
			   	  	$result_item['fetch_response_var'] = $value1;
			  	 } else {
			  	 	$result_item['fetch_response_data'] = $value1;
			  	 }
			  	 
			  }
			  $result[]=$result_item;
		} 
	
		return $result;
	}
	 
	
}