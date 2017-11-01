<?php

namespace common\models\PRO;

use Yii;

/**
 * This is the model class for table "pro_product_mu".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $desc
 * @property string $timestamp
 *
 * @property ProProduct[] $proProducts
 */
class ProProductMu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_product_mu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timestamp'], 'safe'],
            [['code'], 'string', 'max' => 45],
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
            'desc' => Yii::t('app', 'Desc'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProProducts()
    {
        return $this->hasMany(ProProduct::className(), ['mu' => 'id']);
    }
}
