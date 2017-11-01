<?php

namespace common\models\PRO;

use Yii;

/**
 * This is the model class for table "pro_product_format_3".
 *
 * @property integer $id
 * @property integer $product_format_2_id
 * @property string $code
 * @property string $name
 * @property string $value
 * @property string $desc
 * @property string $timestamp
 *
 * @property ProProductFormat2 $productFormat2
 * @property ProProductFormatCost[] $proProductFormatCosts
 */
class ProProductFormat3 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_product_format_3';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_format_2_id'], 'required'],
            [['product_format_2_id'], 'integer'],
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
            'product_format_2_id' => Yii::t('app', 'Product Format 2 ID'),
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
    public function getProductFormat2()
    {
        return $this->hasOne(ProProductFormat2::className(), ['id' => 'product_format_2_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProductFormatCosts()
    {
        return $this->hasMany(ProProductFormatCost::className(), ['product_format_3_id' => 'id']);
    }
}
