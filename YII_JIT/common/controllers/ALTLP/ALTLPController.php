<?php
/**
 *
 * Copyright (c) 2017. @pablo
 *
 * Test Code
 */

namespace common\controllers\ALTLP;

use Yii;

use common\models\ALTLP\AltlpQueueHistory;
use common\models\ALTLP\AltlpQueueSet;
use common\models\ALTLP\AltlpQueueFfs;
use common\models\ALTLP\AltlpQueueStatus;
use common\models\ALTLP\AltlpQueueType;
use common\models\ALTLP\AltlpQueueView;
use common\models\ALTLP\AltlpDelivery;
use common\models\ALTLP\AltlpDeliveryStatus;
use common\models\ALTLP\AltlpDeliveryTransport;
use common\models\ALTLP\AltlpDeliveryWaypoints;

use common\controllers\ESI\ESIController;
use common\controllers\MKR\MKRController;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ALTLPController extends \yii\web\Controller
{
 
	// get queue views
	public function queueStatus(){

    echo "queueStatus is discontinued"; 
    return;
	
    /*// return fields
 		$fields=['altlp_queue_view.task_id AS task_id',
                 'SUBSTRING(altlp_queue_view.task_id,9,3) AS task_id_view',
 				 'altlp_queue_set.queue_name AS queue_name',
 				 'TIME_FORMAT(stimated_time_to_delivery,"%H:%i") AS stimated_ttd',
 				 'task_start',
                 'TIME_FORMAT(TIMEDIFF(altlp_queue_view.stimated_time_to_delivery,mkr_checkout.preferred_delivery_time),"%H:%i") as delay_ttd',
                 'altlp_queue_view.timestamp AS timestamp'
 				 ];

		$rows = (new \yii\db\Query())
            ->select($fields)
            ->from('altlp_queue_view')
            ->join('INNER JOIN','altlp_queue_set','altlp_queue_view.queue_set_id = altlp_queue_set.id')
            ->join('INNER JOIN','mkr_checkout','altlp_queue_view.task_id = mkr_checkout.checkout_id')
            ->all();  

        // group by cat
        $result=array();
        foreach ($rows as $key => $value) {
        	$result[$value['queue_name']][]=$value; 
             
        }

        return($result);
*/
	}

    // get queue views
    public function queueBriefStatus(){

    // return fields
        $fields=['altlp_queue_view.task_id AS task_id',
                 'SUBSTRING(altlp_queue_view.task_id,9,3) AS task_id_view',
                 'SUBSTRING(altlp_queue_view.task_id,12,3) AS item_id_view',
                 'altlp_queue_set.queue_name AS queue_name',
                 'TIME_FORMAT(stimated_time_to_delivery,"%H:%i") AS stimated_ttd',
                 'task_start',
                 'TIME_FORMAT(TIMEDIFF(altlp_queue_view.stimated_time_to_delivery,mkr_checkout.preferred_delivery_time),"%H:%i") as delay_ttd',
                 'altlp_queue_view.timestamp AS timestamp'
                 ];

        $rows = (new \yii\db\Query())
            ->select($fields)
            ->from('altlp_queue_view')
            ->join('INNER JOIN','altlp_queue_set','altlp_queue_view.queue_set_id = altlp_queue_set.id')
            ->join('INNER JOIN','mkr_checkout','altlp_queue_view.group_task_id = mkr_checkout.checkout_id')
            ->all();  

       
        return($rows);

    }
     // get routing, process data and send to client's leaflet library
    public function getRoutingMap(){

        // get id_s
        $rows = (new \yii\db\Query())
            ->select('altlp_delivery_id')
            ->from('altlp_delivery_waypoints') 
            ->all();

        $routing_data = [];

        // get single route and save on cache
        foreach ($rows as $key => $value) {
             $routing_data[$value['altlp_delivery_id']] = ALTLPController::getSingleRoutingMap($value['altlp_delivery_id']);
        }

        return $routing_data;
    }

    // get routing for 1 delivery_id , process data and send to client's leaflet library
    public function getSingleRoutingMap($delivery_id){

        // get coords of waypoints
        $fields = ['coords','group_task_id'];

        $rows = (new \yii\db\Query())
            ->select($fields)
            ->from('altlp_delivery_waypoints')
            ->where('altlp_delivery_id = '.$delivery_id)
            ->all();

        // add home-shop coords +++++++++++++++++++++++++++++++++++++++ ADD SHOP COORDS +++++++++++++
        array_unshift($rows,array('coords'=>'44.999618,11.296847'));
   
        // get osrm data
        $response = ESIController::osrmRouting($rows);
       
        $result = array();
 
        if($response['code']!='Ok'){ echo "OSRM Request Error: ".$response['code']; return;}
         
        // process data
        // route summary
        $result['distance'] = ALTLPController::mts_to_km($response['trips'][0]['distance']);
        $result['duration'] = ALTLPController::sec_to_min($response['trips'][0]['duration']);
        $travel_waypoints_array = $response['trips'][0]['legs'];
        // order waypoints by index
        foreach ($response['waypoints'] as $key => $value) {
            $wayp_coord[$value['waypoint_index']]=$value['location'][1].','.$value['location'][0];
        }
        // waypoints route data
        $index = 0;
        foreach ( $travel_waypoints_array as $key => $value) {
            $wayp['distance'] = ALTLPController::mts_to_km($value['distance']);
            $wayp['duration'] = ALTLPController::sec_to_min($value['duration']);
            $geometry=[];
            // get polygonal
            foreach ($value['steps'] as $key1 => $value1) {
                $geometry[] = $value1['geometry'];
            }
            // marker coords
            $wayp['coords'] = $wayp_coord[$index];
            $index++;
            $wayp['geometry'] = $geometry;
            // get user data to make marker
            // get task_id from delivery_id
            $task_id=0;
            foreach ($rows as $key2 => $value2) { 
                $latlng_arr=split(',',$value2['coords']);
                $latlng_wp=split(',',$wayp['coords']);
                // the coords of the osrm response is NOT the same as the requested coord of marker
                // so we use this function to check the distance between this makers
                // if is < 0.09 is matched 
               if(((((acos(sin(($latlng_arr[0]*pi()/180)) * 
                    sin(( $latlng_wp[0] *pi()/180))+cos(($latlng_arr[0]*pi()/180)) * 
                    cos(( $latlng_wp[0]*pi()/180)) * cos((($latlng_arr[1] - $latlng_wp[1])* 
                    pi()/180))))*180/pi())*60*1.1515 ) * 1.609344)<0.09){
                    // if there's not task_id the maker is Home
                    if (!array_key_exists('group_task_id',$value2)){
                           $marker_wp['name'] ='Our Shop';
                      } else {
                        $group_task_id = $value2['group_task_id'];
                        // get User data
                        $user_data = MKRController::checkoutDataBrief($group_task_id);
                        $marker_wp['name'] = $user_data[$task_id]['user_data']['first_name'].' '.$user_data[$group_task_id]['user_data']['last_name'];
                        $marker_wp['addr'] = $user_data[$task_id]['user_data']['street_name'].' '.$user_data[$group_task_id]['user_data']['street_number'];

                      }
                    break;
               }
            } 
       
            $wayp['user_data'] = $marker_wp;
            $result['waypoints'][] = $wayp;
        }

        return $result;

    }

    // set shop to customer distance / time
    public function shopToCustomer($group_task_id){

        $fields = ['coords'];

        $rows = (new \yii\db\Query())
            ->select($fields)
            ->from('altlp_delivery_waypoints')
            ->where('group_task_id = '.$group_task_id)
            ->all();
        
        // add home-shop coords +++++++++++++++++++++++++++++++++++++++ ADD SHOP COORDS +++++++++++++
        $data['home'] = '44.999618,11.296847';
        $data['customer'] = $rows[0]['coords'];
       
        // get osrm data
        $response = ESIController::osrmSingleRoute($data);
        

        if($response['code']!='Ok'){ echo "OSRM Request Error: ".$response['code']; return;}
     
        // update table
        Yii::$app->db->createCommand()
                     ->update('altlp_delivery_waypoints', [ 
                         'shop_distance_route' => $response['shop_distance_route'],
                        'shop_time_route' => $response['shop_time_route']
                       ], 
                        'group_task_id = '.$group_task_id)
                     ->execute();

    /// ++ print_r($response);
   
    
    }

    /* ALTLP Planning */
    /* setting 1st stage */
    public function setPlanning($group_task_id,$task_start=0){

        // if not start time supplied, set now()
        $task_start=($task_start==0)?ALTLPController::now_time():$task_start;
       
        // get data from MKR
        $fields = ['realtime_coords','preferred_delivery_time'];

        $mkr_group_task_id = (new \yii\db\Query())
            ->select($fields)
            ->from('mkr_checkout')
            ->where('checkout_id = '.$group_task_id)
            ->all();
      
         // ++ delivery_waypoints
        $DeliveryWaypoints_insert = new AltlpDeliveryWaypoints();
        $DeliveryWaypoints_insert->group_task_id = $group_task_id;
        $DeliveryWaypoints_insert->altlp_delivery_id = '1'; // ++++ delivery ID
        $DeliveryWaypoints_insert->coords = $mkr_group_task_id[0]['realtime_coords']; // real coords
        $DeliveryWaypoints_insert->status = '4'; // status is 'first setting'
   
        if ($DeliveryWaypoints_insert->insert() === false) {
            var_dump($DeliveryWaypoints_insert->getErrors());
            return "ALTLPController: DeliveryWaypoints_insert error";
             }  
 
       // get items from group_task_it
        $fields = ['item_id','items_number','product_id'];

        $mkr_item_id = (new \yii\db\Query())
            ->select($fields)
            ->from('mkr_checkout_items')
            ->where('checkout_id = '.$group_task_id)
            ->all();

        // group by items [A-B-C...]
        $item_group = array();

        foreach ($mkr_item_id as $key => $value) {
            $item_group[$value['item_id']] = $value;
          
        }
      
        // get items list and set each task with: [group_task_id][item_id][items_number]
        $fields_prd = ['item_id','items_number',
                 'altlp_prd_task_length.queue_set_id AS prd_queue_set_id',
                 'altlp_prd_task_length.manufacturing_type AS prd_manufacturing_type',
                 'altlp_prd_task_length.task_length AS prd_task_length',
                 'altlp_prd_task_length.task_interval AS prd_task_interval',
                 'queue_insert_on_group_end'];
     
       $fields_cat = ['item_id','items_number',
                  'altlp_cat_task_length.queue_set_id AS cat_queue_set_id',
                 'altlp_cat_task_length.manufacturing_type AS cat_manufacturing_type',
                 'altlp_cat_task_length.task_length AS cat_task_length',
                 'altlp_cat_task_length.task_interval AS cat_task_interval',
                 'queue_insert_on_group_end'];
        
        $queue_custom_task = array();
         // check custom task sets by item_id
        foreach ($item_group as $key => $value) {
           
          // get data from MKR_items and custom task_length for single product
            $mkr_prd_id = (new \yii\db\Query())
                ->select($fields_prd)
                ->from('mkr_checkout_items')
                ->where('checkout_id = '.$group_task_id.' && item_id = "'.$key.'"')
                ->join('INNER JOIN','pro_product','mkr_checkout_items.product_id = pro_product.id')
                ->join('INNER JOIN','altlp_prd_task_length','mkr_checkout_items.product_id = altlp_prd_task_length.product_id')
                ->all();
             // use query_id as key   
            $mkr_prd = array();   
            foreach ($mkr_prd_id as $keyp => $valuep) {
                 $mkr_prd[$valuep['prd_queue_set_id']] = $valuep;
            }
         
            // get data from MKR_items and custom task_length for single product
            $mkr_cat_id = (new \yii\db\Query())
                ->select($fields_cat)
                ->from('mkr_checkout_items')
                ->where('checkout_id = '.$group_task_id.' && item_id = "'.$key.'"')
                ->join('INNER JOIN','pro_product','mkr_checkout_items.product_id = pro_product.id')
                ->join('INNER JOIN','altlp_cat_task_length','pro_product.category = altlp_cat_task_length.pro_category_id')
                ->all();
            // use query_id as key   
            $mkr_cat = array();   
            foreach ($mkr_cat_id as $keyc => $valuec) {
                $mkr_cat[$valuec['cat_queue_set_id']] = $valuec;
            }
             // go trought items_number
           for($items_number=1;$items_number<=$value['items_number'];++$items_number){
                // create task_id (must be unique)
                $task_id = $group_task_id.$value['item_id'].$items_number;

                // create an array for each task_id = $group_task_id.$value['item_id'].$items_number
                $queue_custom_task[$task_id] = array();

                // add custom_task
                if(!empty($mkr_prd)){
                     $queue_custom_task[$task_id]['prd'] = $mkr_prd;
                }
                if(!empty($mkr_cat)){
                     $queue_custom_task[$task_id]['cat'] = $mkr_cat;
                }
           
           
             }  
         
         }
 
         
        // get queue set (task must be inserted allways on wait queues)
     
        $fields = ['queue_id','queue_length','parallel_tasks','task_length','task_interval'];

        $queue_set_rows = (new \yii\db\Query())
            ->select($fields)
            ->from('altlp_queue_set')
            ->where('queue_name LIKE "%W%"')
            ->orderBy('ABS(queue_id)')  
            ->all();

        // group by queue id
        $queue_set = array();
        foreach ($queue_set_rows as $keyq => $valueq) {
           $queue_set[$valueq['queue_id']] = $valueq;
          // set JIT as default manufacturing_type
         $queue_set[$valueq['queue_id']]['manufacturing_type'] = '1';
        }
             
        // process queue_custom_task with standard and custom data
        // [task_id][prd][q0][q1]
        //          [cat][q0][q1]
        // result
        // [task_id][q0][--]
        //          [q1][--]

        // 
        $task_queue_to_set = array();
        $queue_manufacturing_sto = array();

        foreach ($queue_custom_task as $key => $value) {
             // empty arrays
            $queue_set_cat = array();
            $queue_set_prd = array();
            $queue_manufacturing_sto_tmp = array();
             // default - put default task_lenght and task_interval
             $task_queue_to_set[$key] = $queue_set;

             // 2do level - check if there's a custom cat task_*
            if(array_key_exists('cat',$value)){
                $queue_set_cat = $queue_set;
                $insert_element = true;
                // replace on each queue_id the customs values
                foreach ($value['cat'] as $keycat => $valuecat) {
                    $queue_set_cat[$keycat]['task_length'] = $valuecat['cat_task_length'];
                    $queue_set_cat[$keycat]['task_interval'] = $valuecat['cat_task_interval'];
                    $queue_set_cat[$keycat]['manufacturing_type'] = $valuecat['cat_manufacturing_type'];
                          // check if manufacturing_type = 2 so product is on storage
                    if($valuecat['cat_manufacturing_type']=='2'){
                      // the product must insert on the queue when the last item arrives
                      // no queue reservation is needed
                      $queue_manufacturing_sto_tmp['queue_id'] = $keycat;
                      $queue_manufacturing_sto_tmp['manufacturing_type'] = '2';
                      $queue_manufacturing_sto_tmp['task_group'] = substr($key,0,-2).'%';
                      $queue_manufacturing_sto_tmp['queue_insert_on_group_end'] = $valuecat['queue_insert_on_group_end'];
                      $queue_manufacturing_sto[$key] = $queue_manufacturing_sto_tmp;
                      $insert_element = false;
                      break;
                     }
                 }
              // if queue_manufacturing is 2, the element must be insert at the end of array
              if($insert_element){
                  $task_queue_to_set[$key] = $queue_set_cat; 
               }
            }
 
             // 1st level - check if there's a custom prd task_*
            if(array_key_exists('prd',$value)){
                $queue_set_prd = (empty($queue_set_cat))?$queue_set:$queue_set_cat;
                $insert_element = true;
                // replace on each queue_id the customs values
                foreach ($value['prd'] as $keyprd => $valueprd) {
                    $queue_set_prd[$keyprd]['task_length'] = $valueprd['prd_task_length'];
                    $queue_set_prd[$keyprd]['task_interval'] = $valueprd['prd_task_interval'];
                    $queue_set_prd[$keyprd]['manufacturing_type'] = $valueprd['prd_manufacturing_type'];
                     // check if manufacturing_type = 2 so product is on storage
                    if($valuecat['cat_manufacturing_type']=='2'){
                      // the product must insert on the queue when the last item arrives
                      // no queue reservation is needed
                      $queue_manufacturing_sto_tmp['queue_id'] = $keycat;
                      $queue_manufacturing_sto_tmp['manufacturing_type'] = '2';
                      $queue_manufacturing_sto_tmp['task_group'] = substr($key,0,-2);
                      $queue_manufacturing_sto_tmp['queue_insert_on_group_end'] = $valueprd['queue_insert_on_group_end'];
                      $queue_manufacturing_sto[$key] = $queue_manufacturing_sto_tmp;
                      $insert_element = false;
                      break;
                    }
   
                 }
              // if queue_manufacturing is 2, the element must be insert at the end of array
              if($insert_element){
                  // no insert
                  $task_queue_to_set[$key] = $queue_set_prd;   
               }  
             }

        }
        
        // if task_queue_to_set is empty set with default queue_set
        $task_queue_to_set = (empty($task_queue_to_set))?$queue_set:$task_queue_to_set; 
                // create 

                // check for product manufacturing type 
                   /*    $prd_manufacturing_type = $value['prd_manufacturing_type'];
                // check if the custom set is by prd
                if($prd_manufacturing_type){
                    // got prd data
                    if($prd_manufacturing_type == '2'){
                        // prod made - set the queue where made prd must be inserted
                        $queue_insert = $value['prd_queue_set_id'];
                    } else {
                        // prod jit
                        $queue_custom_task[$task_id][$value['prd_queue_set_id']]['task_length'] = $value['prd_task_length']; 
                        $queue_custom_task[$task_id][$value['prd_queue_set_id']]['task_interval'] = $value['prd_task_interval']; 
                    }
                } else {
                    echo "cat";
                     // set by cat 
                    if($value['cat_manufacturing_type']== '2'){
                        // cat made - set the queue where made prd must be inserted
                        $queue_insert = $value['cat_queue_set_id'];
                    } else {
                        // prod jit
                        $queue_custom_task[$task_id][$value['cat_queue_set_id']]['task_length'] = $value['cat_task_length']; 
                        $queue_custom_task[$task_id][$value['cat_queue_set_id']]['task_interval'] = $value['cat_task_interval']; 
                    }
                }
             //  echo $group_task_id.$value['item_id'].$items_number.'<br>';
*/
          
            //print_r($value);
        


                
        
       
/*       $queue_group = array(); 
       // group by queue_set_id
        foreach ($mkr_item_id as $key => $value) {
            // check if prd or cat set
            if($value['prd_queue_set_id']){ 
                $queue_group[$value['prd_queue_set_id']] = $value;
            } else {
                $queue_group[$value['cat_queue_set_id']] = $value;
  
            }  
        }

            print_r($mkr_item_id);
             return;
   */
        
        // process manufacturing = sto
      if(!empty($queue_manufacturing_sto)){
            foreach ($queue_manufacturing_sto as $keyqms => $valueqms) {
               // cancel standard element inserted  
               unset($task_queue_to_set[$keyqms]);  
              // insert at the end of array
   //           $task_queue_to_set[$keyqms] = $queue_manufacturing_sto[$keyqms];
            }
        }
      /*  print_r($task_queue_to_set);print_r($queue_manufacturing_sto);
         return; 
       */   // insert all the singles task of group task
        foreach ($task_queue_to_set as $keyst => $valuest) {
            
            // process the single task
            // by the way sort by queue id
            ksort($valuest);
            // set pointer to 1st element
            reset($valuest);
            // get the first queue data
            $first_element = current($valuest);
            // taskid
            $task_id = $keyst;
         
            // new insert initial state
            $QueueView_insert = new AltlpQueueView();
            $QueueView_insert->task_id = $task_id;    // task_id
            $QueueView_insert->group_task_id = $group_task_id; // group_task_id of this task_id
            $QueueView_insert->queue_set_id = ALTLPController::starting_queue(); // initial queue to start process
            $QueueView_insert->task_start = $task_start; // initial queue start with this insert
            $QueueView_insert->task_length = $first_element['task_length']; // initial queue start with this insert
            $QueueView_insert->stimated_time_to_delivery = $mkr_group_task_id[0]['preferred_delivery_time']; // stimated time to delivery must be process first
      
             if ($QueueView_insert->insert() === false) {
                  var_dump($QueueView_insert->getErrors());
                return "ALTLPController: QueueView_insert error";
             }  
   
            // set shop to customer distance/time on table
            ALTLPController::shopToCustomer($group_task_id);
 
            // set queue history - send task_start and queues after custom processing
            $free_slot_result = ALTLPController::checkFreeSlot($task_start,$valuest);
            
          //  print_r($free_slot_result);
             //  get insert array
            $free_slot_status_insert = $free_slot_result['queue_history_insert_status'];
            $free_slot_insert = $free_slot_result['queue_history_insert'];
            // new insert
            foreach ($free_slot_insert as $key => $value) {
            
                $QueueHistory_insert = new AltlpQueueHistory();
                $QueueHistory_insert->task_id = $task_id;
                $QueueHistory_insert->task_queue_id = '0'; //*******************************
                $QueueHistory_insert->queue_id = $value['queue_id']; // initial queue start with this insert
                $QueueHistory_insert->task_start = $value['task_start']; // initial queue start with this insert
                $QueueHistory_insert->task_index = strval($value['task_index']); // initial queue start with this insert
                $QueueHistory_insert->task_thread = strval($value['task_thread']); // initial queue start with this insert
                $QueueHistory_insert->task_length = $value['task_length']; // task length
                $QueueHistory_insert->task_interval = $value['task_interval']; // task interval
                $QueueHistory_insert->task_status = '3'; // start with SLRE (slot reserved for run)
                $QueueHistory_insert->task_priority = '0'; 
                $QueueHistory_insert->manufacturing_type = $value['manufacturing_type']; 
                   if ($QueueHistory_insert->insert() === false) {
                    var_dump(  $QueueHistory_insert->getErrors());
                    return "ALTLPController: QueueHistory_insert error";
                     }
            }
            
     
        }
        // process manufacturing = sto
      if(!empty($queue_manufacturing_sto)){
          // set the manufacturing storaged task
          $insert_slot_result =ALTLPController::insertStoragedTask($queue_manufacturing_sto);
          //  get insert array
          $free_slot_status_insert = $insert_slot_result['queue_history_insert_status'];
          $free_slot_insert = $insert_slot_result['queue_history_insert'];
          // new insert
          foreach ($free_slot_insert as $key => $value) {
          
              $QueueHistory_insert = new AltlpQueueHistory();
              $QueueHistory_insert->task_id = $task_id;
              $QueueHistory_insert->task_queue_id = '0'; //*******************************
              $QueueHistory_insert->queue_id = $value['queue_id']; // initial queue start with this insert
              $QueueHistory_insert->task_start = $value['task_start']; // initial queue start with this insert
              $QueueHistory_insert->task_index = '0'; // initial queue start with this insert
              $QueueHistory_insert->task_thread ='0'; // initial queue start with this insert
              $QueueHistory_insert->task_length = '00:00:00'; // task length
              $QueueHistory_insert->task_interval = '00:00:00'; // task interval
              $QueueHistory_insert->task_status = '6'; // the final product is on warehouse, insert on task start
              $QueueHistory_insert->task_priority = '0'; 
              $QueueHistory_insert->manufacturing_type = $value['manufacturing_type']; 
              if ($QueueHistory_insert->insert() === false) {
                  var_dump(  $QueueHistory_insert->getErrors());
                  return "ALTLPController: QueueHistory_insert error";
                   }
          }

   

         }
      // process grouping queue (type 6)
      ALTLPController::insertGroupingQueue();

      echo ">>>end<<<";
    }

    // check free slots on queues 
    // input: inital task_start, table of queue_set for this task
    // output: [queue,task_start,slot_queue,slot_thread]

    public function checkFreeSlot($task_request_start,$queue_sets){

        // check if running tasks ending on requested task_start time
        $fields1 = ['task_id','task_start','queue_id','task_index','task_thread','task_status','task_interval'];

        $queue_history_rows = (new \yii\db\Query())
            ->select($fields1)
            ->from('altlp_queue_history')
            ->orderBy('ABS(queue_id),task_index,task_thread')
            ->where('manufacturing_type = 1')                   // the manufacturing ready task are non considered
            ->all();
        // group history table by queue_id
        $queue_history_id = array();
        foreach ($queue_history_rows as $key => $value) {
             $queue_history_id[$value['queue_id']][]=$value;
        }
        // get status table
        $fields2 = ['id','name'];
        $queue_status = (new \yii\db\Query())
            ->select($fields2)
            ->from('altlp_queue_status')
            ->all();
       // group status table by name
        $queue_status_name = array();
        foreach ($queue_status as $key => $value) {
            $queue_status_name[$value['name']][]=$value;
        }

        $queue_history_insert = array();
        $queue_history_insert_status = array();

        // get loop into all wait queues
        foreach ($queue_sets as $keyqs => $valueqs) {
            
            $queue_id = $valueqs['queue_id'];
            // get task_length
            $task_l_tmp = split(':',$valueqs['task_length']);
            $task_length_hours=$task_l_tmp[0];
            $task_length_min=$task_l_tmp[1];
            $task_length_sec=$task_l_tmp[2];
            // get task_interval
            $task_i_tmp = split(':',$valueqs['task_interval']);
            $task_interval_hours=$task_i_tmp[0];
            $task_interval_min=$task_i_tmp[1];
            $task_interval_sec=$task_i_tmp[2];
           // get end time of the requested ask
            $task_r_end = date('Y-m-d H:i:s',strtotime('+'.($task_length_hours+$task_interval_hours).' hour +'.($task_length_min+$task_interval_min).' minutes +'.($task_length_sec+$task_interval_sec).' seconds',strtotime($task_request_start)));
            // array of task, it will use when there's not a free slot on queue
            $task_on_queue = array();
             
            // get the queue_id data of history
            if(!array_key_exists($queue_id,$queue_history_id)){
                // the queue_id is not used in history table
                echo '>>the queue_id:'.$queue_id.' is not used in history table<<';
                // insert on result array
                $queue_history_insert[]=[
                                    'queue_id'=>$queue_id,
                                    'task_start'=>$task_request_start,
                                    'task_end'=>$task_r_end,
                                    'task_index'=>'1',
                                    'task_thread'=>'1',
                                    'task_length'=>$valueqs['task_length'],
                                    'task_interval'=>$valueqs['task_interval'],
                                    'manufacturing_type'=>$valueqs['manufacturing_type'],
                                    ];
                $queue_history_insert_status[]=[
                                    'queue_id'=>$queue_id,
                                    'status'=>'task_setted'
                                    ];
            } else { 
                // get history data of queue_id
                $queue_history = $queue_history_id[$queue_id]; 
                $free_slot = false;
                $task_on_queue_full = array();
                 // get the running task and check if they're finish on requested task_start time
                foreach ($queue_history as $key => $value) {
                        // get index and thread of task
                        $task_thread = $value['task_thread'];
                        $task_index = $value['task_index'];
                        // get end time of the task
                        $task_end = date('Y-m-d H:i:s',strtotime('+'.($task_length_hours+$task_interval_hours).' hour +'.($task_length_min+$task_interval_min).' minutes  +'.($task_length_sec+$task_interval_sec).' seconds',strtotime($value['task_start']))); 
                        // verify if running task is finish when requested task must be start
                        $task_diff = date_diff(date_create($task_request_start), date_create($task_end));
                        /*  $task_diff has this format:
                        (
                            [y] => 0
                            [m] => 0
                            [d] => 0
                            [h] => 0
                            [i] => 7
                            [s] => 24
                            [invert] => 0
                            [days] => 0
                        )

                        we can use [invert], 1 (-) -> time_request > time_end so the running task is finish  *** slot will be free
                                             0 (+) -> time_request < time_end so the running task is running

                                             [h][i][s] are the difference of the 2 inputs
                        */
                        // get running tasks on normal queue (on wait queue status: strq)
                      if(($value['task_status']==$queue_status_name['strq'][0]['id'])&&($task_diff->format('%r')=='-')){
                            // the running task finished before the requested task starts so slot is free
                                echo ">>the running task on normal queue (STRQ) finished before the requested task starts so slot is free - queue:".$valueqs['queue_id']." before slot free on:".$task_request_start.": ".$value['task_index']." - ".$value['task_thread'].'<<';
                                $free_slot=true;
                                 // insert on result array
                                 $queue_history_insert[]=[
                                        'queue_id'=>$queue_id,
                                        'task_start'=>$task_request_start,
                                        'task_end'=>$task_r_end,
                                        'task_index'=>'1',
                                        'task_thread'=>'1',
                                        'task_length'=>$valueqs['task_length'],
                                        'task_interval'=>$valueqs['task_interval'],
                                        'manufacturing_type'=>$valueqs['manufacturing_type'],
                                        ];
                                $queue_history_insert_status[]=[
                                    'queue_id'=>$queue_id,
                                    'status'=>'task_setted'
                                    ];
                
                                break;
                             }
               /******
                         // get slot reserved tasks
                        if($value['task_status']==$queue_status_name['slre'][0]['id']){
                             // verify if requested task is finish when the slre task must be start - it's insert before
                            $task_r_diff = date_diff(date_create($value['task_start']), date_create($task_r_end));
                            if($task_diff->format('%r')=='-'){
                              // the slre task finished before the requested task starts so slot is free
                              echo ">>the slre task finished before the requested task starts so slot is free - queue:".$valueqs['queue_id']."  before slot free on:".$task_request_start.": ".$value['task_index']." - ".$value['task_thread'].'<<';
                              $free_slot=true;
                              // insert on result array
                               $queue_history_insert[]=[
                                        'queue_id'=>$queue_id,
                                        'task_start'=>$task_request_start,
                                        'task_end'=>$task_r_end,
                                        'task_index'=>'1',
                                        'task_thread'=>'1',
                                        'task_length'=>$valueqs['task_length'],
                                        'task_interval'=>$valueqs['task_interval']
                                        ];
                                $queue_history_insert_status[]=[
                                    'queue_id'=>$queue_id,
                                    'status'=>'task_setted'
                                    ];
                
                              break;
                             }
                            if($task_r_diff->format('%r')=='-'){
                                // the  requested task finished beforer the slre task starts so slot is free
                                echo ">> the  requested task finished before the slre task starts so slot is free - queue:".$valueqs['queue_id']."  before slot free on:".$task_request_start.": ".$value['task_index']." - ".$value['task_thread'].'<<';
                               $free_slot=true;
                                // insert on result array
                                $queue_history_insert[]=[
                                        'queue_id'=>$queue_id,
                                        'task_start'=>$task_request_start,
                                        'task_end'=>$task_r_end,
                                        'task_index'=>'1',
                                        'task_thread'=>'1',
                                        'task_length'=>$valueqs['task_length'],
                                        'task_interval'=>$valueqs['task_interval']
                                        ];
                                $queue_history_insert_status[]=[
                                    'queue_id'=>$queue_id,
                                    'status'=>'task_setted'
                                    ];
                
                                break;
                             }
                        }

                    ******/     
                        // the slre owns slot, get thread and index to use later
                     $task_on_queue_full[]=[
                                  'queue_id'=>$queue_id,
                                  'task_index'=>$task_index,
                                  'task_thread'=>$task_thread,
                                  'task_end'=>$task_end
                                  ];
              
                }
                 // check if free slot is found
                if(!$free_slot){
                 //++   echo "not free slot on - queue:".$valueqs['queue_id']."<br>";
                    $not_free_slot = true;
                    $exit = false;
                    // compares not use task_end on task_on_queue so must be removed
                    $task_on_queue = array();
                    foreach ($task_on_queue_full as $keytoq => $valuetoq) {
                         $task_on_queue[] =[
                                  'queue_id'=>$valuetoq['queue_id'],
                                  'task_index'=>$valuetoq['task_index'],
                                  'task_thread'=>$valuetoq['task_thread'],
                                 ];
                    }
                    echo "process:".$valueqs['queue_id']."<";
                    // go into single queue
                   for($task_index_search=1;$task_index_search<=$valueqs['queue_length'];++$task_index_search){
                        // seach by index
                        for($task_thread_search=1;$task_thread_search<=$valueqs['parallel_tasks'];++$task_thread_search){
                            // search by thread
                            $search_array = [ 
                                    'queue_id' => $valueqs['queue_id'],
                                    'task_index' =>$task_index_search,
                                    'task_thread' =>$task_thread_search,
                                     ]; 
                        //++    echo "task_on_queue:";print_r($task_on_queue);
                        //++    echo "search_array:";print_r($search_array);
                    
                            // check if index/thread are in use
                            if(!in_array($search_array,$task_on_queue)){
                               // the combination index/thread is NOT on table so this slot is free, exit loop
                                echo ">>found thread free slot:".print_r($search_array).print_r($task_on_queue_full)."<<";
                                // check the previous task_end
                                if($task_index_search!=1){
                                  // if this is not the first index, the task must start after the previous
                                  // get the end of previous index task
                                  foreach ($task_on_queue_full as $keytoq => $valuetoq) {
                                        if(($valuetoq['task_index']==($task_index_search - 1))&&
                                           ($valuetoq['task_thread']==$task_thread_search)){
                                           // get the end of previous task (+interval) and check the start of this task
                                           // if previuos task end before request_start_task use requesterd 
                                           $task_request_start = ($valuetoq['task_end']<$task_request_start)?$task_request_start:$valuetoq['task_end'];
                                           // get end time of the task
                                           $task_r_end = date('Y-m-d H:i:s',strtotime('+'.($task_length_hours+$task_interval_hours).' hour +'.($task_length_min+$task_interval_min).' minutes  +'.($task_length_sec+$task_interval_sec).' seconds',strtotime($task_request_start))); 
                                    
                                           echo "previous_task_end:".$task_request_start." task_end:".$task_r_end;
                                           break;
                                        }
                                  }
                               //    echo "get data ".$task_index_search." - ".$task_thread_search." from index:".($task_index_search - 1);  
                                }
                               $exit = true;
                               break;
                            }
                          }
                        // exit loop 
                        if($exit){  
                            // $key is the queue_id
                            // $search_array is the index/thread
                            // update queue_ffs table
                           /* $QueueFFS_update = AltlpQueueFfs::findOne(['queue_id' => $key ]);
                            $QueueFFS_update->task_index = strval($search_array['task_index']);
                            $QueueFFS_update->task_thread = strval($search_array['task_thread']);
                            if ($QueueFFS_update->update() === false) {
                                    var_dump($QueueFFS_update->getErrors());
                                     return "ALTLPController: QueueFFS_update error";
                                 }*/
                          // if the index of the free slot is diferent, the new task starts after 
                        /*  $task_on_queue_full_last = end($task_on_queue_full);
                          if($task_index_search!=$task_on_queue_full['task_index']){
                              $task_request_start = $task_on_queue_full['task_end'];
                              // the task_end of previous already has the task_interval
                          } */
                           // insert on result array
                           $queue_history_insert[]=[
                                    'queue_id'=>$queue_id,
                                    'task_start'=>$task_request_start,
                                    'task_end'=>$task_r_end,
                                    'task_index'=>$task_index_search,
                                    'task_thread'=>$task_thread_search,
                                    'task_length'=>$valueqs['task_length'],
                                    'task_interval'=>$valueqs['task_interval'],
                                    'manufacturing_type'=>$valueqs['manufacturing_type'],
                                    ];
                            $queue_history_insert_status[]=[
                                    'queue_id'=>$queue_id,
                                    'status'=>'task_setted'
                                    ];
                             // free slot is found
                            $not_free_slot = false;
                            $exit = false;
                            break;
                        }
                    } 
                  
                    // there's not free slot on this queue
                    if($not_free_slot){
                        // update queue_ffs table
                        /*$QueueFFS_update = AltlpQueueFfs::findOne(['queue_id' => $key ]);
                        $QueueFFS_update->task_index = '0';
                        $QueueFFS_update->task_thread = '0';
                        
                        if ($QueueFFS_update->update() === false) {
                              var_dump($QueueFFS_update->getErrors());
                             return "ALTLPController: QueueFFS_update error";
                            }*/
                        echo ">>not free at all<<";
                        $queue_history_insert_status[]=[
                                    'queue_id'=>$queue_id,
                                    'status'=>'queue_full'
                                    ];
                       
               
                    }

                 }
                    
            }
            // on the next queue the task must start after previous queue process was finished so
             $task_request_start =  $task_r_end;
       
        }

        // return array to insert task on history table
        return [
            'queue_history_insert' => $queue_history_insert,
            'queue_history_insert_status' => $queue_history_insert_status
            ];
    }

    // Process manufacturing = sto
    // check when last element of task group ends on insert_queue and insert as task_start
    // OR 
    // check when first element of task group start on insert_queue and insert as task_start
    //  
    // $queue_manufacturing_sto = [group_id][queue_id]
    //                                      [manufacturing_type]
    //                                      [group_id]
    //                                      [queue_insert_on_group_end]

    public function insertStoragedTask($queue_manufacturing_sto){
        
        $queue_history_insert = array();
        $queue_history_insert_status = array();

          foreach ($queue_manufacturing_sto as $keyms => $valuems) {
            
            if($valuems['queue_insert_on_group_end']=='1'){ 
              // get the end time of the last task of the task_group
              $storaged_insert_task = Yii::$app->db->createCommand("
                  SELECT MAX(addTime(task_start,task_length)) AS 'task_start'
                  FROM altlp_queue_history 
                  WHERE task_id LIKE '".$valuems['task_group']."' AND queue_id = '".$valuems['queue_id']."'
                  ")->queryAll();
          } else {
              // get the start time of the first task of the task_group
              $storaged_insert_task = Yii::$app->db->createCommand("
                  SELECT MIN(task_start) AS 'task_start'
                  FROM altlp_queue_history 
                  WHERE task_id LIKE '".$valuems['task_group']."' AND queue_id = '".$valuems['queue_id']."'
                  ")->queryAll();
            }
            // insert on result array
           $queue_history_insert[]=[
                      'queue_id'=>$valuems['queue_id'],
                      'task_start'=>$storaged_insert_task[0]['task_start'],
                      'manufacturing_type'=>$valuems['manufacturing_type'],
                      ];
              $queue_history_insert_status[]=[
                      'queue_id'=>$valuems['queue_id'],
                      'status'=>'task_setted'
                      ];
         }

         // return array to insert task on history table
        return [
            'queue_history_insert' => $queue_history_insert,
            'queue_history_insert_status' => $queue_history_insert_status
            ];
    


    }

    // Process grouping queue (type 6) - set on altpl_delivery_waypoints
    //
    // check when last element of task group ends on insert_queue and insert as grouping_end
    // check when first element of task group start on insert_queue and insert as grouping_start

    public function insertGroupingQueue(){

          // get queue_id (ONLY ONE for this type by now)
          $fields = ['queue_id'];

          $group_queue_id  = (new \yii\db\Query())
              ->select($fields)
              ->from('altlp_queue_set')
              ->where('queue_type = "6"')
              ->orderBy('ABS(queue_id)')  
              ->one();

       
          // get groups in queue
          $fields = ['SUBSTR(task_id,1,11) AS group_id'];

          $group_id_temp = (new \yii\db\Query())
              ->select($fields)
              ->from('altlp_queue_history')
              ->where('queue_id = '.$group_queue_id['queue_id'].' && task_status = 3')
              ->all();

          // group by group_id
          $grouping_queue_id = array();

          foreach ($group_id_temp as $key => $value) {
              $grouping_queue_id [$value['group_id']] = $value;
            
          }


         foreach ($grouping_queue_id as $keygq => $valuegq) {
          
            // get the start time of the first task of the task_group
            $grouping_start = Yii::$app->db->createCommand("
                    SELECT MIN(task_start) AS 'task_start'
                    FROM altlp_queue_history 
                    WHERE task_id LIKE '".$valuegq['group_id']."%' AND queue_id = '".$group_queue_id['queue_id']."'
                    ")->queryOne(); 
   
            // get the end time of the last task of the task_group
            $grouping_end = Yii::$app->db->createCommand("
                    SELECT MAX(addTime(task_start,task_length)) AS 'task_end'
                    FROM altlp_queue_history 
                    WHERE task_id LIKE '".$valuegq['group_id']."%' AND queue_id = '".$group_queue_id['queue_id']."'
                    ")->queryOne();
      /*   echo '-------';
          print_r($grouping_start);
          echo '------------';
          print_r($grouping_end);die;
        */     // set on table
            $update_values = [
                          'start_grouping' => $grouping_start['task_start'],
                          'end_grouping' => $grouping_end['task_end']
                             ];

           Yii::$app->db->createCommand()
              ->update('altlp_delivery_waypoints', $update_values, 'group_task_id = '.$valuegq['group_id'])
              ->execute(); 
          }


    }


    //public function 
    // check queue slots
    // loop on queue_index (<= queue_length)-> loop on thread (<= queue_tasks)
    // return position status [queue_index][queue_thread] 
    // first free slot is updated on altlp_queue_ffs tbl
    public function checkQueueSlots(){

        // get queues id

        $fields = ['queue_id','queue_length','parallel_tasks'];

        $queue_set = (new \yii\db\Query())
            ->select($fields)
            ->from('altlp_queue_set')
            ->all();
        
        $fields1 = ['task_id','queue_id','task_index','task_thread'];

        $queue_history = (new \yii\db\Query())
            ->select($fields1)
            ->from('altlp_queue_history')
            ->orderBy('queue_id,task_index,task_thread')
            ->all();
        
        // group queue_history by queue_id
        $queue_set_id = array();
        foreach ($queue_set as $key => $value) {
            $queue_set_id[$value['queue_id']][]=$value;
        }
        // group queue_set by queue_id
        $queue_history_id = array();
        foreach ($queue_history as $key => $value) {
            $queue_history_id[$value['queue_id']][]=$value;
        }
        // loop on history table
        $task_table = array();
        $task_table_queue = array();
        foreach ($queue_history_id as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $task_table[] = [
                            'queue_id' => $key,
                            'task_index' => $value1['task_index'],
                            'task_thread' => $value1['task_thread'],
                            'task_id' => $value1['task_id']
                            ];
                $task_table_queue[$key][] = [ 
                            'task_index' => $value1['task_index'],
                            'task_thread' => $value1['task_thread'],
                            ]; 
            }
        }
       
        // go into array and get first free slot for a queue_id
       foreach ($queue_set_id as $key => $value) {
             $exit = false;
             $not_free_slot = true;
             // get queue set data 
             $task_index = $queue_set_id[$key][0]['queue_length'];
             $parallel_tasks = $queue_set_id[$key][0]['parallel_tasks'];
            
             // check if the queue_id is in queue_history
             if(!array_key_exists($key,$task_table_queue)){
               // queue_id doesn't exist so the free slot is the first task_index:1,task_thread=1
                // update queue_ffs table
                    $QueueFFS_update = AltlpQueueFfs::findOne(['queue_id' => $key ]);
                    $QueueFFS_update->task_index = '1';
                    $QueueFFS_update->task_thread = '1';
                    
                    if ($QueueFFS_update->update() === false) {
                          var_dump($QueueFFS_update->getErrors());
                         return "ALTLPController: QueueFFS_update error";
                        }
                  // free slot is found
                $not_free_slot = false;
              
             } else {

                 // go into single queue
              for($task_index_search=1;$task_index_search<=$task_index;++$task_index_search){
                    // seach by index
                    for($task_thread_search=1;$task_thread_search<=$parallel_tasks;++$task_thread_search){
                        // search by index
                        $search_array = [ 
                                'task_index' => $task_index_search,
                                'task_thread' => $task_thread_search
                                ]; 
                        if(!in_array($search_array,$task_table_queue[$key])){
                           // the combination index/thread is NOT on table so this slot is free, exit loop
                            $exit = true;
                           break;
                        }
                      }
                    // exit loop 
                    if($exit){  
                        // $key is the queue_id
                        // $search_array is the index/thread
                        // update queue_ffs table
                        $QueueFFS_update = AltlpQueueFfs::findOne(['queue_id' => $key ]);
                        $QueueFFS_update->task_index = strval($search_array['task_index']);
                        $QueueFFS_update->task_thread = strval($search_array['task_thread']);
                        if ($QueueFFS_update->update() === false) {
                                var_dump($QueueFFS_update->getErrors());
                                 return "ALTLPController: QueueFFS_update error";
                             }
                                        
                        // free slot is found
                        $not_free_slot = false;
                        break;
                    }
                } 
              
                // there's not free slot on this queue
                if($not_free_slot){
                    // update queue_ffs table
                    $QueueFFS_update = AltlpQueueFfs::findOne(['queue_id' => $key ]);
                    $QueueFFS_update->task_index = '0';
                    $QueueFFS_update->task_thread = '0';
                    
                    if ($QueueFFS_update->update() === false) {
                          var_dump($QueueFFS_update->getErrors());
                         return "ALTLPController: QueueFFS_update error";
                        }
           
                }
            }
        }
      /*  // loop on queues id
        foreach ($queue_rows as $key => $value) {
           // loop history with length
           echo $value['queue_length'];

           for ($queue_task=1;$queue_task<=$value['queue_length'];$queue_task++){

            }
             
        }*/
       // print_r($task_table);
    }

    // queue_history group by queue_id

    public function queueHistory(){


        $fields1 = ['SUBSTRING(task_id,9,3) AS task_id_view','SUBSTRING(task_id,12,3) AS item_id_view','task_id','queue_id','TIME_FORMAT( task_start,"%H:%i") AS task_start_time','UNIX_TIMESTAMP(task_start) AS task_start','task_index','task_thread','TIME_FORMAT( task_length,"%i") AS task_length','name AS task_status'];

        $queue_history_rows = (new \yii\db\Query())
            ->select($fields1)
            ->from('altlp_queue_history')
            ->orderBy('queue_id,task_index,task_thread')
            ->join('INNER JOIN','altlp_queue_status','altlp_queue_status.id = altlp_queue_history.task_status')

            ->all();
        
        // group queue_history by queue_id
        $queue_history = array();
        foreach ($queue_history_rows as $key => $value) {
            $queue_history[$value['queue_id']][]=$value;
        }

        return $queue_history;
    }


 

    /* common */
  
    public function mts_to_km($mts){
        
        return number_format(($mts/1000), 2, '.', '');
    
    }

    public function sec_to_min($sec){
        return gmdate('H:i:s', $sec);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function now_time(){

         return date('Y-m-d H:i:s');
        }
    public function starting_queue(){

        return 1;
    }
} 

/*  // check free slots on queues 
    // input: inital task_start 
    // output: [queue,task_start,slot_queue,slot_thread]

    public function checkFreeSlot($task_request_start){

        // get queue sets

        $fields = ['queue_id','queue_length','parallel_tasks','task_length','task_interval'];

        $queue_set = (new \yii\db\Query())
            ->select($fields)
            ->from('altlp_queue_set')
            ->where('queue_id = '.$queue_id)
            ->all();
         

        // check if running tasks ending on requested task_start time
        $fields1 = ['task_id','task_start','queue_id','task_index','task_thread','task_status'];

        $queue_history = (new \yii\db\Query())
            ->select($fields1)
            ->from('altlp_queue_history')
            ->orderBy('queue_id,task_index,task_thread')
            ->where('queue_id = '.$queue_id)
            ->all();

        // get status table
        $fields2 = ['id','name'];
        $queue_status = (new \yii\db\Query())
            ->select($fields2)
            ->from('altlp_queue_status')
            ->all();
       // group status table by name
        $queue_status_name = array();
        foreach ($queue_status as $key => $value) {
            $queue_status_name[$value['name']][]=$value;
        }

        // get the running task and check if they're finish on requested task_start time
        foreach ($queue_history as $key => $value) {
              // get task_length
               $task_l_tmp = split(':',$queue_set[0]['task_length']);
               $task_length_hours=$task_l_tmp[0];
               $task_length_min=$task_l_tmp[1];
               // get task_interval
               $task_i_tmp = split(':',$queue_set[0]['task_interval']);
               $task_interval_hours=$task_i_tmp[0];
               $task_interval_min=$task_i_tmp[1];
           // get end time of the task
               $task_end = date('Y-m-d H:i:s',strtotime('+'.($task_length_hours+$task_interval_hours).' hour +'.($task_length_min+$task_interval_min).' minutes',strtotime($value['task_start'])));
               // verify if running task is finish when requested task must be start
               $task_diff = date_diff(date_create($task_request_start), date_create($task_end));
               /*  $task_diff has this format:
                (
                    [y] => 0
                    [m] => 0
                    [d] => 0
                    [h] => 0
                    [i] => 7
                    [s] => 24
                    [weekday] => 0
                    [weekday_behavior] => 0
                    [first_last_day_of] => 0
                    [invert] => 0
                    [days] => 0
                    [special_type] => 0
                    [special_amount] => 0
                    [have_weekday_relative] => 0
                    [have_special_relative] => 0
                )

                we can use [invert], 1 (-) -> time_request > time_end so the running task is finish  *** slot will be free
                                     0 (+) -> time_request < time_end so the running task is running

                                     [h][i][s] are the difference of the 2 inputs
                */
      /*      // get running tasks
            if(($value['task_status']==$queue_status_name['run'][0]['id'])&&($task_diff->format('%r')=='-')){
                // the running task finished after the requested task starts so slot is free
                    echo "the running task finished after the requested task starts so slot is free - queue:".$queue_set[0]['queue_id']." before slot free on:".$task_request_start.": ".$value['task_index']." - ".$value['task_thread'];
                 }
             // get slot reserved tasks
            if($value['task_status']==$queue_status_name['slre'][0]['id']){
                // get end time of the requested ask
                $task_r_end = date('Y-m-d H:i:s',strtotime('+'.$task_length_hours.' hour +'.$task_length_min.' minutes',strtotime($task_request_start)));
                // verify if requested task is finish when the slre task must be start - it's insert before
                $task_r_diff = date_diff(date_create($value['task_start']), date_create($task_r_end));
                if($task_diff->format('%r')=='-'){
                  // the slre task finished after the requested task starts so slot is free
                  echo "the slre task finished after the requested task starts so slot is free - queue:".$queue_set[0]['queue_id']." after slot free on:".$task_request_start.": ".$value['task_index']." - ".$value['task_thread'];
                 }
                if($task_r_diff->format('%r')=='-'){
                    // the  requested task finished after the slre task starts so slot is free
                    echo " the  requested task finished after the slre task starts so slot is free - queue:".$queue_set[0]['queue_id']." after slot free on:".$task_request_start.": ".$value['task_index']." - ".$value['task_thread'];
                 }
            }
            

        }
        

    }
    */