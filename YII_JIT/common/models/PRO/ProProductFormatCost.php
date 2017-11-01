<?php

namespace common\models\PRO;

use Yii;

/**
 * This is the model class for table "pro_product_format_cost".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $product_format_id
 * @property integer $product_format_2_id
 * @property integer $product_format_3_id
 * @property integer $category_id
 * @property string $sku
 * @property string $name
 * @property string $value
 * @property string $desc
 * @property integer $status
 * @property string $images
 * @property string $timestamp
 *
 * @property MkrCheckoutItems[] $mkrCheckoutItems
 * @property MkrPromoCategories[] $mkrPromoCategories
 * @property MkrPromoListProd[] $mkrPromoListProds
 * @property MkrPromoSingleProd[] $mkrPromoSingleProds
 * @property ProProductFormat3 $productFormat3
 * @property ProCategory $category
 * @property ProProduct $product
 * @property ProProductStatus $status0
 * @property ProProductFormat $productFormat
 * @property ProProductFormat2 $productFormat2
 */
class ProProductFormatCost extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_product_format_cost';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'product_format_id', 'product_format_2_id', 'product_format_3_id', 'category_id', 'status'], 'required'],
            [['product_id', 'product_format_id', 'product_format_2_id', 'product_format_3_id', 'category_id', 'status'], 'integer'],
            [['timestamp'], 'safe'],
            [['sku'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['value'], 'string', 'max' => 45],
            [['desc'], 'string', 'max' => 500],
            [['images'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'product_format_id' => Yii::t('app', 'Product Format ID'),
            'product_format_2_id' => Yii::t('app', 'Product Format 2 ID'),
            'product_format_3_id' => Yii::t('app', 'Product Format 3 ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'sku' => Yii::t('app', 'Sku'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
            'desc' => Yii::t('app', 'Desc'),
            'status' => Yii::t('app', 'Status'),
            'images' => Yii::t('app', 'Images'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrCheckoutItems()
    {
        return $this->hasMany(MkrCheckoutItems::className(), ['product_unit_cost_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrPromoCategories()
    {
        return $this->hasMany(MkrPromoCategories::className(), ['product_unit_cost_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrPromoListProds()
    {
        return $this->hasMany(MkrPromoListProd::className(), ['product_unit_cost_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrPromoSingleProds()
    {
        return $this->hasMany(MkrPromoSingleProd::className(), ['product_unit_cost_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductFormat3()
    {
        return $this->hasOne(ProProductFormat3::className(), ['id' => 'product_format_3_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ProCategory::className(), ['id' => 'category_id']);
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
    public function getStatus0()
    {
        return $this->hasOne(ProProductStatus::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductFormat()
    {
        return $this->hasOne(ProProductFormat::className(), ['id' => 'product_format_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductFormat2()
    {
        return $this->hasOne(ProProductFormat2::className(), ['id' => 'product_format_2_id']);
    }
}
