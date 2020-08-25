<?php

namespace app\board\entities;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%auto_services_works_assignment}}".
 *
 * @property int $service_id
 * @property int $work_id
 * @property int $sort [int(11) unsigned]
 *
 * @property AutoServiceWork $work
 * @property AutoService $service
 */
class AutoServicesWorksAssignment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auto_services_works_assignment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_id', 'work_id'], 'required'],
            [['service_id', 'work_id', 'sort'], 'integer'],
            [['work_id'], 'exist', 'skipOnError' => true, 'targetClass' => AutoServiceWork::className(), 'targetAttribute' => ['work_id' => 'id']],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => AutoService::className(), 'targetAttribute' => ['service_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'service_id' => 'Service ID',
            'work_id' => 'Work ID',
            'sort' => 'Сортировка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWork()
    {
        return $this->hasOne(AutoServiceWork::className(), ['id' => 'work_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(AutoService::className(), ['id' => 'service_id']);
    }
}
