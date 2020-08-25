<?php

namespace app\board\entities;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%auto_service_works}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $name
 *
 * @property AutoServiceCategory $category
 * @property AutoServicesWorksAssignment[] $autoServicesWorksAssignments
 * @property AutoService[] $services
 */
class AutoServiceWork extends ActiveRecord
{
    public static function create($categoryID, $name)
    {
        return new static([
            'category_id' => $categoryID,
            'name' => $name
        ]);
    }

    public function edit($categoryID, $name){
        $this->category_id = $categoryID;
        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auto_service_works}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'name' => 'Название',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(AutoServiceCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutoServicesWorksAssignments()
    {
        return $this->hasMany(AutoServicesWorksAssignment::className(), ['work_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(AutoService::className(), ['id' => 'service_id'])->viaTable('{{%auto_services_works_assignment}}', ['work_id' => 'id']);
    }
}
