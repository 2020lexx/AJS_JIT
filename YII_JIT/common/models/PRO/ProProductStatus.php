<?php

namespace common\models\PRO;

use Yii;

/**
 * This is the model class for table "pro_product_status".
 *
 * @property integer $id
 * @property string $name
 * @property string $desc
 * @property string $timestamp
 *
 * @property ProProduct[] $proProducts
 * @property ProProductFormatCost[] $proProductFormatCosts
 */
class ProProductStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_product_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timestamp'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['desc'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'desc' => Yii::t('app', 'Desc'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProducts()
    {
        return $this->hasMany(ProProduct::className(), ['status' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProductFormatCosts()
    {
        return $this->hasMany(ProProductFormatCost::className(), ['status' => 'id']);
    }
}
