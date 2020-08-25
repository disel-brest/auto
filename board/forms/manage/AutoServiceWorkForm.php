<?php

namespace app\board\forms\manage;


use app\board\entities\AutoServiceCategory;
use app\board\entities\AutoServiceWork;
use yii\base\Model;

class AutoServiceWorkForm extends Model
{
    public $category;
    public $name;

    private $_work;

    public function __construct(AutoServiceWork $work = null, array $config = [])
    {
        $this->_work = $work;
        if ($work) {
            $this->category = $work->category_id;
            $this->name = $work->name;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['category', 'name'], 'required'],
            [['category'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['category'], 'exist', 'skipOnError' => false, 'targetClass' => AutoServiceCategory::className(), 'targetAttribute' => ['category' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'category' => 'Категория',
            'name' => 'Название',
        ];
    }
}