<?php

namespace common\models\ZSA;

use Yii;

/**
 * This is the model class for table "zsa_shops_shopping_hours".
 *
 * @property integer $id
 * @property string $shops_code
 * @property string $open_hour
 * @property string $close_hour
 * @property string $date_of_the_week
 * @property string $timestamp
 *
 * @property ZsaShops $shopsCode
 */
class ZsaShopsShoppingHours extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zsa_shops_shopping_hours';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shops_code'], 'required'],
            [['open_hour', 'close_hour', 'timestamp'], 'safe'],
            [['shops_code', 'date_of_the_week'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'shops_code' => Yii::t('app', 'Shops Code'),
            'open_hour' => Yii::t('app', 'Open Hour'),
            'close_hour' => Yii::t('app', 'Close Hour'),
            'date_of_the_week' => Yii::t('app', 'Date Of The Week'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopsCode()
    {
        return $this->hasOne(ZsaShops::className(), ['code' => 'shops_code']);
    }
}
