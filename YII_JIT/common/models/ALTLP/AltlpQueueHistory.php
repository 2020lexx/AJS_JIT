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
 * This is the model class for table "altlp_queue_history".
 *
 * @property integer $id
 * @property string $task_id
 * @property integer $task_queue_id
 * @property integer $queue_id
 * @property string $task_start
 * @property string $task_index
 * @property string $task_thread
 * @property integer $task_status
 * @property string $task_length
 * @property string $task_priority
 * @property string $slot_rq_time
 * @property string $task_interval
 * @property string $linked_task
 * @property integer $manufacturing_type
 * @property string $timestamp
 *
 * @property AltlpQueueSet $queue
 * @property AltlpQueueStatus $taskStatus
 * @property AltlpQueueView $task
 * @property AltlpManufacturingType $manufacturingType
 */
class AltlpQueueHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'altlp_queue_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'queue_id', 'task_status', 'manufacturing_type'], 'required'],
            [['task_queue_id', 'queue_id', 'task_status', 'manufacturing_type'], 'integer'],
            [['task_start', 'task_length', 'slot_rq_time', 'task_interval', 'timestamp'], 'safe'],
            [['task_id', 'task_index', 'task_thread', 'task_priority', 'linked_task'], 'string', 'max' => 45]
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
            'task_queue_id' => Yii::t('app', 'Task Queue ID'),
            'queue_id' => Yii::t('app', 'Queue ID'),
            'task_start' => Yii::t('app', 'Task Start'),
            'task_index' => Yii::t('app', 'Task Index'),
            'task_thread' => Yii::t('app', 'Task Thread'),
            'task_status' => Yii::t('app', 'Task Status'),
            'task_length' => Yii::t('app', 'Task Length'),
            'task_priority' => Yii::t('app', 'Task Priority'),
            'slot_rq_time' => Yii::t('app', 'Slot Rq Time'),
            'task_interval' => Yii::t('app', 'Task Interval'),
            'linked_task' => Yii::t('app', 'Linked Task'),
            'manufacturing_type' => Yii::t('app', 'Manufacturing Type'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQueue()
    {
        return $this->hasOne(AltlpQueueSet::className(), ['id' => 'queue_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskStatus()
    {
        return $this->hasOne(AltlpQueueStatus::className(), ['id' => 'task_status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(AltlpQueueView::className(), ['task_id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturingType()
    {
        return $this->hasOne(AltlpManufacturingType::className(), ['id' => 'manufacturing_type']);
    }
}
