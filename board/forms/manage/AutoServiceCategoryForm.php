<?php

namespace app\board\forms\manage;


use app\board\entities\AutoServiceCategory;
use yii\base\Model;
use yii\web\UploadedFile;

class AutoServiceCategoryForm extends Model
{
    public $name;
    public $photo;

    private $_category;

    public function __construct(AutoServiceCategory $category = null, array $config = [])
    {
        parent::__construct($config);
        $this->_category = $category;
        if ($category) {
            $this->name = $category->name;
            $this->photo = $category->getPhotoUrl();
        }
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            ['photo', 'image'],
        ];
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->photo = UploadedFile::getInstance($this, 'photo');
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'photo' => 'Фото',
        ];
    }
}