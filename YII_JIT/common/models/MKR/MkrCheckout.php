<?php

namespace common\models\MKR;

use Yii;

/**
 * This is the model class for table "mkr_checkout".
 *
 * @property integer $id
 * @property string $checkout_id
 * @property integer $local_id
 * @property string $preferred_delivery_time
 * @property string $total_invoice
 * @property integer $status
 * @property string $realtime_coords
 * @property string $timestamp
 *
 * @property AltlpQueueView[] $altlpQueueViews
 * @property CsmCustomersLocal $local
 * @property MkrStatus $status0
 * @property MkrCheckoutItems[] $mkrCheckoutItems
 * @property MopMission[] $mopMissions
 */
class MkrCheckout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mkr_checkout';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['checkout_id', 'local_id', 'status'], 'required'],
            [['local_id', 'status'], 'integer'],
            [['preferred_delivery_time', 'timestamp'], 'safe'],
            [['checkout_id', 'total_invoice'], 'string', 'max' => 45],
            [['realtime_coords'], 'string', 'max' => 100],
            [['checkout_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'checkout_id' => Yii::t('app', 'Checkout ID'),
            'local_id' => Yii::t('app', 'Local ID'),
            'preferred_delivery_time' => Yii::t('app', 'Preferred Delivery Time'),
            'total_invoice' => Yii::t('app', 'Total Invoice'),
            'status' => Yii::t('app', 'Status'),
            'realtime_coords' => Yii::t('app', 'Realtime Coords'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpQueueViews()
    {
        return $this->hasMany(AltlpQueueView::className(), ['task_id' => 'checkout_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocal()
    {
        return $this->hasOne(CsmCustomersLocal::className(), ['id' => 'local_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(MkrStatus::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrCheckoutItems()
    {
        return $this->hasMany(MkrCheckoutItems::className(), ['checkout_id' => 'checkout_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMopMissions()
    {
        return $this->hasMany(MopMission::className(), ['checkout_id' => 'checkout_id']);
    }
}
