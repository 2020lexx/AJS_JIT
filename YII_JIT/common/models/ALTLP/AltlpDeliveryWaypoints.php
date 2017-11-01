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
 * This is the model class for table "altlp_delivery_waypoints".
 *
 * @property integer $id
 * @property integer $altlp_delivery_id
 * @property string $group_task_id
 * @property string $coords
 * @property string $time_to_delivery
 * @property string $distance_between_points
 * @property string $trip_distance
 * @property integer $status
 * @property string $shop_distance_route
 * @property string $shop_time_route
 * @property string $start_grouping
 * @property string $end_grouping
 * @property string $timestamp
 *
 * @property AltlpDelivery $altlpDelivery
 * @property MkrCheckout $groupTask
 * @property AltlpDeliveryStatus $status0
 * @property AltlpQueueView[] $altlpQueueViews
 */
class AltlpDeliveryWaypoints extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'altlp_delivery_waypoints';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['altlp_delivery_id', 'group_task_id', 'status'], 'required'],
            [['altlp_delivery_id', 'status'], 'integer'],
            [['time_to_delivery', 'shop_time_route', 'start_grouping', 'end_grouping', 'timestamp'], 'safe'],
            [['group_task_id', 'coords', 'distance_between_points', 'trip_distance', 'shop_distance_route'], 'string', 'max' => 45],
            [['group_task_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'altlp_delivery_id' => Yii::t('app', 'Altlp Delivery ID'),
            'group_task_id' => Yii::t('app', 'Group Task ID'),
            'coords' => Yii::t('app', 'Coords'),
            'time_to_delivery' => Yii::t('app', 'Time To Delivery'),
            'distance_between_points' => Yii::t('app', 'Distance Between Points'),
            'trip_distance' => Yii::t('app', 'Trip Distance'),
            'status' => Yii::t('app', 'Status'),
            'shop_distance_route' => Yii::t('app', 'Shop Distance Route'),
            'shop_time_route' => Yii::t('app', 'Shop Time Route'),
            'start_grouping' => Yii::t('app', 'Start Grouping'),
            'end_grouping' => Yii::t('app', 'End Grouping'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpDelivery()
    {
        return $this->hasOne(AltlpDelivery::className(), ['id' => 'altlp_delivery_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupTask()
    {
        return $this->hasOne(MkrCheckout::className(), ['checkout_id' => 'group_task_id']);
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
    public function getAltlpQueueViews()
    {
        return $this->hasMany(AltlpQueueView::className(), ['group_task_id' => 'group_task_id']);
    }
}
