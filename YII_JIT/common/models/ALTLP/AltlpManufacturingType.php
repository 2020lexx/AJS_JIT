<?php
/**
 *
 * Copyright (c) 2017. @pablo
 *
 * Test Code
 */

namespace common\models\ALTLP;

use Yii;

/**
 * This is the model class for table "altlp_manufacturing_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $desc
 * @property string $timestamp
 *
 * @property AltlpCatTaskLength[] $altlpCatTaskLengths
 * @property AltlpPrdTaskLength[] $altlpPrdTaskLengths
 * @property AltlpQueueHistory[] $altlpQueueHistories
 */
class AltlpManufacturingType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'altlp_manufacturing_type';
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
            [['desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'this table is use to set a ready made product or jit'),
            'name' => Yii::t('app', 'Name'),
            'desc' => Yii::t('app', 'Desc'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpCatTaskLengths()
    {
        return $this->hasMany(AltlpCatTaskLength::className(), ['manufacturing_type' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpPrdTaskLengths()
    {
        return $this->hasMany(AltlpPrdTaskLength::className(), ['manufacturing_type' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAltlpQueueHistories()
    {
        return $this->hasMany(AltlpQueueHistory::className(), ['manufacturing_type' => 'id']);
    }
}
