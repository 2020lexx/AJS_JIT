<?php

namespace common\models\PRO;

use Yii;

/**
 * This is the model class for table "pro_product".
 *
 * @property integer $id
 * @property string $code
 * @property integer $parentID
 * @property string $name
 * @property integer $category
 * @property integer $type
 * @property string $short_desc
 * @property string $long_desc
 * @property string $title
 * @property string $meta_desc
 * @property string $sku
 * @property double $price
 * @property string $cart_desc
 * @property string $images
 * @property integer $mu
 * @property string $min_unit
 * @property string $tags
 * @property integer $status
 * @property string $timestamp
 *
 * @property AltlpPrdTaskLength[] $altlpPrdTaskLengths
 * @property MkrCheckoutItems[] $mkrCheckoutItems
 * @property MkrPromoListProd[] $mkrPromoListProds
 * @property MkrPromoSingleProd[] $mkrPromoSingleProds
 * @property ProCategory $category0
 * @property ProProductMu $mu0
 * @property ProProductStatus $status0
 * @property ProProductType $type0
 * @property ProProductCat[] $proProductCats
 * @property ProProductFields[] $proProductFields
 * @property ProProductFormatCost[] $proProductFormatCosts
 * @property ProProductGroup[] $proProductGroups
 * @property ProProductGroup[] $proProductGroups0
 */
class ProProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parentID', 'category', 'type', 'mu', 'status'], 'integer'],
            [['name', 'category', 'type', 'mu', 'min_unit', 'status'], 'required'],
            [['price'], 'number'],
            [['timestamp'], 'safe'],
            [['code', 'min_unit'], 'string', 'max' => 45],
            [['name', 'meta_desc', 'cart_desc'], 'string', 'max' => 255],
            [['short_desc'], 'string', 'max' => 500],
            [['long_desc', 'images', 'tags'], 'string', 'max' => 1000],
            [['title'], 'string', 'max' => 100],
            [['sku'], 'string', 'max' => 50],
            [['code'], 'unique']
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
            'parentID' => Yii::t('app', 'Parent ID'),
            'name' => Yii::t('app', 'Name'),
            'category' => Yii::t('app', 'Category'),
            'type' => Yii::t('app', 'Type'),
            'short_desc' => Yii::t('app', 'Short Desc'),
            'long_desc' => Yii::t('app', 'Long Desc'),
            'title' => Yii::t('app', 'Title'),
            'meta_desc' => Yii::t('app', 'Meta Desc'),
            'sku' => Yii::t('app', 'Sku'),
            'price' => Yii::t('app', 'Price'),
            'cart_desc' => Yii::t('app', 'Cart Desc'),
            'images' => Yii::t('app', 'Images'),
            'mu' => Yii::t('app', 'Mu'),
            'min_unit' => Yii::t('app', 'Min Unit'),
            'tags' => Yii::t('app', 'Tags'),
            'status' => Yii::t('app', 'Status'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpPrdTaskLengths()
    {
        return $this->hasMany(AltlpPrdTaskLength::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrCheckoutItems()
    {
        return $this->hasMany(MkrCheckoutItems::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrPromoListProds()
    {
        return $this->hasMany(MkrPromoListProd::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrPromoSingleProds()
    {
        return $this->hasMany(MkrPromoSingleProd::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory0()
    {
        return $this->hasOne(ProCategory::className(), ['id' => 'category']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMu0()
    {
        return $this->hasOne(ProProductMu::className(), ['id' => 'mu']);
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
    public function getType0()
    {
        return $this->hasOne(ProProductType::className(), ['id' => 'type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProductCats()
    {
        return $this->hasMany(ProProductCat::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProductFields()
    {
        return $this->hasMany(ProProductFields::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProductFormatCosts()
    {
        return $this->hasMany(ProProductFormatCost::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProductGroups()
    {
        return $this->hasMany(ProProductGroup::className(), ['main' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProductGroups0()
    {
        return $this->hasMany(ProProductGroup::className(), ['element' => 'id']);
    }
}
