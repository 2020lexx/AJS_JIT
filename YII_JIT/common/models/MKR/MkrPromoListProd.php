<?php

namespace common\models\MKR;

use Yii;

/**
 * This is the model class for table "mkr_promo_list_prod".
 *
 * @property integer $id
 * @property integer $promo_list_id
 * @property integer $product_id
 * @property integer $product_format_cost_id
 * @property string $units
 * @property string $timestamp
 *
 * @property MkrPromoList $promoList
 * @property ProProduct $product
 * @property ProProductFormatCost $productFormatCost
 */
class MkrPromoListProd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mkr_promo_list_prod';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promo_list_id', 'product_id', 'product_format_cost_id'], 'required'],
            [['promo_list_id', 'product_id', 'product_format_cost_id'], 'integer'],
            [['timestamp'], 'safe'],
            [['units'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'promo_list_id' => Yii::t('app', 'Promo List ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'product_format_cost_id' => Yii::t('app', 'Product Format Cost ID'),
            'units' => Yii::t('app', 'Units'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromoList()
    {
        return $this->hasOne(MkrPromoList::className(), ['id' => 'promo_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(ProProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductFormatCost()
    {
        return $this->hasOne(ProProductFormatCost::className(), ['id' => 'product_format_cost_id']);
    }
}
