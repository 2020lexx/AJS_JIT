<?php

namespace common\models\ZSA;

use Yii;

/**
 * This is the model class for table "zsa_customers_app_id".
 *
 * @property integer $id
 * @property string $code
 * @property string $app_id
 * @property string $last_access
 * @property integer $status
 * @property string $timestamp
 *
 * @property CsmCustomersLocal[] $csmCustomersLocals
 * @property ZsaCustomers $code0
 * @property ZsaCustomersStatus $status0
 */
class ZsaCustomersAppId extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zsa_customers_app_id';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'status'], 'required'],
            [['last_access', 'timestamp'], 'safe'],
            [['status'], 'integer'],
            [['code'], 'string', 'max' => 200],
            [['app_id'], 'string', 'max' => 45],
            [['app_id'], 'unique']
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
            'app_id' => Yii::t('app', 'App ID'),
            'last_access' => Yii::t('app', 'Last Access'),
            'status' => Yii::t('app', 'Status'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCsmCustomersLocals()
    {
        return $this->hasMany(CsmCustomersLocal::className(), ['app_id_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCode0()
    {
        return $this->hasOne(ZsaCustomers::className(), ['code' => 'code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(ZsaCustomersStatus::className(), ['id' => 'status']);
    }
}
