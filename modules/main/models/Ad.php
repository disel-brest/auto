<?php
/**
 * Common class for ad-models
 */

namespace app\modules\main\models;


use app\board\helpers\PhotoHelper;
use app\components\Cacher;
use app\helpers\AutoHelper;
use app\modules\user\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class Ad
 * @package app\modules\main\models
 *
 * @property int $status
 * @property integer $active_till
 * @property integer $user_id
 *
 * @property string $filesPath
 * @property string $fuelName
 * @property string $engineVolume
 * @property string $transmissionName
 * @property string $priceNormal
 * @property string $bodyStyle
 * @property float $activeTimeLeftInDays
 * @property string $mainPhoto
 * @property array $photos
 * @property string $isNewName
 */
class Ad extends ActiveRecord
{
    const STATUS_WAITING = 0;
    const STATUS_BANNED = 1;
    const STATUS_CLOSED = 2;
    const STATUS_DELETED = 3;
    const STATUS_PREVIEW = 4;
    const STATUS_INACTIVE = 5;
    const STATUS_ACTIVE = 10;

    const TYPE_PART = 1;
    const TYPE_CAR = 2;
    const TYPE_TIRE = 3;
    const TYPE_WHEEL = 4;

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->active_till = time() + Yii::$app->params['ad.defaultActiveTime'];
            }

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        /*foreach ($this->getPhotos() as $photo) {
            @unlink($this->filesPath . "/" . $photo);
        }*/
        PhotoHelper::removePhotos($this->getPhotos(), $this->filesPath);
        Cacher::updateAdCount($this->user_id);
        parent::afterDelete();
    }

    public function afterSave($insert, $changedAttributes)
    {
        Cacher::updateAdCount($this->user_id);
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return array
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_WAITING => 'Ожидает модерации',
            self::STATUS_BANNED => 'Бан',
            self::STATUS_CLOSED => 'Закрыто',
            self::STATUS_DELETED => 'Удалено',
            self::STATUS_PREVIEW => 'Предпросмотр',
            self::STATUS_INACTIVE => 'Неактивно',
            self::STATUS_ACTIVE => 'Активно',
        ];
    }

    public static function getTypesArray()
    {
        return [
            self::TYPE_PART => 'Запчасти',
            self::TYPE_CAR => 'Авто',
            self::TYPE_TIRE => 'Шины',
            self::TYPE_WHEEL => 'Диски',
        ];
    }

    public static function getTypeUrlsArray()
    {
        return [
            self::TYPE_PART => 'parts',
            self::TYPE_CAR => 'cars',
            self::TYPE_TIRE => 'tires',
            self::TYPE_WHEEL => 'wheels',
        ];
    }

    public static function type()
    {
        return null;
    }

    public function getItemViewPath()
    {
        switch ($this->type()) {
            case self::TYPE_PART:
                return ['@app/modules/main/views/parts/part_item', 'adPart'];
            case self::TYPE_CAR:
                return ['@app/modules/main/views/cars/car_item', 'adCar'];
            case self::TYPE_TIRE:
                return ['@app/modules/main/views/tires/tire_item', 'adTire'];
            case self::TYPE_WHEEL:
                return ['@app/modules/main/views/wheels/wheel_item', 'adWheel'];
            default: return null;
        }
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }

    /**
     * @param integer $type
     * @return string
     */
    public static function getTypeName($type)
    {
        return ArrayHelper::getValue(self::getTypesArray(), $type);
    }

    /**
     * @param integer $type
     * @return string
     */
    public static function getTypeUrlId($type)
    {
        return ArrayHelper::getValue(self::getTypeUrlsArray(), $type);
    }

    /**
     * @param int $type
     * @param int $id
     * @return string
     */
    public static function getUrl($type, $id)
    {
        return Url::to(['/main/' . self::getTypeUrlId($type) . '/view', 'id' => $id]);
    }

    /**
     * @param bool $url
     * @return bool|string
     */
    public function getFilesPath($url = false)
    {
        return Yii::getAlias(($url ? "@web" : "@webroot") . "/images/users/" . $this->user_id);
    }

    /**
     * @param bool $shot
     * @return string
     */
    public function getFuelName($shot = false)
    {
        return ArrayHelper::getValue($shot ? AutoHelper::FUEL_TYPES_SHOT : AutoHelper::FUEL_TYPES, $this->fuel_id, "-");
    }

    /**
     * @return string
     */
    public function getEngineVolume()
    {
        return ArrayHelper::getValue(AutoHelper::ENGINE_VOLUMES, $this->engine_volume, "-");
    }

    /**
     * @return string
     */
    public function getTransmissionName()
    {
        return ArrayHelper::getValue(AutoHelper::TRANSMISSION_TYPES, $this->transmission, "-");
    }

    /**
     * @param bool $usd
     * @return string
     */
    public function getPriceNormal($usd = false)
    {
        return !$usd
            ? number_format($this->price, 0, '.', ' ')
            //: number_format(Yii::$app->currency->exchange($this->price), 2, ".", " ");
            : number_format($this->price_usd, 0, '.', ' ');
    }

    public function getIsNewName()
    {
        return $this->is_new ? 'Новые' : 'б/у';
    }

    /**
     * @return string
     */
    public function getBodyStyle()
    {
        return ArrayHelper::getValue(AutoHelper::BODY_STYLES, $this->body_style, "-");
    }

    /**
     * @return float
     */
    public function getActiveTimeLeftInDays()
    {
        $time = $this->active_till - time();
        return ceil($time / 3600 / 24);
    }

    /**
     * @return string|ActiveRecord
     */
    public static function getAdClassByType($type)
    {
        switch ($type) {
            case Ad::TYPE_PART:
                $class = AdPart::className();
                break;
            case Ad::TYPE_CAR:
                $class = AdCar::className();
                break;
            case Ad::TYPE_TIRE:
                $class = AdTire::className();
                break;
            case Ad::TYPE_WHEEL:
                $class = AdWheel::className();
                break;
            default: $class = null;
        }

        if ($class === null) {
            throw new \InvalidArgumentException();
        }

        return $class;
    }

    /**
     * @return string
     */
    public function getMainPhoto()
    {
        if ($this->photo) {
            $photos = unserialize($this->photo);
            if (isset($photos[0]) && is_file($this->getFilesPath(). "/" . $photos[0])) {
                //return $this->getFilesPath(true). "/" . $photos[0];
                return $this->getFilesPath(true). "/" . PhotoHelper::getNameFor($photos[0], PhotoHelper::TYPE_LT);
            }
        }

        return Yii::getAlias("@web/images/empty-ad-image.png");
    }

    /**
     * @return array
     */
    public function getPhotos()
    {
        if (!is_array($this->photo)) {
            $photos = unserialize($this->photo);
        } else {
            $photos = $this->photo;
        }

        return is_array($photos) ? $photos : [];
    }

    /**
     * @return bool
     */
    public function hasPhoto()
    {
        foreach ($this->getPhotos() as $photo) {
            if (is_file($this->getFilesPath() . "/" . $photo)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function ban()
    {
        if ($this->updateAttributes(['status' => Ad::STATUS_BANNED])) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function close()
    {
        if ($this->updateAttributes(['status' => Ad::STATUS_CLOSED])) {
            return true;
        }

        return false;
    }

    /**
     * @return int
     */
    public function prolong()
    {
        return $this->updateAttributes([
            'active_till' => time() + Yii::$app->params['ad.defaultActiveTime'],
            'status' => Ad::STATUS_ACTIVE,
        ]);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'description' => 'Описание',
            'photo' => 'Фото',
            'price' => 'Цена',
            'priceNormal' => 'Цена',
            'status' => 'Статус',
            'statusName' => 'Статус',
            'views' => 'Просмотры',
            'created_at' => 'Дата',
            'updated_at' => 'Поледнее обновление',
            'active_till' => 'Активно до'
        ];
    }
}