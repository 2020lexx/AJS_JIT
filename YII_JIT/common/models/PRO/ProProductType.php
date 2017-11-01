<?php

namespace common\models\PRO;

use Yii;

/**
 * This is the model class for table "pro_product_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $desc
 * @property string $timestamp
 *
 * @property ProProduct[] $proProducts
 */
class ProProductType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_product_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
        return $this->hasMany(ProProduct::className(), ['type' => 'id']);
    }
}
