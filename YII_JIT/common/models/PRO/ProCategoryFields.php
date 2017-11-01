<?php

namespace common\models\PRO;

use Yii;

/**
 * This is the model class for table "pro_category_fields".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $name
 * @property string $value
 * @property integer $parent_id
 * @property string $desc
 * @property string $timestamp
 *
 * @property ProCategory $category
 */
class ProCategoryFields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_category_fields';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'name', 'value'], 'required'],
            [['category_id', 'parent_id'], 'integer'],
            [['timestamp'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['value', 'desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'desc' => Yii::t('app', 'Desc'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ProCategory::className(), ['id' => 'category_id']);
    }
}
