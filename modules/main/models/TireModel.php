<?php

namespace app\modules\main\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%tire_model}}".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property string $name
 *
 * @property TireBrand $brand
 */
class TireModel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tire_model}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'name'], 'required'],
            [['brand_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['brand_id'], 'exist', 'skipOnError' => false, 'targetClass' => TireBrand::className(), 'targetAttribute' => ['brand_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_id' => 'Brand ID',
            'name' => 'Name',
        ];
    }

    /**
     * @param $id
     * @return array
     */
    public static function getModelsByBrand($id)
    {
        return $id ? ArrayHelper::map(static::findAll(['brand_id' => $id]), 'id', 'name') : [];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(TireBrand::className(), ['id' => 'brand_id']);
    }
}
