<?php

namespace common\models\PRO;

use Yii;

/**
 * This is the model class for table "pro_product_group".
 *
 * @property integer $id
 * @property integer $main
 * @property integer $element
 * @property string $timestamp
 *
 * @property ProProduct $main0
 * @property ProProduct $element0
 */
class ProProductGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_product_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['main', 'element'], 'required'],
            [['main', 'element'], 'integer'],
            [['timestamp'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'main' => Yii::t('app', 'Main'),
            'element' => Yii::t('app', 'Element'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMain0()
    {
        return $this->hasOne(ProProduct::className(), ['id' => 'main']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement0()
    {
        return $this->hasOne(ProProduct::className(), ['id' => 'element']);
    }
}
