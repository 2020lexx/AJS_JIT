<?php

namespace common\models\MKR;

use Yii;

/**
 * This is the model class for table "mkr_promo_status".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $timestamp
 *
 * @property MkrPromoCategories[] $mkrPromoCategories
 * @property MkrPromoList[] $mkrPromoLists
 * @property MkrPromoSingleProd[] $mkrPromoSingleProds
 */
class MkrPromoStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mkr_promo_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
    public function getMkrPromoCategories()
    {
        return $this->hasMany(MkrPromoCategories::className(), ['status' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrPromoLists()
    {
        return $this->hasMany(MkrPromoList::className(), ['status' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrPromoSingleProds()
    {
        return $this->hasMany(MkrPromoSingleProd::className(), ['status' => 'id']);
    }
}
