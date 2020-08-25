<?php

namespace app\modules\main\forms;

use app\board\helpers\PhotoHelper;
use app\helpers\AutoHelper;
use app\modules\main\models\Ad;
use app\modules\main\models\AdPart;
use app\modules\main\models\AutoBrand;
use app\modules\main\models\AutoModel;
use app\modules\main\models\City;
use app\modules\user\models\User;
use Yii;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

/**
 * @property User $_user
 */
class PartForm extends Model
{
    public $brand_id;
    public $model_id;
    public $fuel_id;
    public $engine_volume;
    public $year;
    public $body_style;
    public $category_id;
    public $name;
    public $description;
    public $price;
    public $photoUpload;
    public $toRemove;

    private $_adPart;

    public function __construct(AdPart $adPart, array $config = [])
    {
        $this->_adPart = $adPart;
        parent::__construct($config);
    }

    public function init()
    {
        $this->brand_id = $this->_adPart->brand_id;
        $this->model_id = $this->_adPart->model_id;
        $this->fuel_id = $this->_adPart->fuel_id;
        $this->engine_volume = $this->_adPart->engine_volume;
        $this->year = $this->_adPart->year;
        $this->body_style = $this->_adPart->body_style;
        $this->category_id = $this->_adPart->category_id;
        $this->name = $this->_adPart->name;
        $this->description = $this->_adPart->description;
        $this->price = $this->_adPart->price;
        parent::init();
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['brand_id', 'required', 'message' => 'Выберите марку авто'],
            ['brand_id', 'integer', 'min' => 1],
            ['brand_id', 'exist', 'skipOnError' => false, 'targetClass' => AutoBrand::className(), 'targetAttribute' => ['brand_id' => 'id']],

            ['model_id', 'required', 'message' => 'Выберите модель авто'],
            ['model_id', 'integer', 'min' => 1],
            ['model_id', 'exist', 'skipOnError' => false, 'targetClass' => AutoModel::className(), 'targetAttribute' => ['model_id' => 'id']],

            ['fuel_id', 'required', 'message' => 'Выберите тип двигателя'],
            ['fuel_id', 'integer', 'min' => 1],
            ['fuel_id', 'in', 'range' => array_keys(AutoHelper::FUEL_TYPES)],

            ['engine_volume', 'required', 'message' => 'Выберите объём двигателя'],
            ['engine_volume', 'integer', 'min' => 1],
            ['engine_volume', 'in', 'range' => array_keys(AutoHelper::ENGINE_VOLUMES)],

            ['year', 'required', 'message' => 'Укажите год выпуска'],
            ['year', 'integer', 'min' => 1950, 'max' => date('Y')],

            ['body_style', 'required', 'message' => 'Выберите тип кузова'],
            ['body_style', 'integer', 'min' => 1],
            ['body_style', 'in', 'range' => array_keys(AutoHelper::BODY_STYLES)],

            ['category_id', 'required'],
            ['category_id', 'integer', 'min' => 1],

            ['name', 'required'],
            ['name', 'string', 'max' => 100],

            ['description', 'string', 'max' => 255],

            ['photoUpload', 'each', 'rule' => ['file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 3, 'tooBig' => 'Максимальный размер фото - 3 МБ']],
            ['toRemove', 'each', 'rule' => ['string']],

            //['price', 'required'],
            ['price', 'integer'],
        ];
    }

    public function beforeValidate()
    {
        $photos = [];
        foreach ($this->photoUpload as $n => $photo) {
            $photos[$n] = UploadedFile::getInstanceByName($this->formName() . '[photoUpload][' . $n . ']');
        }
        $this->photoUpload = $photos;
        return parent::beforeValidate();
    }

    public function save()
    {
        if ($this->validate()) {
            $adPart = $this->_adPart;
            $adPart->brand_id = $this->brand_id;
            $adPart->model_id = $this->model_id;
            $adPart->fuel_id = $this->fuel_id;
            $adPart->engine_volume = $this->engine_volume;
            $adPart->year = $this->year;
            $adPart->body_style = $this->body_style;
            $adPart->category_id = $this->category_id;
            $adPart->name = $this->name;
            $adPart->description = $this->description;
            $adPart->price = $this->price;

            $photoArr = $adPart->photo;
            //print_r($this->photoUpload);exit;
            if (is_array($this->photoUpload)) {
                foreach ($this->photoUpload as $n => $photo) {
                    if ($photo instanceof UploadedFile) {
                        $oldPhoto = isset($adPart->photo[$n]) ? $adPart->photo[$n] : null;
                        $photoName = md5($adPart->id . "_" . time()) . "_" . $n . "." . $photo->extension;
                        $photoArr[$n] = $photoName;
                        if (!$photo->saveAs($adPart->getFilesPath() . "/" . $photoName)) {
                            return false;
                        }
                        //Сжатие фото и создание превьшек
                        $photoArr[$n] = PhotoHelper::createAdImages(Yii::getAlias("@webroot/images/users/" . $adPart->user_id . "/" . $photoName), Ad::TYPE_PART);

                        if ($oldPhoto) {
                            PhotoHelper::removePhotos($oldPhoto, $adPart->getFilesPath());
                        }
                    } else if (isset($this->toRemove[$n]) && $this->toRemove[$n] && isset($photoArr[$n]) && $photoArr[$n]) {
                        PhotoHelper::removePhotos($photoArr[$n], $adPart->getFilesPath());
                        unset($photoArr[$n]);
                    }
                }
            }
            /*if (!$this->_adPart->isNewRecord) {
                foreach ($this->toRemove as $n => $toRemove) {
                    if (!isset($photoArr[$n])) {
                        PhotoHelper::removePhotos($oldPhoto, $adPart->getFilesPath());
                    }
                }
            }*/

            $photos = [];
            foreach ($photoArr as $photo) {
                $photos[] = $photo;
            }
            $adPart->photo = $photos;
            Yii::debug($adPart->photo, 'EXIT ARRAY');

            if (!$adPart->save(false)) {
                Yii::debug($adPart->getErrors());
                return false;
            }

            return true;
        } else {
            Yii::debug($this->getErrors());
        }

        return false;
    }

    public function attributeLabels()
    {
        return [
            'brand_id' => 'Марка авто',
            'model_id' => 'Модель авто',
            'fuel_id' => 'Тип двигателя',
            'engine_volume' => 'Объём двигателя',
            'year' => 'Год выпуска авто',
            'body_style' => 'Кузов',
            'category_id' => 'Категория',
            'name' => 'Название',
            'description' => 'Описание',
            'price' => 'Цена в бел. рублях',
            'photoUpload' => 'Фото',
        ];
    }
}
