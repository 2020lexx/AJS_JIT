<?php

namespace common\models\PRO;

use Yii;

/**
 * This is the model class for table "pro_product_format_2".
 *
 * @property integer $id
 * @property integer $product_format_id
 * @property string $code
 * @property string $name
 * @property string $value
 * @property string $desc
 * @property string $timestamp
 *
 * @property ProProductFormat $productFormat
 * @property ProProductFormat3[] $proProductFormat3s
 * @property ProProductFormatCost[] $proProductFormatCosts
 */
class ProProductFormat2 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_product_format_2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_format_id'], 'required'],
            [['product_format_id'], 'integer'],
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
            'product_format_id' => Yii::t('app', 'Product Format ID'),
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
    public function getProductFormat()
    {
        return $this->hasOne(ProProductFormat::className(), ['id' => 'product_format_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProductFormat3s()
    {
        return $this->hasMany(ProProductFormat3::className(), ['product_format_2_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProductFormatCosts()
    {
        return $this->hasMany(ProProductFormatCost::className(), ['product_format_2_id' => 'id']);
    }
}
