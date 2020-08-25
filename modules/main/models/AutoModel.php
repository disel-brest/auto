<?php

namespace app\modules\main\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%auto_model}}".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property string $name
 *
 * @property AutoBrand $brand
 */
class AutoModel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auto_model}}';
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
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => AutoBrand::className(), 'targetAttribute' => ['brand_id' => 'id']],
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
            'name' => 'Название',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(AutoBrand::className(), ['id' => 'brand_id']);
    }
}
