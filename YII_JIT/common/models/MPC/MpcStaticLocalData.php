<?php

namespace common\models\MPC;

use Yii;

/**
 * This is the model class for table "mpc_static_local_data".
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 * @property string $enable
 * @property string $timestamp
 */
class MpcStaticLocalData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mpc_static_local_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timestamp'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['value'], 'string', 'max' => 500],
            [['enable'], 'string', 'max' => 45]
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
            'value' => Yii::t('app', 'Value'),
            'enable' => Yii::t('app', 'Enable'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }
}
