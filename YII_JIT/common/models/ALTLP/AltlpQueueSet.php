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
 * This is the model class for table "altlp_queue_set".
 *
 * @property integer $id
 * @property string $queue_id
 * @property string $linked_queue
 * @property integer $queue_type
 * @property string $queue_name
 * @property integer $queue_length
 * @property integer $parallel_tasks
 * @property string $task_length
 * @property string $task_interval
 * @property string $timestamp
 *
 * @property AltlpCatTaskLength[] $altlpCatTaskLengths
 * @property AltlpPrdTaskLength[] $altlpPrdTaskLengths
 * @property AltlpQueueFfs[] $altlpQueueFfs
 * @property AltlpQueueHistory[] $altlpQueueHistories
 * @property AltlpQueueType $queueType
 * @property AltlpQueueView[] $altlpQueueViews
 */
class AltlpQueueSet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'altlp_queue_set';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['queue_type'], 'required'],
            [['queue_type', 'queue_length', 'parallel_tasks'], 'integer'],
            [['task_length', 'task_interval', 'timestamp'], 'safe'],
            [['queue_id', 'linked_queue'], 'string', 'max' => 45],
            [['queue_name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'queue_id' => Yii::t('app', 'Queue ID'),
            'linked_queue' => Yii::t('app', 'Linked Queue'),
            'queue_type' => Yii::t('app', 'Queue Type'),
            'queue_name' => Yii::t('app', 'Queue Name'),
            'queue_length' => Yii::t('app', 'Queue Length'),
            'parallel_tasks' => Yii::t('app', 'Parallel Tasks'),
            'task_length' => Yii::t('app', 'Task Length'),
            'task_interval' => Yii::t('app', 'Task Interval'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpCatTaskLengths()
    {
        return $this->hasMany(AltlpCatTaskLength::className(), ['queue_set_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpPrdTaskLengths()
    {
        return $this->hasMany(AltlpPrdTaskLength::className(), ['queue_set_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpQueueFfs()
    {
        return $this->hasMany(AltlpQueueFfs::className(), ['queue_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpQueueHistories()
    {
        return $this->hasMany(AltlpQueueHistory::className(), ['queue_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQueueType()
    {
        return $this->hasOne(AltlpQueueType::className(), ['id' => 'queue_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpQueueViews()
    {
        return $this->hasMany(AltlpQueueView::className(), ['queue_set_id' => 'id']);
    }
}
