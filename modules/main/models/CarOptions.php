<?php

namespace app\modules\main\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%car_options}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 *
 * @property CarOptions $category
 * @property CarOptionsAssignment[] $carOptionsAssignments
 * @property AdCar[] $adCars
 */
class CarOptions extends ActiveRecord
{
    const SCENARIO_CATEGORY_MANAGE = 'categoryManage';

    //public $categoryName;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_options}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'name'], 'required'],
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CATEGORY_MANAGE] = ['name'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'name' => 'Название',
        ];
    }

    /**
     * @return string
     */
    public function getCategoryName()
    {
        return $this->parent_id ? $this->category->name : "-";
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarOptionsAssignments()
    {
        return $this->hasMany(CarOptionsAssignment::className(), ['option_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdCars()
    {
        return $this->hasMany(AdCar::className(), ['id' => 'ad_car_id'])->viaTable('{{%car_options_assignment}}', ['option_id' => 'id']);
    }
}
