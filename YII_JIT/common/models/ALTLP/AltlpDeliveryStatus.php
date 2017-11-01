<?php
/**
 *
 * Copyright (c) 2017. @pablo
 *
 * Test Code
 */

namespace common\models\ALTLP;

use Yii;

/**
 * This is the model class for table "altlp_delivery_status".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $timestamp
 *
 * @property AltlpDelivery[] $altlpDeliveries
 * @property AltlpDeliveryWaypoints[] $altlpDeliveryWaypoints
 */
class AltlpDeliveryStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'altlp_delivery_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timestamp'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['description'], 'string', 'max' => 255]
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
            'description' => Yii::t('app', 'Description'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpDeliveries()
    {
        return $this->hasMany(AltlpDelivery::className(), ['status' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpDeliveryWaypoints()
    {
        return $this->hasMany(AltlpDeliveryWaypoints::className(), ['status' => 'id']);
    }
}
