<?php
/**
 *
 * Copyright (c) 2017. @pablo
 *
 * Test Code
 */

namespace backend\controllers;

use common\controllers\PRO\PROController; 
use common\controllers\MPC\MPCController;
use common\controllers\MKR\MKRController;
use common\controllers\ESI\ESIController;
use common\controllers\ZSA\ZSAController;
use common\controllers\CSM\CSMController; 
use common\controllers\ALTLP\ALTLPController; 

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class BecController extends \yii\web\Controller
{

    /**
     * @return array
     */
    public function accessRules()
    {
        return array(
            array('deny',
                'actions'=>array('create', 'edit'),
                'users'=>array('*'),
            ),
            array('allow',
                'actions'=>array('delete'),
                'roles'=>array('admin'),
            ),
            array('deny',
                'actions'=>array('delete'),
                'users'=>array('*'),
            ),
        );
    }
	// disable CSFR - fetch error	
	public $enableCsrfValidation = false;
	


	public function actionBackendasync(){



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
		 fnc_sync:[]
		 data: {
			[1,2,3,4]
			}
		 }} */

	public function actionBackendsync(){

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
		  $data = json_decode(file_get_contents("php://input"),true);
		  // check token
		  $token = $data['token'];
 
		  // call funcion
		  $call_function=$data['fnc_sync'];
		  $call_function_data=$data['data'];

		  $result =  $this->$call_function($call_function_data);
 		
 		  // return  
		  $data_to_send = json_encode($result);
 		  echo $data_to_send;
		}

	}

	/* init sync data */
	/* recieved [{var:var_name,timestamp:timestamp}] of mobile data storaged */

	public function sync_data($data){
 	
   /*
 	$data=[];
		$data['data']= array([ 'code'=>'items_data_init','modified'=>'2016-05-15 17:26:10'],
      ['code'=>'middle_menu_cat','modified'=>'2016-05-15 17:27:03']);  
 */ 
 		// get var name array
	foreach ($data as $key => $value) {
			$query_var[] = $value['code'];
		}
		// get cache table
		$cache_data=MPCController::getJSONcache($query_var);
		$response=[];
		// check modified timestamp
	 	foreach ($data as $key => $value) {
			$var_to_check=$value['code'];
			$timestamp_to_check=$value['modified']; 
			foreach ($cache_data as $key1 => $value1) {
				// compare modified timestamp
				if($var_to_check==$value1['code']){
				//	echo $var_to_check."=======".$timestamp_to_check."---".$value1['modified'];
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
								'var_name'=>$value1['var_name'],
								'data'=>$data,
								'modified'=>$modified
								];

				}
			}
		}    
		 
	//	$response = $cache_data;

	  $result = $this->AJAXFetchResponse([['sync_data',$response]]);
      
      return $result;
	  
	}

	// GetModifiedOrders
	//
	// recieved modified timestamps of d_orders records
	// it will verify with local data and it will send the modified orders records

	public function GetModifiedOrders($data){

		// if remote table is empty return all data
		if(empty($data)){

			$all_orders=MKRController::checkoutDataBrief();
			$result = $this->AJAXFetchResponse([['d_orders',$all_orders]]);
    		return($result);
		}

		// get timestamp data from local table
		$timestamp_table= MKRController::getTimestampOfOrders();

		$response_array = [];
		// compare modified timestamps
		foreach ($timestamp_table as $key => $value) {
			// control if local task is not on remote yet
			$local_in_remote = false;
			foreach ($data as $key1 => $value1) {
				// search for == rows
				if($value['task_id']==$value1['task_id']){
					// check timestamp
					if($value['timestamp']!=$value1['timestamp']){
						// get data
						$response_item = MKRController::checkoutDataBrief($value['task_id']);
						$response_array[$value['task_id']] = $response_item[$value['task_id']];
					}
				$local_in_remote = true;
				}
			}
			if($local_in_remote==false){
				// this task_id is not on remote 
				$response_array[$value['task_id']] = MKRController::checkoutDataBrief($value['task_id']);
			}
		}
		
		$result = $this->AJAXFetchResponse([['d_orders',$response_array]]);
    	return($result);
	
	}

	// get queue_history table group by queue_id

	public function QueueHistory(){

		$result_tmp=ALTLPController::queueHistory();
		$result = $this->AJAXFetchResponse([['d_queue_history',$result_tmp]]);
    	return($result);
	
	}


   
	public function actionHistory(){

		$result_tmp=ALTLPController::queueHistory();
		
		print_r($result_tmp);
	
	}



	public function actionTest(){

		$test=ALTLPController::queueBriefStatus();
		MPCController::updateQueueBriefStatus();

		//$result = $this->AJAXFetchResponse([['test',$test]]);
    	//$result = $this->AJAXFetchResponse([['QueueStatus',$test]]);
    	
     	print_r($test);
	}
 	
	// Main Queues Status
 	public function QueueStatus(){

		$test=ALTLPController::queueStatus();

		$result = $this->AJAXFetchResponse([['QueueStatus',$test]]);
    	
     	return($result);
	}
	// Checkout Data
 	public function CheckoutDataBrief(){

		$test=MKRController::checkoutDataBrief(2016110401);
		$result = $this->AJAXFetchResponse([['CheckoutDataBrief',$test]]);
    	
     	return($result);
	}

	// Order + User + Items Array
 	public function actionTestweb(){

		 $result = MKRController::checkoutDataBrief();
     	
    	 
     	print_r($result);
	}
	// altlp planning
	 public function actionAltlp(){

	  $result = ALTLPController::setPlanning('20161104001');
  	// $result = ALTLPController::checkQueueSlots();
    	 print_r($result);
    	return; $result = ALTLPController::setPlanning('2016110402');
 	
    	 
     	print_r($result);
	}

	// Order + User + Items Array
 	public function actionTestmap(){

		 $result = ALTLPController::getRoutingMap();
     	MPCController::updateRoutingMap();
     
     	print_r($result);
	}

 	public function action1(){


 		$result=MKRController::checkoutDataBrief(2016110401);

 		print_r($result);
 	}
	public function action2(){


 		$result=MPCController::getJSONcacheProduct([67,55]);


 		print_r(json_decode($result[0]['data'],true)['format']['0']['format_id']);
 	}
public function action3(){

 	ALTLPController::checkFreeSlot('2016-12-19 20:08:36');

 		
 	}
 	
public function action4(){

 		  $result = ALTLPController::checkQueueSlots();

 		
 	}
 	/* Main MOP Record */
 	// return former record - 0 = all running
 	/*
 		[checkout_id][static][user_data]
 							 [order]
 							 [delivery_address]
 							 [aditiona_data]
 							 [vars]
 					 [dynamic]

 	*/
	public function MainMOPRecord($checkout_id = 0){




	}

	/* Common Functions */

	/* fetch response */
	public function AJAXFetchResponse($fetch_response)
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
	
    public function actionIndex()
    {
        return $this->render('index');
    }

}
