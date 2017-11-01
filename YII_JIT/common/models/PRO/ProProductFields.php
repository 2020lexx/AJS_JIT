<?php

namespace common\models\PRO;

use Yii;

/**
 * This is the model class for table "pro_product_fields".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $name
 * @property string $value
 * @property integer $parent_id
 * @property string $desc
 * @property string $timestamp
 *
 * @property ProProduct $product
 */
class ProProductFields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_product_fields';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'name'], 'required'],
            [['id', 'product_id', 'parent_id'], 'integer'],
            [['timestamp'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['value', 'desc'], 'string', 'max' => 255]
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
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'desc' => Yii::t('app', 'Desc'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(ProProduct::className(), ['id' => 'product_id']);
    }
}
