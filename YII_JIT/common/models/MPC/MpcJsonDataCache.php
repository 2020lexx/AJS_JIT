<?php

namespace common\models\MPC;

use Yii;

/**
 * This is the model class for table "mpc_json_data_cache".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $var_name
 * @property string $desc
 * @property string $data
 * @property string $modified
 * @property string $timestamp
 */
class MpcJsonDataCache extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mpc_json_data_cache';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data'], 'string'],
            [['modified', 'timestamp'], 'safe'],
            [['code', 'name', 'var_name'], 'string', 'max' => 45],
            [['desc'], 'string', 'max' => 500],
            [['code'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'var_name' => Yii::t('app', 'Var Name'),
            'desc' => Yii::t('app', 'Desc'),
            'data' => Yii::t('app', 'Data'),
            'modified' => Yii::t('app', 'Modified'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }
}
