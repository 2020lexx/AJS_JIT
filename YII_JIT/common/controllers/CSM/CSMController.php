<?php
/**
 *
 * Copyright (c) 2017. @pablo
 *
 * Test Code
 */

namespace common\controllers\CSM;

use Yii;

use common\models\CSM\CustomerLocal;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class CSMController extends \yii\web\Controller
{
    
	/* Get Customer Local data get from code or mkr_checkout.local_id */

	public function customerLocal($code,$local_id){
  
 	   if($code!=''){
	 	   // code=0 get all
		   if($code=='0'){
		   		$where_cmd=['like','csm_customers_local.code',''];
		   } else {
		   		$where_cmd=['csm_customers_local.code' => $code];
		   } 
		 } else {
			$where_cmd=['csm_customers_local.id' => $local_id];
		 }
     	// return fields
 		$fields=['zsa_customers.code',
 				 'last_name',
 				 'first_name',
 				 'email',
 				 'phones',
 				 'photo',
 				 'birth',
 				 'zsa_customers_status.name AS customer_status',
 				 'zsa_customers_address.name AS address_id',
 				 'customer_since',
                 'total_orders',
 				 'street_name',
 				 'street_number',
 				 'street_ids',
 				 'locality',
 				 'coords'];
 
		$rows = (new \yii\db\Query())
            ->select($fields)
            ->from('csm_customers_local')
            ->join('INNER JOIN','zsa_customers','csm_customers_local.code = zsa_customers.code')
            ->join('INNER JOIN','zsa_customers_address','csm_customers_local.address_id = zsa_customers_address.id')
            ->join('INNER JOIN','zsa_customers_status','zsa_customers.status = zsa_customers_status.id')
        	->where($where_cmd)
            ->all();  

        // group by cat
        $result=array();
        foreach ($rows as $key => $value) {
              $result[]=$value;
        }

        return($result);


	}

    public function actionIndex()
    {
        return $this->render('index');
    }

}
