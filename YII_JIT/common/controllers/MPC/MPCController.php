<?php
/**
 *
 * Copyright (c) 2017. @pablo
 *
 * Test Code
 */

namespace common\controllers\MPC;


use common\controllers\PRO\PROController;
use common\controllers\MKR\MKRController; 
use common\controllers\ALTLP\ALTLPController; 

use Yii;

use common\models\MPC\MpcJsonDataCache;
use common\models\MPC\MpcJsonProductCache;
use common\models\MPC\MpcTablesUpdate;

class MPCController extends \yii\web\Controller
{
  // Update cache data functions
  
  public function updateProducts(){
    
    MPCController::setJSONcacheTable('items_data_init');
    MPCController::setJSONcacheProduct();

  } 
  public function updatePromoSingleProduct(){
     
     MPCController::setJSONcacheTable('promo_single_product');
     MPCController::setJSONcacheProduct();

  }
	public function updatePromoList(){
    
    MPCController::setJSONcacheTable('promo_list');
    MPCController::setJSONcacheProduct();

  }
  public function updataPromoCategories(){
     
    MPCController::setJSONcacheTable('promo_categories');
    MPCController::setJSONcacheProduct();

  }

  public function updateQueueStatus(){

    MPCController::setJSONcacheTable('queue_status');

  }

  public function updateQueueBriefStatus(){

    MPCController::setJSONcacheTable('queue_brief_status');

  }

  public function updateRoutingMap(){

      MPCController::setJSONcacheTable('routing_map');
  
  }
  // Set JSON Cache Mobile data table

  public function setJSONcacheTable($code=null){

    // set Items

    switch ($code) {

      case 'items_data_init':
         // Product List
          $items[]=array(
              'code' => 'items_data_init',
              'name' => 'Product List',
              'var_name' => '',
              'desc' => 'All Products Items Array',
              'data' => PROController::productList(0)
              );# code...
        break;

      case 'middle_menu_cat':
         // Menu Category
          $items[]=array(
                'code' => 'middle_menu_cat',
                'name' => 'Categories Menu',
                'var_name' => '',
                'desc' => 'Middle Menu Categories',
                'data' => PROController::mobileCategoriesMenu()
                );
        break;

      case 'promo_single_product';
        // Promo Single Produc
         $items[]=array(
              'code' => 'promo_single_product',
              'name' => 'Single Product\'s Promos',
              'var_name' => '',
              'desc' => 'Single Product',
              'data' => MKRController::promoSingleProduct()
              );
        break;
      case 'promo_categories';
        // Promo Single Produc
         $items[]=array(
              'code' => 'promo_categories',
              'name' => 'Categories Product\'s Promos',
              'var_name' => '',
              'desc' => 'Categories Promo',
              'data' => MKRController::promoCategories()
              );
        break;
        case 'promo_list';
        // Promo List
         $items[]=array(
              'code' => 'promo_list',
              'name' => 'Promo List',
              'var_name' => '',
              'desc' => 'Promo List of Products',
              'data' => MKRController::promoList()
              );
        break;
        case 'queue_status';
        // Promo List
         $items[]=array(
              'code' => 'queue_status',
              'name' => 'Queue Status',
              'var_name' => 'd_Q',
              'desc' => 'Queue of ALTLP procs',
              'data' => ALTLPController::queueStatus()
              );
        break;
        case 'queue_brief_status';
        // queue
         $items[]=array(
              'code' => 'queue_brief_status',
              'name' => 'Queue Brief Status',
              'var_name' => 'd_Q_brief',
              'desc' => 'Queue of ALTLP procs in single level array',
              'data' => ALTLPController::queueBriefStatus()
              );
        break;
   case 'routing_map';
        // Routing Map
         $items[]=array(
              'code' => 'routing_map',
              'name' => 'Routing Map',
              'var_name' => 'd_Delivery',
              'desc' => 'Leaflet Routing Map',
              'data' => ALTLPController::getRoutingMap()
              );
        break;
      default:
        return;
        break;
    }
   
    $result = true;

     foreach ($items as $key => $value) {
      // convert
     $json_data=trim(json_encode($items[$key]['data']));
 
      // set / update
      $query_result = MPCController::setJSONcache(
                    $items[$key]['code'],
                    $items[$key]['name'],
                    $items[$key]['var_name'],
                    $items[$key]['desc'],
                    $json_data
                    );
 
      $result = $result && $query_result; 
      }  

      return $result;
  } 

  /* Set Single Product/Cat with Promo Cache */

  public function setJSONcacheProduct(){

        $product_list = PROController::productWithPromo();

        $result = true;

        // category loop
        foreach ($product_list as $key => $value) {
          // single cat loop
          foreach ($product_list[$key] as $key1 => $value1) {
                // convert
               $json_data=trim(json_encode($product_list[$key][$key1]));
           
                // set / update
                $query_result = MPCController::setJSONcachePRO(
                              $product_list[$key][$key1]['id'],
                              $product_list[$key][$key1]['code'],
                              $product_list[$key][$key1]['ProductName'],
                              '',
                              $json_data
                              );
                $result = $result && $query_result; 
       
          }
       }
  
      return $result;  
  }

/* get the cache data stored */
  
  public function getJSONcacheProduct($product_id = null){

        $where=['OR'];
       foreach ($product_id as $key => $value) {
           $where[]=['product_id'=>$value];
       } 

    $rows = (new \yii\db\Query())
            ->select(['product_id','data'])
            ->from('mpc_json_product_cache') 
            ->where($where)
            ->all(); 

        // check if cache was modified
      /*  if( $row[0]['modified'] != $modified_timestamp ) {
            return $row[0]['data'];
        } else {
            // return 'sync' string if data was not modified
            return '';
        }   */ 
     return $rows;   

  }
  /* Low Level */

  /* get the cache data stored */
	public function getJSONcache($codes = null,$modified_timestamp = null){

        $where=['OR'];
       foreach ($codes as $key => $value) {
           $where[]=['code'=>$value];
       } 

		$rows = (new \yii\db\Query())
            ->select(['code','var_name','data','modified'])
            ->from('mpc_json_data_cache') 
            ->where($where)
            ->all(); 

        // check if cache was modified
      /*  if( $row[0]['modified'] != $modified_timestamp ) {
            return $row[0]['data'];
        } else {
            // return 'sync' string if data was not modified
            return '';
        }   */ 
     return $rows;   

	}

  /* stored or update cache data */

  /* using UTC time for modified */
  
  public function setJSONcache($code,$name,$var_name='',$desc='',$data){
      
      // check if is an update or a new row of data
      $JSONcache_update = MpcJsonDataCache::findOne(['code' => $code ]);
     
     if(is_null($JSONcache_update)){
        
        // new insert
        $JSONcache_insert = new MpcJsonDataCache();
        $JSONcache_insert->code = $code;
        $JSONcache_insert->name = $name;
        $JSONcache_insert->var_name = $var_name;
        $JSONcache_insert->data = $data;
        $JSONcache_insert->desc = $desc;
        $JSONcache_insert->modified = date("Y-m-d H:i:s"); 
        if ($JSONcache_insert->insert() !== false) {
                return true;
            } else {
                return false;
            }

      } else {

        // update
        $JSONcache_update->name = $name;
        $JSONcache_update->var_name = $var_name;
        $JSONcache_update->data = $data;
        $JSONcache_update->desc = $desc;
        $JSONcache_update->modified = date("Y-m-d H:i:s");
       
        if ($JSONcache_update->update() !== false) {
                return true;
            } else {
                return false;
            }

      }
   
      
  }

 /* stored or update product cache data */

  /* using UTC time for modified */
  
  public function setJSONcachePRO($product_id,$code,$name='',$desc='',$data){
      
      // check if is an update or a new row of data
      $JSONcache_update = MpcJsonProductCache::findOne(['product_id' => $product_id]);
     
     if(is_null($JSONcache_update)){
        
        // new insert
        $JSONcache_insert = new MpcJsonProductCache();
        $JSONcache_insert->product_id = $product_id;
        $JSONcache_insert->code = $code;
        $JSONcache_insert->name = $name;
        $JSONcache_insert->data = $data;  
        if ($JSONcache_insert->insert() !== false) {
                return true;
            } else {
                return false;
            }

      } else {

        // update
        $JSONcache_update->product_id = $product_id;
        $JSONcache_update->code = $code;
        $JSONcache_update->name = $name;
        $JSONcache_update->data = $data;
       
        if ($JSONcache_update->update() !== false) {
                return true;
            } else {
                return false;
            }

      }
   
      
  }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
