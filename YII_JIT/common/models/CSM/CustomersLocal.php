<?php

namespace common\models\CSM;

use Yii;

/**
 * This is the model class for table "csm_customers_local".
 *
 * @property integer $id
 * @property string $code
 * @property integer $address_id
 * @property integer $app_id_id
 * @property string $timestamp
 *
 * @property ZsaCustomers $code0
 * @property ZsaCustomersAddress $address
 * @property ZsaCustomersAppId $appId
 * @property MkrCheckout[] $mkrCheckouts
 */
class CustomersLocal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'csm_customers_local';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'address_id', 'app_id_id'], 'required'],
            [['address_id', 'app_id_id'], 'integer'],
            [['timestamp'], 'safe'],
            [['code'], 'string', 'max' => 200]
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
            'address_id' => Yii::t('app', 'Address ID'),
            'app_id_id' => Yii::t('app', 'App Id ID'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
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
    public function getAddress()
    {
        return $this->hasOne(ZsaCustomersAddress::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppId()
    {
        return $this->hasOne(ZsaCustomersAppId::className(), ['id' => 'app_id_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrCheckouts()
    {
        return $this->hasMany(MkrCheckout::className(), ['local_id' => 'id']);
    }
}
