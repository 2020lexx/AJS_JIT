<?php

namespace common\models\MOP;

use Yii;

/**
 * This is the model class for table "mop_status_1".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $timestamp
 *
 * @property MopMission[] $mopMissions
 * @property MopStatus2[] $mopStatus2s
 */
class MopStatus1 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mop_status_1';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
        return $this->hasMany(MopMission::className(), ['mop_status_1_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMopStatus2s()
    {
        return $this->hasMany(MopStatus2::className(), ['status_1_id' => 'id']);
    }
}
