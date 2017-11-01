<?php

namespace common\models\MPC;

use Yii;

/**
 * This is the model class for table "mpc_json_product_cache".
 *
 * @property integer $id
 * @property string $product_id
 * @property string $code
 * @property string $name
 * @property string $data
 * @property string $timestamp
 */
class MpcJsonProductCache extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mpc_json_product_cache';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data'], 'string'],
            [['timestamp'], 'safe'],
            [['product_id', 'code', 'name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'data' => Yii::t('app', 'Data'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }
}
