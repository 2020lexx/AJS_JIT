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
 * This is the model class for table "altlp_prd_task_length".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $queue_set_id
 * @property integer $manufacturing_type
 * @property integer $queue_insert_on_group_end
 * @property string $task_length
 * @property string $task_interval
 * @property string $desc
 * @property string $timestamp
 *
 * @property AltlpManufacturingType $manufacturingType
 * @property AltlpQueueSet $queueSet
 * @property ProProduct $product
 */
class AltlpPrdTaskLength extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'altlp_prd_task_length';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'queue_set_id', 'manufacturing_type'], 'required'],
            [['product_id', 'queue_set_id', 'manufacturing_type', 'queue_insert_on_group_end'], 'integer'],
            [['task_length', 'task_interval', 'timestamp'], 'safe'],
            [['desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'queue_set_id' => Yii::t('app', 'if manufacturing type is: ready made then the queue_set_id show the queue where must be insert on the process'),
            'manufacturing_type' => Yii::t('app', 'if manufacturing type is: ready made then the queue_set_id show the queue where must be insert on the process'),
            'queue_insert_on_group_end' => Yii::t('app', 'Queue Insert On Group End'),
            'task_length' => Yii::t('app', 'Task Length'),
            'task_interval' => Yii::t('app', 'Task Interval'),
            'desc' => Yii::t('app', 'Desc'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturingType()
    {
        return $this->hasOne(AltlpManufacturingType::className(), ['id' => 'manufacturing_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQueueSet()
    {
        return $this->hasOne(AltlpQueueSet::className(), ['id' => 'queue_set_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(ProProduct::className(), ['id' => 'product_id']);
    }
}
