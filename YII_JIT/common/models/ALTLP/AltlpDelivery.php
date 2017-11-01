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
 * This is the model class for table "altlp_delivery".
 *
 * @property integer $id
 * @property integer $delivery_transport
 * @property integer $status
 * @property string $gps_track
 * @property string $timestamp
 *
 * @property AltlpDeliveryTransport $deliveryTransport
 * @property AltlpDeliveryStatus $status0
 * @property AltlpDeliveryWaypoints[] $altlpDeliveryWaypoints
 */
class AltlpDelivery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'altlp_delivery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_transport', 'status'], 'required'],
            [['delivery_transport', 'status'], 'integer'],
            [['timestamp'], 'safe'],
            [['gps_track'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'delivery_transport' => Yii::t('app', 'Delivery Transport'),
            'status' => Yii::t('app', 'Status'),
            'gps_track' => Yii::t('app', 'Gps Track'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryTransport()
    {
        return $this->hasOne(AltlpDeliveryTransport::className(), ['id' => 'delivery_transport']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(AltlpDeliveryStatus::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpDeliveryWaypoints()
    {
        return $this->hasMany(AltlpDeliveryWaypoints::className(), ['altlp_delivery_id' => 'id']);
    }
}
