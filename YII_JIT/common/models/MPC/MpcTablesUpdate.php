<?php

namespace common\models\MPC;

use Yii;

/**
 * This is the model class for table "mpc_tables_update".
 *
 * @property integer $id
 * @property string $table_name
 * @property string $table_timestamp
 * @property string $timestamp
 */
class MpcTablesUpdate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mpc_tables_update';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['table_name'], 'required'],
            [['table_timestamp', 'timestamp'], 'safe'],
            [['table_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'table_name' => Yii::t('app', 'Table Name'),
            'table_timestamp' => Yii::t('app', 'Table Timestamp'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }
}
