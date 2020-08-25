<?php

namespace app\modules\main\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%auto_brand}}".
 *
 * @property integer $id
 * @property string $name
 *
 * @property AutoModel[] $autoModels
 */
class AutoBrand extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auto_brand}}';
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
            'name' => 'Название',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutoModels()
    {
        return $this->hasMany(AutoModel::className(), ['brand_id' => 'id']);
    }
}
