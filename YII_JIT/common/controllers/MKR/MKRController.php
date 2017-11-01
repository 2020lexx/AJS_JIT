<?php
/**
 *
 * Copyright (c) 2017. @pablo
 *
 * Test Code
 */

namespace common\controllers\MKR;


use common\controllers\PRO\PROController; 
use common\controllers\MPC\MPCController; 
use common\controllers\CSM\CSMController; 

use Yii;

use common\models\MKR\Checkout; 
use common\models\MKR\CheckoutItems;
use common\models\MKR\CheckoutStatus;   
use common\models\MKR\PromoCategories;   
use common\models\MKR\PromoList;  
use common\models\MKR\PromoListProd;  
use common\models\MKR\PromoSingleProd;  
use common\models\MKR\PromoStatus;   

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MKRController for MKR model.
 */
class MKRController extends \yii\web\Controller
{
    
	
	/* Promo functions */

	// Promo single product
 
	function promoSingleProduct(){

		// return fields
 		$fields=['code AS code',
 				 'name AS name',
 				 'product_id AS id',
 				 'product_format_cost_id AS format_id', 
 				 'product_category_id AS category_id',
 				 'desc AS desc',
 				 'date_end',
 				 'image',
 				 'price',
 				 'price_reduction'];



		$rows = (new \yii\db\Query())
            ->select($fields)
            ->from('mkr_promo_single_prod')
            ->where(['status'=>'1'])
            ->all();  

        // group by cat
        $result=array();
        foreach ($rows as $key => $value) {
              $result[]=$value;
        }

        return($result);

	}

	// Promo Categories
 
	function promoCategories(){

		// return fields
 			$fields=['code AS code',
 				 'name AS name',
 				 'category_id AS category',
 				 'product_format_cost_id AS format_id', 
 				 'desc AS desc',
 				 'image',
 				 'date_end',
 				 'price_reduction'];


		$rows = (new \yii\db\Query())
            ->select($fields)
            ->from('mkr_promo_categories')
            ->where(['status'=>'1'])
            ->all();  

        // group by cat
        $result=array();
        foreach ($rows as $key => $value) {
              $result[]=$value;
        }

        return($result);

	} 

	// Promo List

	function promoList(){

// return fields
 			$fields=['code',
 				 'name',
 				 'desc',
 				 'image',
 				 'date_end',
 				 'price_total',
 				 'id AS list_id',
 				 ];
			$fields1=['product_id AS id',
 				 'product_format_cost_id AS format_id',
 				 'units',
 				 'promo_list_id AS list_id'
 				 ];

		$rows = (new \yii\db\Query())
            ->select($fields)
            ->from('mkr_promo_list')
            ->where(['status'=>'1'])
            ->all();  

		$rows1 = (new \yii\db\Query())
            ->select($fields1)
            ->from('mkr_promo_list_prod')
            ->all();  

        // group by cat
        $result=array();
        $result2=array();

        foreach ($rows1 as $key1 => $value1) {
         	$result2[$value1['list_id']][]=$value1;
          } 
       foreach ($rows as $key  => $value ) {
        	$rows[$key]['list_prod']=$result2[$rows[$key]['list_id']];
        	 }
         
		return($rows);


	} 

	// Checkout data full
	
	function checkoutData($checkout_id=0){
			
 			// checkout_id=0 get all
       	   if($checkout_id=='0'){
		   		$where_cmd=['like','checkout_id',''];
		   } else {
		   		$where_cmd=['checkout_id' => $checkout_id];
		   } 

			// return fields
 			
 			$fields=['checkout_id',
	 				 'preferred_delivery_time',
	 				 'total_invoice',
	 				 'realtime_coords',
	 				 'name AS status',
	 				 'local_id',
	 				 ]; 
			$fields1=['checkout_id',
	 				  'product_id',
	 				 'product_unit_cost_id AS format_id',
	 				 'items_number' 
	 				 ]; 
			$rows = (new \yii\db\Query())
	            ->select($fields)
	            ->from('mkr_checkout')
	            ->join('INNER JOIN','mkr_status','mkr_checkout.status = mkr_status.id')
	            ->where($where_cmd)
	            ->all();  
			$rows1 = (new \yii\db\Query())
	            ->select($fields1)
	            ->from('mkr_checkout_items')
	            ->where($where_cmd)
	            ->all();  

	        // group by id
	        $result=array(); 
	        $result1=array();
	        $product_item=array();
	        $product_full=array();
	        // group by checkout_id
            foreach ($rows1 as $key  => $value ) {
	        	$result1[$value['checkout_id']][]=$value;
	        	$product_item[]=$rows1[$key]['product_id'];
	        	// make an array with [checkout_id]{[product_id]->format_id}
	        	$product_format[$rows1[$key]['checkout_id']][$rows1[$key]['product_id']]=$rows1[$key]['format_id'];
	        	 } 
	        // get product_id and remove duplicates then get json product cache 
			$product_cache=MPCController::getJSONcacheProduct(array_unique($product_item));
			
			foreach ($product_cache as $key => $value) {
				  $product_full[$product_cache[$key]['product_id']]=json_decode($product_cache[$key]['data'],true);
				 }
 			// remove unset format
	        foreach ($result1 as $key => $value) {
	        	// loop over items
	        	foreach ($result1[$key] as $key1 => $value1){
	        		// cancel unused format
	        		foreach ($product_full[$result1[$key][$key1]['product_id']]['format'] as $key2 => $value2) {
	        			 //++ echo ">".$product_full[$result1[$key][$key1]['product_id']]['format'][$key2]['format_id']."---".$result1[$key][$key1]['format_id'];
	        			if($product_full[$result1[$key][$key1]['product_id']]['format'][$key2]['format_id'] == $result1[$key][$key1]['format_id']){
	        				//++$product_full[$result1[$key][$key1]['product_id']]['format'][$key2]['name']="xxx";
	        				$product_format_data = $product_full[$result1[$key][$key1]['product_id']]['format'][$key2];
	        		 		}
	        		}
	        		// add new format_d key
	        		$product_full[$result1[$key][$key1]['product_id']]['format_d']=$product_format_data;
	        		// add item 
	        		$result1[$key][$key1]['item']=$product_full[$result1[$key][$key1]['product_id']];
	        		// remove unset format element
	        		unset($result1[$key][$key1]['item']['format']);
	        		// remove unused keys
	        		unset($result1[$key][$key1]['item']['promo']['category']);
	        		unset($result1[$key][$key1]['item']['promo']['category_id']);
	        		unset($result1[$key][$key1]['item']['promo']['id']);
	        		unset($result1[$key][$key1]['item']['promo']['price']);
	        		unset($result1[$key][$key1]['item']['promo']['old_price']);
	        		unset($result1[$key][$key1]['item']['promo']['format_id']);
	        	    unset($result1[$key][$key1]['item']['promo']['desc']);
	        	    unset($result1[$key][$key1]['item']['promo']['image']);
	        	    unset($result1[$key][$key1]['item']['promo']['price_reduction']);
	        	    unset($result1[$key][$key1]['item']['category']);
	        		unset($result1[$key][$key1]['item']['price']);
	        		unset($result1[$key][$key1]['item']['id']);
	        	 	unset($result1[$key][$key1]['checkout_id']);
	        	 	unset($result1[$key][$key1]['format_id']); 
				}
	        }
	    
	    foreach ($rows as $key  => $value ) {
	        	$result[$value['checkout_id']]=$value;
	        	// add customer data
	        	$user_data=CSMController::CustomerLocal('',$result[$value['checkout_id']]['local_id']);
	        	$result[$value['checkout_id']]['user_data']=$user_data['0'];
	        	$result[$value['checkout_id']]['items']=$result1[$value['checkout_id']];
	        		 
	        	 }
 
         	
		return($result);
	}

	// Checkout data Brief
	
	function checkoutDataBrief($task_id=0){
			
 			// task_id=0 get all
       	   if($task_id=='0'){
		   		$where_cmd=['like','checkout_id',''];
		   } else {
		   		$where_cmd=['checkout_id' => $task_id];
		   } 

			// return fields
 			
 			$fields=['checkout_id AS task_id',
 			 		 'SUBSTRING(checkout_id,9,3) AS task_id_view',
	 				 'TIME_FORMAT(preferred_delivery_time,"%H:%i") AS preferred_delivery_time ',
	 				 'total_invoice',
	 				 'realtime_coords',
	 				 'name AS status',
	 				 'local_id',
	 				 'mkr_checkout.timestamp AS timestamp'
	 				 ]; 
			$fields1=['checkout_id AS task_id',
	 				  'product_id',
	 				  'item_id',
	 				 'product_unit_cost_id AS format_id',
	 				 'items_number' 
	 				 ]; 
			$rows = (new \yii\db\Query())
	            ->select($fields)
	            ->from('mkr_checkout')
	            ->join('INNER JOIN','mkr_status','mkr_checkout.status = mkr_status.id')
	            ->where($where_cmd)
	            ->all();  
			$rows1 = (new \yii\db\Query())
	            ->select($fields1)
	            ->from('mkr_checkout_items')
	            ->where($where_cmd)
	            ->all();  

	        // group by id
	        $result=array(); 
	        $result1=array();
	        $product_item=array();
	        $product_full=array();
	        $brief_data='';
	        // group by task_id
            foreach ($rows1 as $key  => $value ) {
	        	$result1[$value['task_id']][]=$value;
	        	$product_item[]=$rows1[$key]['product_id'];
	        	// make an array with [task_id]{[product_id]->format_id}
	        	$product_format[$rows1[$key]['task_id']][$rows1[$key]['product_id']]=$rows1[$key]['format_id'];
	        	 } 
	        // get product_id and remove duplicates then get json product cache 
			$product_cache=MPCController::getJSONcacheProduct(array_unique($product_item));
			
			foreach ($product_cache as $key => $value) {
				  $product_full[$product_cache[$key]['product_id']]=json_decode($product_cache[$key]['data'],true);
				 }
 			// remove unset format
	        foreach ($result1 as $key => $value) {
	        	// loop over items
	        	foreach ($result1[$key] as $key1 => $value1){
	        		// cancel unused format
	        		foreach ($product_full[$result1[$key][$key1]['product_id']]['format'] as $key2 => $value2) {
	        			 //++ echo ">".$product_full[$result1[$key][$key1]['product_id']]['format'][$key2]['format_id']."---".$result1[$key][$key1]['format_id'];
	        			if($product_full[$result1[$key][$key1]['product_id']]['format'][$key2]['format_id'] == $result1[$key][$key1]['format_id']){
	        				//++$product_full[$result1[$key][$key1]['product_id']]['format'][$key2]['name']="xxx";
	        				$product_format_data = $product_full[$result1[$key][$key1]['product_id']]['format'][$key2];
	        		 		}
	        		}
	        		// add new format_d key
	        		$product_full[$result1[$key][$key1]['product_id']]['format_d']=$product_format_data;
	        		// add item 
	        		$result1[$key][$key1]['item']=$product_full[$result1[$key][$key1]['product_id']];
	        		// remove unset format element
	        		unset($result1[$key][$key1]['item']['format']);
	        		unset($result1[$key][$key1]['item']['promo']);
	        		// remove unused keys
	        		unset($result1[$key][$key1]['item']['category']);
	        		unset($result1[$key][$key1]['item']['price']);
	        		unset($result1[$key][$key1]['item']['id']);
	        	 	unset($result1[$key][$key1]['task_id']);
	        	 	unset($result1[$key][$key1]['format_id']); 
	       	 		unset($result1[$key][$key1]['format_d']['factor']); 
	       	 		unset($result1[$key][$key1]['format_d']['category_id']); 
	       	 		unset($result1[$key][$key1]['format_d']['format_id']); 
	       	 		// add product name to brief data
	       	 		$brief_data=' '.$result1[$key][$key1]['items_number'].': '.$result1[$key][$key1]['item']['ProductName'].','.$brief_data;
	       	 		
				}
				// add item 
	        	$result1[$key]['brief_data']=$brief_data;
	        	$brief_data='';
	        }
	    
	    foreach ($rows as $key  => $value ) {
	        	$result[$value['task_id']]=$value;
	        	// add customer data
	        	$user_data=CSMController::CustomerLocal('',$result[$value['task_id']]['local_id']);
	        	$result[$value['task_id']]['user_data']=$user_data['0'];
	        	// add brief addr
	        	$result[$value['task_id']]['brief_data']['addr']=$user_data[0]['street_name'].' '.$user_data[0]['street_number'];
	        	// add brief items
	       		$result[$value['task_id']]['brief_data']['items']=$result1[$value['task_id']]['brief_data'];
	       		// del temp array
	       		unset($result1[$value['task_id']]['brief_data']);
	        	// add distance e time routing from shop to customer
	        	$routing_data = (new \yii\db\Query())
					            ->select('shop_distance_route,shop_time_route')
					            ->from('altlp_delivery_waypoints')
					            ->where('task_id = '.$value['task_id'])
					            ->all();
				$result[$value['task_id']]['shop_distance_route'] = $routing_data[0]['shop_distance_route'];	       
				$result[$value['task_id']]['shop_time_route'] = $routing_data[0]['shop_time_route'];	       
			    // add items
			    $result[$value['task_id']]['items']=$result1[$value['task_id']];
	            		 
	        	 }
 
         	
		return($result);
	}

	// get timestamp fata of orders

	public function getTimestampOfOrders(){

			$rows = (new \yii\db\Query())
	            ->select('checkout_id AS task_id, timestamp')
	            ->from('mkr_checkout')
	            ->all();  

	        return $rows;
	
	}
/* DISCONT 

	// Promo list by cat ID -product ID sub array


	Public function PromoList($cat){

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

	     return $promo; 

	}
*/
	/* Checkout insert */

	public function checkoutInsert($data){

		// data = []

		 // new insert
        $Checkout_insert = new MkrCheckout();
        $Checkout_insert->prefered_delivery_time = $data['prefered_delivery_time'];
        $Checkout_insert->customer_id= $data['customer_id'];
        $Checkout_insert->customer_address_id = $data['customer_address_id'];
        $Checkout_insert->total_invoce = $data['total_invoce']; 
        if ($Checkout_insert->insert() == false) {
             // 1st insert error
             return false;
            }
        
        // inserted id
        $checkout_id = Yii::app()->db->getLastInsertID();

        foreach ($data['items'] as $key => $value) {
		     
		 	// new insert
	        $CheckoutItems_insert = new MkrCheckoutItems();
	        $CheckoutItems_insert->checkout_id = $checkout_id;
	     	$CheckoutItems_insert->product_it = $items[$key]['product_it'];
	        $CheckoutItems_insert->product_format_cost = $items[$key]['product_format_cost'] ;
	        $CheckoutItems_insert->items_number = $items[$key]['items_number'];
	        $CheckoutItems_insert->items_total_cost = $items[$key]['items_total_cost']; 
	        if ($CheckoutItems_insert->insert() == false) {
	             // 2st insert error
	             return false;
	            }

			}

	}

	/* Low Level */

	public function getPromo($fields){
 
        // status = active 
        array_push($fields,'pro_product_format_cost.id AS format_id');

		   $rows = (new \yii\db\Query())
            ->select($fields)
            ->from('mkr_promo_apply','','')
            ->join('INNER JOIN','mkr_promo_table','mkr_promo_apply.promo_id = mkr_promo_table.id')
            ->join('INNER JOIN','pro_product_format_cost','mkr_promo_apply.product_format_cost_id = pro_product_format_cost.id')  
            ->where(['mkr_promo_table.status'=>'1'])
           ->all();  
        // group by cat
        $result=array();
        foreach ($rows as $key => $value) {
              $result[$value['format_id']]=$value;
        }
        return($result);
     
	}


    public function actionIndex()
    {
        return $this->render('index');
    }

}
