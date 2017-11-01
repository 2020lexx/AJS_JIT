<?php

namespace common\models\ZSA;

use Yii;

/**
 * This is the model class for table "zsa_customers_status".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $timestamp
 *
 * @property ZsaCustomers[] $zsaCustomers
 * @property ZsaCustomersAppId[] $zsaCustomersApps
 */
class ZsaCustomersStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zsa_customers_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
    public function getZsaCustomers()
    {
        return $this->hasMany(ZsaCustomers::className(), ['status' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZsaCustomersApps()
    {
        return $this->hasMany(ZsaCustomersAppId::className(), ['status' => 'id']);
    }
}
