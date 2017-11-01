<?php

namespace common\models\MOP;

use Yii;

/**
 * This is the model class for table "mop_mission".
 *
 * @property integer $id
 * @property string $checkout_id
 * @property integer $mop_status_1_id
 * @property string $status_1_timestamp
 * @property integer $mop_status_2_id
 * @property string $status_2_timestamp
 * @property integer $mop_status_3_id
 * @property string $status_3_timestamp
 * @property string $timestamp
 *
 * @property MkrCheckout $checkout
 * @property MopStatus1 $mopStatus1
 * @property MopStatus2 $mopStatus2
 * @property MopStatus3 $mopStatus3
 */
class MopMission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mop_mission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['checkout_id', 'mop_status_1_id', 'mop_status_2_id', 'mop_status_3_id'], 'required'],
            [['mop_status_1_id', 'mop_status_2_id', 'mop_status_3_id'], 'integer'],
            [['status_1_timestamp', 'status_2_timestamp', 'status_3_timestamp', 'timestamp'], 'safe'],
            [['checkout_id'], 'string', 'max' => 45]
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
            'mop_status_1_id' => Yii::t('app', 'Mop Status 1 ID'),
            'status_1_timestamp' => Yii::t('app', 'Status 1 Timestamp'),
            'mop_status_2_id' => Yii::t('app', 'Mop Status 2 ID'),
            'status_2_timestamp' => Yii::t('app', 'Status 2 Timestamp'),
            'mop_status_3_id' => Yii::t('app', 'Mop Status 3 ID'),
            'status_3_timestamp' => Yii::t('app', 'Status 3 Timestamp'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckout()
    {
        return $this->hasOne(MkrCheckout::className(), ['checkout_id' => 'checkout_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMopStatus1()
    {
        return $this->hasOne(MopStatus1::className(), ['id' => 'mop_status_1_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMopStatus2()
    {
        return $this->hasOne(MopStatus2::className(), ['id' => 'mop_status_2_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMopStatus3()
    {
        return $this->hasOne(MopStatus3::className(), ['id' => 'mop_status_3_id']);
    }
}
