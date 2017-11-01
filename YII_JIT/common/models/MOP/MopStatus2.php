<?php

namespace common\models\MOP;

use Yii;

/**
 * This is the model class for table "mop_status_2".
 *
 * @property integer $id
 * @property integer $status_1_id
 * @property string $name
 * @property string $description
 * @property string $timestamp
 *
 * @property MopMission[] $mopMissions
 * @property MopStatus1 $status1
 * @property MopStatus3[] $mopStatus3s
 */
class MopStatus2 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mop_status_2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_1_id'], 'required'],
            [['status_1_id'], 'integer'],
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
            'status_1_id' => Yii::t('app', 'Status 1 ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMopMissions()
    {
        return $this->hasMany(MopMission::className(), ['mop_status_2_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus1()
    {
        return $this->hasOne(MopStatus1::className(), ['id' => 'status_1_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMopStatus3s()
    {
        return $this->hasMany(MopStatus3::className(), ['status_2_id' => 'id']);
    }
}
