<?php

namespace common\models\CSM;

use Yii;

/**
 * This is the model class for table "csm_customers_local_status".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $timestamp
 *
 * @property CsmCustomersLocal[] $csmCustomersLocals
 */
class CsmCustomersLocalStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'csm_customers_local_status';
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
    public function getCsmCustomersLocals()
    {
        return $this->hasMany(CsmCustomersLocal::className(), ['status' => 'id']);
    }
}
