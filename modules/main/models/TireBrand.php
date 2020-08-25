<?php

namespace app\modules\main\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%tire_brand}}".
 *
 * @property integer $id
 * @property string $name
 *
 * @property TireModel[] $tireModels
 */
class TireBrand extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tire_brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return array
     */
    public static function itemsArray()
    {
        return ArrayHelper::map(TireBrand::find()->all(), 'id', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTireModels()
    {
        return $this->hasMany(TireModel::className(), ['brand_id' => 'id']);
    }
}
