<?php

namespace common\models\ZSA;

use Yii;

/**
 * This is the model class for table "zsa_shops".
 *
 * @property integer $id
 * @property string $code
 * @property string $logo
 * @property string $name
 * @property integer $status
 * @property string $description
 * @property string $phones
 * @property string $email
 * @property string $street_name
 * @property string $street_number
 * @property string $street_ids
 * @property string $coords
 * @property string $lat
 * @property string $lng
 * @property string $delivery_area
 * @property string $city
 * @property string $state
 * @property string $PO
 * @property string $country
 * @property string $timestamp
 *
 * @property ZsaCustomersSites[] $zsaCustomersSites
 * @property ZsaShopsStatus $status0
 * @property ZsaShopsGroup[] $zsaShopsGroups
 * @property ZsaShopsGroup[] $zsaShopsGroups0
 * @property ZsaShopsShoppingHours[] $zsaShopsShoppingHours
 */
class ZsaShops extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zsa_shops';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'status'], 'required'],
            [['status'], 'integer'],
            [['timestamp'], 'safe'],
            [['code', 'logo', 'street_number', 'lat', 'lng', 'delivery_area', 'state', 'PO', 'country'], 'string', 'max' => 45],
            [['name', 'description'], 'string', 'max' => 500],
            [['phones', 'email', 'street_name', 'street_ids'], 'string', 'max' => 255],
            [['coords'], 'string', 'max' => 100],
            [['city'], 'string', 'max' => 300],
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
            'logo' => Yii::t('app', 'Logo'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'description' => Yii::t('app', 'Description'),
            'phones' => Yii::t('app', 'Phones'),
            'email' => Yii::t('app', 'Email'),
            'street_name' => Yii::t('app', 'Street Name'),
            'street_number' => Yii::t('app', 'Street Number'),
            'street_ids' => Yii::t('app', 'Street Ids'),
            'coords' => Yii::t('app', 'Coords'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'delivery_area' => Yii::t('app', 'Delivery Area'),
            'city' => Yii::t('app', 'City'),
            'state' => Yii::t('app', 'State'),
            'PO' => Yii::t('app', 'Po'),
            'country' => Yii::t('app', 'Country'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZsaCustomersSites()
    {
        return $this->hasMany(ZsaCustomersSites::className(), ['shops_code' => 'code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(ZsaShopsStatus::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZsaShopsGroups()
    {
        return $this->hasMany(ZsaShopsGroup::className(), ['shops_code_main' => 'code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZsaShopsGroups0()
    {
        return $this->hasMany(ZsaShopsGroup::className(), ['shops_code_location' => 'code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZsaShopsShoppingHours()
    {
        return $this->hasMany(ZsaShopsShoppingHours::className(), ['shops_code' => 'code']);
    }
}
