<?php

namespace common\models\MOP;

use Yii;

/**
 * This is the model class for table "mop_status_3".
 *
 * @property integer $id
 * @property integer $status_2_id
 * @property string $name
 * @property string $description
 * @property string $timestamp
 *
 * @property MopMission[] $mopMissions
 * @property MopStatus2 $status2
 */
class MopStatus3 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mop_status_3';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_2_id'], 'required'],
            [['status_2_id'], 'integer'],
            [['name', 'timestamp'], 'string', 'max' => 45],
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
            'status_2_id' => Yii::t('app', 'Status 2 ID'),
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
        return $this->hasMany(MopMission::className(), ['mop_status_3_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus2()
    {
        return $this->hasOne(MopStatus2::className(), ['id' => 'status_2_id']);
    }
}
