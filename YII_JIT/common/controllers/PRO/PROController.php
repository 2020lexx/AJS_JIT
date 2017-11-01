<?php
/**
 *
 * Copyright (c) 2017. @pablo
 *
 * Test Code
 */

namespace common\controllers\PRO;

use common\controllers\MKR\MKRController; 

use Yii;

use common\models\PRO\ProProduct;
use common\models\PRO\ProCategory;
use common\models\PRO\ProProductSearch;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController for ProProduct model.
 */
class PROController extends Controller
{
    
      
      // Product list by Category

      public function productList($cat){
      
      // Product List Items
      // mobile: items_data_init_fetch[]
      // cat=0 means all categories

      // return fields
      $fields=['pro_product.id',
               'pro_product.code',
               'pro_product.price',
               'pro_category.name AS CategoryName',
               'pro_product.name AS ProductName'];
      
      // Get All Categories Array
      $product=PROController::getAllElements($cat,$fields);
        
        $groups=[];
        // Get id's from products
        foreach ($product as $key => $catGroup) {
          foreach ($catGroup as $key1 => $value) {
            $groups[]=$value['id'];
          }
        }

          // groups - Search element match secondary-elements
          $product1=PROController::getGroupsAndElements($groups);
        
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
        $fields=['pro_product_format_cost.name AS name',
             'pro_product_format.value AS format',
             'pro_product_format_2.value AS format2',
             'pro_product_format_3.value AS format3',
             'pro_product_format_cost.value AS price'];

        $product4=PROController::getAllUnits($fields);
      
      // groups - Search Full Category format and cost
        $fields=['pro_product_format_cost.name AS name',
             'pro_product_format.value AS format',
             'pro_product_format_2.value AS format2',
             'pro_product_format_3.value AS format3',
             'pro_product_format_cost.value AS factor'];
      $product5=PROController::getAllUnitsCat($fields);
         
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

       
      return $product6;

     // $result = $this->fetch_response([['items_data_init_fetch',$product6]]);
     
  }

      //  Apply promo to product list

    public function productWithPromo(){

        // get all list
        $product_list = PROController::productList(0);

        // get single product promo
        $promo_single_product = MKRController::promoSingleProduct();
        // get categories producto promo
        $promo_cat_product = MKRController::promoCategories();
        // main loop
        foreach ($product_list as $key => $value){
          // single cat loop
          foreach ($product_list[$key] as $key1 => $value1) {
            // apply for categories
            foreach ($promo_cat_product as $key2 => $value2) {
                
                if($promo_cat_product[$key2]['category']==$key){
                  // check format promo
                  if($promo_cat_product[$key2]['format_id']==0){
                     // apply to all formats
                      foreach ($product_list[$key][$key1]['format'] as $key3 => $value3) {
                        // get price
                        $price = floatval($product_list[$key][$key1]['format'][$key3]['price']);
                        $factor = floatval($promo_cat_product[$key2]['price_reduction']);
                        $product_list[$key][$key1]['format'][$key3]['new_price'] = strval($price * $factor);
                     } 
                  } else {
                     // apply to single format
                       foreach ($product_list[$key][$key1]['format'] as $key3 => $value3) {
                         if($promo_cat_product[$key2]['format_id']==$product_list[$key][$key1]['format'][$key3]['format_id']){
                          // get price
                          $price = floatval($product_list[$key][$key1]['format'][$key3]['price']);
                          $factor = floatval($promo_cat_product[$key2]['price_reduction']);
                          $product_list[$key][$key1]['format'][$key3]['new_price'] = strval($price * $factor);
                        }
                     } 
                   }
                  // add promo fields
                  $product_list[$key][$key1]['promo'] = $promo_cat_product[$key2];

               }
            }
         // apply for single product 
         foreach ($promo_single_product as $key4 => $value4) {
             if($promo_single_product[$key4]['id']==$product_list[$key][$key1]['id']){
                  // check format promo
                  if($promo_single_product[$key4]['format_id']==0){
                     // apply to all formats
                      foreach ($product_list[$key][$key1]['format'] as $key3 => $value3) {
                        // get price
                        $price = floatval($product_list[$key][$key1]['format'][$key3]['price']);
                        $factor = floatval($promo_single_product[$key4]['price_reduction']);
                        $product_list[$key][$key1]['format'][$key3]['new_price'] = strval($price * $factor);
                     } 
                  } else {
                     // apply to single format
                       foreach ($product_list[$key][$key1]['format'] as $key3 => $value3) {
                         if($promo_single_product[$key4]['format_id']==$product_list[$key][$key1]['format'][$key3]['format_id']){
                            // check if fixed price or reduction
                            if($promo_single_product[$key4]['price']==0){
                                // apply price reduction
                                $old_price = floatval($product_list[$key][$key1]['format'][$key3]['price']);
                                $factor = floatval($promo_single_product[$key4]['price_reduction']);
                                $product_list[$key][$key1]['format'][$key3]['new_price'] = strval($old_price * $factor);
                                 $promo_single_product[$key4]['old_price'] = "";
                            } else {
                                // apply fixed
                                $product_list[$key][$key1]['format'][$key3]['new_price'] = $promo_single_product[$key4]['price'];
                                $old_price = floatval($product_list[$key][$key1]['format'][$key3]['price']);
                                $promo_single_product[$key4]['old_price'] = strval($old_price);
                            }

                        }
                     } 
                   }
                  // add promo fields
                  $product_list[$key][$key1]['promo'] =$promo_single_product[$key4];
                  
             }   

          }

         }
            
        }
       return $product_list;
    }


/*******
    //  Get product by id with promo applied

   public function ProductWithPromo($product_id){
   
        // get category of product
        $fields=['pro_product.id AS id',
                 'pro_product.code AS code',
                 'pro_product.name AS ProductName',
                 'pro_category.name AS CategoryName',
                 'category']; 

        $product = (new \yii\db\Query())
            ->select($fields)
            ->from('pro_product')
            ->join('INNER JOIN','pro_category','pro_product.category = pro_category.id') 
            ->where(['type'=>'1','pro_product.status'=>'1'])
            ->where(['pro_product.id' => $product_id])
            ->one();  
       
        // get elements of product
        $elements=PROController::GetGroupsAndElements([$product_id]);

        // add elements to record
        if(array_key_exists($product_id,$elements)){
            $product['elements'] = $elements[$product_id];
         } 
       
        // get format for the product
       $fields=['pro_product_format_cost.name AS name',
             'pro_product_format.value AS format',
             'pro_product_format_2.value AS format2',
             'pro_product_format_3.value AS format3',
             'pro_product_format_cost.value AS price'];

       $product_format=PROController::GetAllUnits($fields);
        
        // check if there's a format for this product
       if(array_key_exists($product_id,$product_format)){
          // apply
          $product['format'] = $product_format[$product_id];
       } else {
          // apply category format
          // groups - Search Full Category format and cost
          $fields=['pro_product_format_cost.name AS name',
                 'pro_product_format.value AS format',
                 'pro_product_format_2.value AS format2',
                 'pro_product_format_3.value AS format3',
                 'pro_product_format_cost.value AS factor'];
          $product_cat=PROController::GetAllUnitsCat($fields); 

          $product['format'] = $product_cat[$product['category']];
       }
     
       // 
       return $product;


   }


   ******/
   // App Categories middle_menu
   
   public function mobileCategoriesMenu(){
      
   
      // return fields
      $fields=['`id`AS `handle`','name','parent_id','level','concat("L",(level+1),"_",id) AS `toHandle`','concat("L",(level-1)) AS `fromHandle`','last'];

      // Get All Categories Array
          $product=PROController::getAllCategories($fields);
          
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
        /*  foreach ($product as $key => $value) {
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
          } */
        return $catArray;
      }
    /* low level */
    
    // all ingredients group by cat (number id)

    public function getAllIngredients($fields){

        // type = element
        // status = active 
        array_push($fields,'category');

        $rows = (new \yii\db\Query())
            ->select($fields)
            ->from('pro_product')
            ->join('INNER JOIN','pro_category','pro_product.category = pro_category.id') 
            ->where(['type'=>'1','pro_product.status'=>'1'])
           ->all();  
        // group by cat
        $result=array();
        foreach ($rows as $key => $value) {
              $result[$value['category']][]=$value;
        }
        return($result);
    }

    // all categories id->name array

    public function getAllCategories($fields){

        // status = active 
        
        array_push($fields,'parent_id');

        $rows = (new \yii\db\Query())
                    ->select($fields)
                    ->from('pro_category')
                    ->where(['status'=>'1'])
                    ->orderBy('level')
                    ->all();

                // group by level
                $result=array();$key=0;
                foreach ($rows as $key => $value) {
                        $result[$value['level']][$key]=$value;
                    } 
                return($result);
    }

    // all products groups and elements
    /*  [group] ->[name element1]
                ->[name element2]
                ->[name element3]
    */
    public function getGroupsAndElements($groups = null){
     
        // status = active
       $where=['OR'];
       foreach ($groups as $key => $value) {
           $where[]=['main'=>$value];
       } 
       
        $rows = (new \yii\db\Query()) 
                    ->select(['pro_product.id','pro_product.name','main','element'])
                    ->from('pro_product_group')
                    ->join('INNER JOIN','pro_product','pro_product_group.element = pro_product.id') 
                    ->where($where)
                    ->orderBy('main')
                    ->all();

                // group by group
                $result=array(); 
                foreach ($rows as $key => $value) {
                        $result[$value['main']][$key]=$value;
                    } 
                $result2=array();
                foreach ($result as $key => $value) {
                      foreach ($result[$key] as $key1 => $value1) {
                          $result2[$key][]=$value1['name'];
                          
                        }
                    }  

                return($result2); 
    }
    // groups who has elements
    /*  [group1]
        [group2]
    */ 
    public function getElementsAndGroups($elements = null){
         
            // status = active
           $where=['OR'];
           foreach ($elements as $key => $value) {
               $where[]=['element'=>$value];
           } 
           
            $rows = (new \yii\db\Query()) 
                        ->select(['id','main','element'])
                        ->from('pro_product_group')
                        ->where($where)
                        ->orderBy('element')
                        ->all();
 
                    // group by group
                    $result=array(); 
                    $result2=array();
                    $result3=array();
                    foreach ($rows as $key => $value) {
                            $result[$value['element']][$key]=$value;
                        }
                    foreach ($result as $key => $value) {
                          foreach ($result[$key] as $key1 => $value1) {
                              $result2[$key][]=$value1['main'];
                            }
                       } 
                    $result3=current($result2);  
                    foreach ($result2 as $key => $value) {
                             $result3=array_intersect($result3,$result2[$key]);
                         }
                   return($result3); 
        }

    
    /* all elements from a category with grouped elements     */

    public function getAllElements($cat=0,$fields){
        
        // cat=0 get all
        $where_data=",'pro_category.id'=>".$cat."]";
        $where_cmd=($cat!=0)?$where_data:"";
        
        // status = active 
        
        array_push($fields,'category');

        $rows = (new \yii\db\Query())
                    ->select($fields)
                    ->from('pro_product','pro_category')
                    ->join('INNER JOIN','pro_category','pro_product.category = pro_category.id') 
                    ->where(['pro_product.status'=>'1'.$where_cmd])
                    ->orderBy('category')
                    ->all();

                // group by Category
                $result=array();$key=0;
                foreach ($rows as $key => $value) {
                        $result[$value['category']][$key]=$value;
                    } 
        
        return($result);


    }

    /* format cost groups on product_id */

    public function getAllUnits($fields){

      array_push($fields,'product_id','pro_product_format_cost.id AS format_id');

      $rows = (new \yii\db\Query())
            ->select($fields)
            ->from('pro_product_format_cost','pro_product_format','pro_product_format_2','pro_product_format_3')
            ->join('INNER JOIN','pro_product_format','pro_product_format_cost.product_format_id = pro_product_format.id')
            ->join('LEFT OUTER JOIN','pro_product_format_2','pro_product_format_cost.product_format_2_id = pro_product_format_2.id')
            ->join('LEFT OUTER JOIN','pro_product_format_3','pro_product_format_cost.product_format_3_id = pro_product_format_3.id')
            ->orderBy('pro_product_format_cost.product_id')
            ->all();

            // group by product_id
            $result=array();$key=0;
            foreach ($rows as $key => $value) {
              $result[$value['product_id']][]=$value;
            }

      return $result;
    }

    /* unit cost groups on category_id */
    public function getAllUnitsCat($fields){

      array_push($fields,'category_id','pro_product_format_cost.id AS format_id');
       $rows = (new \yii\db\Query())
            ->select($fields)
            ->from('pro_product_format_cost','pro_product_format','pro_product_format_2','pro_product_format_3')
            ->join('INNER JOIN','pro_product_format','pro_product_format_cost.product_format_id = pro_product_format.id') 
            ->join('LEFT OUTER JOIN','pro_product_format_2','pro_product_format_cost.product_format_2_id = pro_product_format_2.id')
            ->join('LEFT OUTER JOIN','pro_product_format_3','pro_product_format_cost.product_format_3_id = pro_product_format_3.id')
            ->orderBy('pro_product_format_cost.product_id')
            ->all();
            // group by category_id
            $result=[];$key=0;
            foreach ($rows as $key => $value) {

              $result[$value['category_id']][]=$value;
            } 
      return $result;
    }
     
    /*
    *   test
    */
    public function actionUno(){

        //$product=ProProduct::findOne(5);
        $product=ProProduct::find()->asArray()->all();
        
        return ($product);
    //    echo $product->category0->name;

    }
 


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all ProProduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProProduct model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProProduct();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProProduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
