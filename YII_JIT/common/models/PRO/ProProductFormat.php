<?php

namespace common\models\PRO;

use Yii;

/**
 * This is the model class for table "pro_product_format".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $value
 * @property string $desc
 * @property string $timestamp
 *
 * @property ProProductFormat2[] $proProductFormat2s
 * @property ProProductFormatCost[] $proProductFormatCosts
 */
class ProProductFormat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_product_format';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timestamp'], 'safe'],
            [['code', 'value'], 'string', 'max' => 45],
            [['name'], 'string', 'max' => 255],
            [['desc'], 'string', 'max' => 500]
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
            'value' => Yii::t('app', 'Value'),
            'desc' => Yii::t('app', 'Desc'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProductFormat2s()
    {
        return $this->hasMany(ProProductFormat2::className(), ['product_format_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProductFormatCosts()
    {
        return $this->hasMany(ProProductFormatCost::className(), ['product_format_id' => 'id']);
    }
}
