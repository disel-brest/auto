<?php

namespace app\board\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%auto_service_categories}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $photo
 *
 * @property string $photoUrl
 *
 * @property AutoServiceWork[] $autoServiceWorks
 */
class AutoServiceCategory extends ActiveRecord
{
    public static function create($name)
    {
        return new static([
            'name' => $name,
        ]);
    }

    public function edit($name)
    {
        $this->name = $name;
    }

    public function savePhoto(UploadedFile $photo)
    {
        $oldPhoto = $this->photo;
        $oldFile = $this->getPhotoPath();
        $this->photo = 'cat_' . $this->id . '.' . $photo->extension;
        if (!$photo->saveAs(Yii::getAlias('@webroot/') . $this->getPhotoPath())) {
            throw new \DomainException("Ошибка сохранения фотографии");
        }

        if ($oldPhoto && $oldPhoto != $this->photo && is_file(Yii::getAlias('@webroot/') . $oldFile)) {
            unlink(Yii::getAlias('@webroot/') . $oldFile);
        }
    }

    public function getPhotoUrl()
    {
        return is_file(Yii::getAlias('@webroot/') . $this->getPhotoPath())
            ? Yii::getAlias('@web/') . $this->getPhotoPath() . '?v=' . filectime(Yii::getAlias('@webroot/') . $this->getPhotoPath())
            : Yii::getAlias('@web/images/auto-services/no-service-photo.jpg');
    }

    public function getPhotoPath()
    {
        return 'images/auto-services/' . $this->photo;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auto_service_categories}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'photo' => 'Фото',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutoServiceWorks()
    {
        return $this->hasMany(AutoServiceWork::className(), ['category_id' => 'id']);
    }
}
