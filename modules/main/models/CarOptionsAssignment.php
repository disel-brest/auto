<?php

namespace app\modules\main\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%car_options_assignment}}".
 *
 * @property integer $ad_car_id
 * @property integer $option_id
 *
 * @property CarOptions $option
 * @property AdCar $adCar
 */
class CarOptionsAssignment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_options_assignment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ad_car_id', 'option_id'], 'required'],
            [['ad_car_id', 'option_id'], 'integer'],
            [['option_id'], 'exist', 'skipOnError' => false, 'targetClass' => CarOptions::className(), 'targetAttribute' => ['option_id' => 'id']],
            [['ad_car_id'], 'exist', 'skipOnError' => false, 'targetClass' => AdCar::className(), 'targetAttribute' => ['ad_car_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ad_car_id' => 'Ad Car ID',
            'option_id' => 'Option ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(CarOptions::className(), ['id' => 'option_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdCar()
    {
        return $this->hasOne(AdCar::className(), ['id' => 'ad_car_id']);
    }
}
