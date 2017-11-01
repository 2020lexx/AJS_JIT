<?php
/**
 *
 * Copyright (c) 2017. @pablo
 *
 * Test Code
 */

namespace common\controllers\ZSA;

use Yii; 

use common\models\ZSA\Shops; 

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ZSAController extends \yii\web\Controller
{
    
	/* Search Nearly Shops */

	public function getNearbyShops($latlng){

		$latlng_arr = split(",", $latlng);

		// get list of shops at 'delivery_area' from customer input coords
		// return fields
 			$fields=['code',
 					 'name',
 					 'logo',
 					 'description',
 					 'phones',
 					 'email',
 					 'street_name',
 					 'street_number',
 					 'street_ids',
 					 'coords',
 					 'delivery_area'];
 			 
 		
		// status = active 
         array_push($fields,"truncate(((((acos(sin((".$latlng_arr[0]."*pi()/180)) * 
		            sin(( zsa_shops.lat *pi()/180))+cos((".$latlng_arr[0]."*pi()/180)) * 
		            cos(( zsa_shops.lat *pi()/180)) * cos(((".$latlng_arr[1]." - zsa_shops.lng )* 
		            pi()/180))))*180/pi())*60*1.1515 ) * 1.609344) ,2) AS `distance_to`");

 		 $rows = (new \yii\db\Query())
            ->select($fields)
            ->from('zsa_shops')
            ->where(['zsa_shops.status'=>'1'])
            ->having('`distance_to` < `delivery_area`')
		    ->all();  
 
        // group by shop_code
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
