<?php

namespace common\models\ZSA;

use Yii;

/**
 * This is the model class for table "zsa_customers".
 *
 * @property integer $id
 * @property string $code
 * @property string $last_name
 * @property string $first_name
 * @property string $email
 * @property string $password
 * @property string $birth
 * @property string $photo
 * @property string $phones
 * @property string $gender
 * @property string $customer_since
 * @property integer $status
 * @property string $last_access
 * @property integer $total_orders
 * @property string $timestamp
 *
 * @property CsmCustomersLocal[] $csmCustomersLocals
 * @property ZsaCustomersStatus $status0
 * @property ZsaCustomersAddress[] $zsaCustomersAddresses
 * @property ZsaCustomersAppId[] $zsaCustomersApps
 * @property ZsaCustomersSites[] $zsaCustomersSites
 */
class ZsaCustomers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zsa_customers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'last_name', 'email', 'password', 'customer_since', 'status'], 'required'],
            [['birth', 'customer_since', 'last_access', 'timestamp'], 'safe'],
            [['status', 'total_orders'], 'integer'],
            [['code', 'last_name', 'first_name', 'email'], 'string', 'max' => 200],
            [['password', 'photo'], 'string', 'max' => 45],
            [['phones'], 'string', 'max' => 255],
            [['gender'], 'string', 'max' => 10],
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
            'last_name' => Yii::t('app', 'Last Name'),
            'first_name' => Yii::t('app', 'First Name'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'birth' => Yii::t('app', 'Birth'),
            'photo' => Yii::t('app', 'Photo'),
            'phones' => Yii::t('app', 'Phones'),
            'gender' => Yii::t('app', 'Gender'),
            'customer_since' => Yii::t('app', 'Customer Since'),
            'status' => Yii::t('app', 'Status'),
            'last_access' => Yii::t('app', 'Last Access'),
            'total_orders' => Yii::t('app', 'Total Orders'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCsmCustomersLocals()
    {
        return $this->hasMany(CsmCustomersLocal::className(), ['code' => 'code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(ZsaCustomersStatus::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZsaCustomersAddresses()
    {
        return $this->hasMany(ZsaCustomersAddress::className(), ['code' => 'code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZsaCustomersApps()
    {
        return $this->hasMany(ZsaCustomersAppId::className(), ['code' => 'code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZsaCustomersSites()
    {
        return $this->hasMany(ZsaCustomersSites::className(), ['customers_code' => 'code']);
    }
}
