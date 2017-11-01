<?php

namespace common\models\MKR;

use Yii;

/**
 * This is the model class for table "mkr_checkout_items".
 *
 * @property integer $id
 * @property string $checkout_id
 * @property string $item_id
 * @property integer $product_id
 * @property integer $product_unit_cost_id
 * @property string $items_number
 * @property string $timestamp
 *
 * @property MkrCheckout $checkout
 * @property ProProduct $product
 * @property ProProductFormatCost $productUnitCost
 */
class MkrCheckoutItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mkr_checkout_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['checkout_id', 'item_id', 'product_id', 'product_unit_cost_id'], 'required'],
            [['product_id', 'product_unit_cost_id'], 'integer'],
            [['timestamp'], 'safe'],
            [['checkout_id', 'item_id', 'items_number'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'checkout_id' => Yii::t('app', 'Checkout ID'),
            'item_id' => Yii::t('app', 'Item ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'product_unit_cost_id' => Yii::t('app', 'Product Unit Cost ID'),
            'items_number' => Yii::t('app', 'Items Number'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckout()
    {
        return $this->hasOne(MkrCheckout::className(), ['checkout_id' => 'checkout_id']);
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
    public function getProductUnitCost()
    {
        return $this->hasOne(ProProductFormatCost::className(), ['id' => 'product_unit_cost_id']);
    }
}
