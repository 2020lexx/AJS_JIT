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
 * This is the model class for table "altlp_queue_ffs".
 *
 * @property integer $id
 * @property integer $queue_id
 * @property string $task_thread
 * @property string $task_index
 * @property string $ffs_time
 * @property string $timestamp
 *
 * @property AltlpQueueSet $queue
 */
class AltlpQueueFfs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'altlp_queue_ffs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['queue_id'], 'required'],
            [['queue_id'], 'integer'],
            [['ffs_time', 'timestamp'], 'safe'],
            [['task_thread', 'task_index'], 'string', 'max' => 45]
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
            'task_thread' => Yii::t('app', 'Task Thread'),
            'task_index' => Yii::t('app', 'Task Index'),
            'ffs_time' => Yii::t('app', 'Ffs Time'),
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
}
