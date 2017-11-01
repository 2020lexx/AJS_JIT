<?php

namespace common\models\ZSA;

use Yii;

/**
 * This is the model class for table "zsa_shops_group".
 *
 * @property integer $id
 * @property string $shops_code_main
 * @property string $shops_code_location
 * @property string $timestamp
 *
 * @property ZsaShops $shopsCodeMain
 * @property ZsaShops $shopsCodeLocation
 */
class ZsaShopsGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zsa_shops_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shops_code_main', 'shops_code_location'], 'required'],
            [['timestamp'], 'safe'],
            [['shops_code_main', 'shops_code_location'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'shops_code_main' => Yii::t('app', 'Shops Code Main'),
            'shops_code_location' => Yii::t('app', 'Shops Code Location'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopsCodeMain()
    {
        return $this->hasOne(ZsaShops::className(), ['code' => 'shops_code_main']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopsCodeLocation()
    {
        return $this->hasOne(ZsaShops::className(), ['code' => 'shops_code_location']);
    }
}
