<?php

namespace common\models\MKR;

use Yii;

/**
 * This is the model class for table "mkr_promo_single_prod".
 *
 * @property integer $id
 * @property string $code
 * @property integer $product_id
 * @property integer $product_category_id
 * @property integer $product_format_cost_id
 * @property string $name
 * @property string $desc
 * @property string $image
 * @property string $date_init
 * @property string $date_end
 * @property integer $status
 * @property string $price
 * @property string $price_reduction
 * @property string $timestamp
 *
 * @property MkrPromoStatus $status0
 * @property ProCategory $productCategory
 * @property ProProduct $product
 * @property ProProductFormatCost $productFormatCost
 */
class MkrPromoSingleProd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mkr_promo_single_prod';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'product_category_id', 'product_format_cost_id', 'status'], 'required'],
            [['product_id', 'product_category_id', 'product_format_cost_id', 'status'], 'integer'],
            [['date_init', 'date_end', 'timestamp'], 'safe'],
            [['code', 'name', 'price', 'price_reduction'], 'string', 'max' => 45],
            [['desc'], 'string', 'max' => 500],
            [['image'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'product_id' => Yii::t('app', 'Product ID'),
            'product_category_id' => Yii::t('app', 'Product Category ID'),
            'product_format_cost_id' => Yii::t('app', 'Product Format Cost ID'),
            'name' => Yii::t('app', 'Name'),
            'desc' => Yii::t('app', 'Desc'),
            'image' => Yii::t('app', 'Image'),
            'date_init' => Yii::t('app', 'Date Init'),
            'date_end' => Yii::t('app', 'Date End'),
            'status' => Yii::t('app', 'Status'),
            'price' => Yii::t('app', 'Price'),
            'price_reduction' => Yii::t('app', 'Price Reduction'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(MkrPromoStatus::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategory()
    {
        return $this->hasOne(ProCategory::className(), ['id' => 'product_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(ProProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductFormatCost()
    {
        return $this->hasOne(ProProductFormatCost::className(), ['id' => 'product_format_cost_id']);
    }
}
