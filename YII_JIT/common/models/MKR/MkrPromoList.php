<?php

namespace common\models\MKR;

use Yii;

/**
 * This is the model class for table "mkr_promo_list".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $desc
 * @property string $image
 * @property integer $status
 * @property string $date_init
 * @property string $date_end
 * @property string $price_total
 * @property string $timestamp
 *
 * @property MkrPromoStatus $status0
 * @property MkrPromoListProd[] $mkrPromoListProds
 */
class MkrPromoList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mkr_promo_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['status'], 'integer'],
            [['date_init', 'date_end', 'timestamp'], 'safe'],
            [['code', 'name', 'price_total'], 'string', 'max' => 45],
            [['desc'], 'string', 'max' => 500],
            [['image'], 'string', 'max' => 1000]
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
            'image' => Yii::t('app', 'Image'),
            'status' => Yii::t('app', 'Status'),
            'date_init' => Yii::t('app', 'Date Init'),
            'date_end' => Yii::t('app', 'Date End'),
            'price_total' => Yii::t('app', 'Price Total'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(MkrPromoStatus::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMkrPromoListProds()
    {
        return $this->hasMany(MkrPromoListProd::className(), ['promo_list_id' => 'id']);
    }
}
