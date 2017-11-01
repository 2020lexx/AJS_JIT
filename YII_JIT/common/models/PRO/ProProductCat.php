<?php

namespace common\models\PRO;

use Yii;

/**
 * This is the model class for table "pro_product_cat".
 *
 * @property integer $id
 * @property string $timestamp
 * @property integer $category_id
 * @property integer $product_id
 *
 * @property ProCategory $category
 * @property ProProduct $product
 */
class ProProductCat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_product_cat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timestamp'], 'safe'],
            [['category_id', 'product_id'], 'required'],
            [['category_id', 'product_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'category_id' => Yii::t('app', 'Category ID'),
            'product_id' => Yii::t('app', 'Product ID'),
        ];
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
}
