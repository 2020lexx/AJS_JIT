<?php

namespace common\models\ZSA;

use Yii;

/**
 * This is the model class for table "zsa_customers_address".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $street_name
 * @property string $street_number
 * @property string $street_ids
 * @property string $coords
 * @property string $locality
 * @property string $area_level_3_long
 * @property string $area_level_3_short
 * @property string $area_level_2
 * @property string $area_level_1
 * @property string $postal_code
 * @property string $country
 * @property string $timestamp
 *
 * @property CsmCustomersLocal[] $csmCustomersLocals
 * @property ZsaCustomers $code0
 * @property ZsaCustomersSites[] $zsaCustomersSites
 */
class ZsaCustomersAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zsa_customers_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'street_name', 'locality', 'area_level_3_short', 'postal_code', 'country'], 'required'],
            [['timestamp'], 'safe'],
            [['code', 'country'], 'string', 'max' => 200],
            [['name', 'locality', 'area_level_3_long', 'area_level_2', 'area_level_1'], 'string', 'max' => 300],
            [['street_name', 'street_ids'], 'string', 'max' => 255],
            [['street_number', 'area_level_3_short', 'postal_code'], 'string', 'max' => 45],
            [['coords'], 'string', 'max' => 100]
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
            'street_name' => Yii::t('app', 'Street Name'),
            'street_number' => Yii::t('app', 'Street Number'),
            'street_ids' => Yii::t('app', 'Street Ids'),
            'coords' => Yii::t('app', 'Coords'),
            'locality' => Yii::t('app', 'Locality'),
            'area_level_3_long' => Yii::t('app', 'Area Level 3 Long'),
            'area_level_3_short' => Yii::t('app', 'Area Level 3 Short'),
            'area_level_2' => Yii::t('app', 'Area Level 2'),
            'area_level_1' => Yii::t('app', 'Area Level 1'),
            'postal_code' => Yii::t('app', 'Postal Code'),
            'country' => Yii::t('app', 'Country'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCsmCustomersLocals()
    {
        return $this->hasMany(CsmCustomersLocal::className(), ['address_id' => 'id']);
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
    public function getZsaCustomersSites()
    {
        return $this->hasMany(ZsaCustomersSites::className(), ['address_id' => 'id']);
    }
}
