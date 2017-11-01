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
 * This is the model class for table "altlp_queue_view".
 *
 * @property integer $id
 * @property string $task_id
 * @property string $group_task_id
 * @property integer $queue_set_id
 * @property string $stimated_time_to_delivery
 * @property string $task_start
 * @property string $task_length
 * @property string $timestamp
 *
 * @property AltlpQueueHistory[] $altlpQueueHistories
 * @property AltlpDeliveryWaypoints $groupTask
 * @property AltlpQueueSet $queueSet
 */
class AltlpQueueView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'altlp_queue_view';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'group_task_id', 'queue_set_id'], 'required'],
            [['queue_set_id'], 'integer'],
            [['stimated_time_to_delivery', 'task_start', 'task_length', 'timestamp'], 'safe'],
            [['task_id', 'group_task_id'], 'string', 'max' => 45],
            [['task_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'task_id' => Yii::t('app', 'Task ID'),
            'group_task_id' => Yii::t('app', 'Group Task ID'),
            'queue_set_id' => Yii::t('app', 'Queue Set ID'),
            'stimated_time_to_delivery' => Yii::t('app', 'Stimated Time To Delivery'),
            'task_start' => Yii::t('app', 'Task Start'),
            'task_length' => Yii::t('app', 'Task Length'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpQueueHistories()
    {
        return $this->hasMany(AltlpQueueHistory::className(), ['task_id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupTask()
    {
        return $this->hasOne(AltlpDeliveryWaypoints::className(), ['group_task_id' => 'group_task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQueueSet()
    {
        return $this->hasOne(AltlpQueueSet::className(), ['id' => 'queue_set_id']);
    }
}
