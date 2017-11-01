<?php

namespace common\models\PRO;

use Yii;

/**
 * This is the model class for table "pro_category".
 *
 * @property integer $id
 * @property string $code
 * @property integer $parent_id
 * @property string $name
 * @property string $title
 * @property string $short_desc
 * @property string $long_desc
 * @property string $meta_desc
 * @property string $images
 * @property string $tags
 * @property integer $status
 * @property integer $level
 * @property string $last
 * @property string $timestamp
 *
 * @property AltlpCatTaskLength[] $altlpCatTaskLengths
 * @property MkrPromoCategories[] $mkrPromoCategories
 * @property MkrPromoSingleProd[] $mkrPromoSingleProds
 * @property ProCategoryStatus $status0
 * @property ProCategoryFields[] $proCategoryFields
 * @property ProProduct[] $proProducts
 * @property ProProductCat[] $proProductCats
 * @property ProProductFormatCost[] $proProductFormatCosts
 */
class ProCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'status', 'level'], 'required'],
            [['parent_id', 'status', 'level'], 'integer'],
            [['timestamp'], 'safe'],
            [['code'], 'string', 'max' => 45],
            [['name', 'title', 'meta_desc'], 'string', 'max' => 255],
            [['short_desc'], 'string', 'max' => 500],
            [['long_desc', 'images', 'tags'], 'string', 'max' => 1000],
            [['last'], 'string', 'max' => 1]
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
            'parent_id' => Yii::t('app', 'Parent ID'),
            'name' => Yii::t('app', 'Name'),
            'title' => Yii::t('app', 'Title'),
            'short_desc' => Yii::t('app', 'Short Desc'),
            'long_desc' => Yii::t('app', 'Long Desc'),
            'meta_desc' => Yii::t('app', 'Meta Desc'),
            'images' => Yii::t('app', 'Images'),
            'tags' => Yii::t('app', 'Tags'),
            'status' => Yii::t('app', 'Status'),
            'level' => Yii::t('app', 'Level'),
            'last' => Yii::t('app', 'Last'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpCatTaskLengths()
    {
        return $this->hasMany(AltlpCatTaskLength::className(), ['pro_category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrPromoCategories()
    {
        return $this->hasMany(MkrPromoCategories::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrPromoSingleProds()
    {
        return $this->hasMany(MkrPromoSingleProd::className(), ['product_category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(ProCategoryStatus::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProCategoryFields()
    {
        return $this->hasMany(ProCategoryFields::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProducts()
    {
        return $this->hasMany(ProProduct::className(), ['category' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProductCats()
    {
        return $this->hasMany(ProProductCat::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProductFormatCosts()
    {
        return $this->hasMany(ProProductFormatCost::className(), ['category_id' => 'id']);
    }
}
