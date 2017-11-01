<?php

namespace common\models\MKR;

use Yii;

/**
 * This is the model class for table "mkr_status".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $timestamp
 *
 * @property MkrCheckout[] $mkrCheckouts
 */
class MkrCheckoutStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mkr_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['timestamp'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrCheckouts()
    {
        return $this->hasMany(MkrCheckout::className(), ['status' => 'id']);
    }
}
