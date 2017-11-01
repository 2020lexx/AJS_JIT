<?php

namespace common\models\ZSA;

use Yii;

/**
 * This is the model class for table "zsa_customers_sites".
 *
 * @property integer $id
 * @property string $customers_code
 * @property integer $address_id
 * @property string $shops_code
 * @property string $distance_to
 * @property string $tiemstamp
 *
 * @property ZsaCustomers $customersCode
 * @property ZsaCustomersAddress $address
 * @property ZsaShops $shopsCode
 */
class ZsaCustomersSites extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zsa_customers_sites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customers_code', 'address_id', 'shops_code'], 'required'],
            [['address_id'], 'integer'],
            [['tiemstamp'], 'safe'],
            [['customers_code'], 'string', 'max' => 200],
            [['shops_code', 'distance_to'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customers_code' => Yii::t('app', 'Customers Code'),
            'address_id' => Yii::t('app', 'Address ID'),
            'shops_code' => Yii::t('app', 'Shops Code'),
            'distance_to' => Yii::t('app', 'Distance To'),
            'tiemstamp' => Yii::t('app', 'Tiemstamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomersCode()
    {
        return $this->hasOne(ZsaCustomers::className(), ['code' => 'customers_code']);
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
    public function getShopsCode()
    {
        return $this->hasOne(ZsaShops::className(), ['code' => 'shops_code']);
    }
}
