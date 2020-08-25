<?php

namespace app\helpers;


use app\board\helpers\PhotoHelper;
use app\modules\main\forms\AddCarForm;
use app\modules\main\forms\AddPartForm;
use app\modules\main\forms\AddTireForm;
use app\modules\main\forms\AddWheelForm;
use app\modules\main\models\Ad;
use app\modules\main\models\AdCar;
use app\modules\main\models\AdPart;
use app\modules\main\models\AdTire;
use app\modules\main\models\AdWheel;
use Yii;
use yii\bootstrap\Html;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class AdHelper
{
    /**
     * @param $ad Ad
     * @return string
     */
    public static function activeTimeString($ad)
    {
        $leftDays = $ad->activeTimeLeftInDays;
        $timeTag = $leftDays > 1 ? '<time>' . PluralForm::get($leftDays, "день", "дня", "дней") . '</time>' : '<time data-time-left="' . $ad->active_till . '"></time>';

        return "Объявление "
            . ($ad->status == Ad::STATUS_ACTIVE
                ? "активно ещё " . $timeTag
                : $ad->statusName);
    }

    public static function prolongAll($userId)
    {
        self::prolongByType(Ad::getAdClassByType(Ad::TYPE_PART), $userId);
        self::prolongByType(Ad::getAdClassByType(Ad::TYPE_CAR), $userId);
        self::prolongByType(Ad::getAdClassByType(Ad::TYPE_TIRE), $userId);
        self::prolongByType(Ad::getAdClassByType(Ad::TYPE_WHEEL), $userId);
    }

    /**
     * @param integer $condition
     * @return string
     */
    public static function getConditionColor($condition)
    {
        switch ($condition) {
            case 1: return 'one';
            case 2: return 'two';
            case 3: return 'three';
            case 4: return 'four';
            case 5: return 'five';
            case 6: return 'six';
            default: return 'six';
        }
    }

    /**
     * @param int $status
     * @return string
     */
    public static function statusLabel($status)
    {
        switch ($status) {
            case Ad::STATUS_WAITING:
                $class = 'label label-primary';
                break;
            case Ad::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            case Ad::STATUS_BANNED:
                $class = 'label label-danger';
                break;
            case Ad::STATUS_CLOSED:
                $class = 'label label-warning';
                break;
            case Ad::STATUS_DELETED:
                $class = 'label label-default';
                break;
            case Ad::STATUS_INACTIVE:
                $class = 'label label-info';
                break;
            case Ad::STATUS_PREVIEW:
                $class = 'label label-default';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(Ad::getStatusesArray(), $status), [
            'class' => $class,
        ]);
    }

    /**
     * @return array
     */
    public static function getCategoriesArray()
    {
        $cats = [];
        /*foreach (AutoHelper::getPartsCategories() as $category) {
            $cats[$category['id']] = $category['title'];
            if (isset($category['sub_categories'])) {
                foreach ($category['sub_categories'] as $subCategory) {
                    $cats[$subCategory['id']] = $subCategory['title'];
                }
            }
        }*/
        foreach (AutoHelper::getPartsCategories() as $category) {
            if (isset($category['sub_categories'])) {
                $subCats = [];
                foreach ($category['sub_categories'] as $subCategory) {
                    $subCats[$subCategory['id']] = $subCategory['title'];
                }
                $cats[$category['title']] = $subCats;
            } else {
                $cats[$category['id']] = $category['title'];
            }
        }

        return $cats;
    }

    /**
     * @param $form AddCarForm|AddTireForm|AddWheelForm
     * @param $ad AdCar|AdTire|AdWheel
     * @return bool
     */
    public static function savePhotos($form, $ad)
    {
        /*if (is_array($form->photo)) {
            $newPhotos = null;
            for ($i = 0; $i < 5; $i++) {
                //foreach ($this->photo as $k => $photo) {
                $photo = UploadedFile::getInstanceByName($form->formName() . '[photo][' . $i . ']');
                if ($photo instanceof UploadedFile) {
                    $photoName = md5(time() . $i . rand(1, 300)) . "." . $photo->extension;
                    if (!$photo->saveAs(Yii::getAlias("@webroot/images/users/" . $ad->user_id . "/" . $photoName))) {
                        $form->addError("photo", "Ошибка сохранения фото на сервере");
                        if (is_array($newPhotos)) {
                            foreach ($newPhotos as $newPhoto) {
                                @unlink(Yii::getAlias("@webroot/images/users/" . $ad->user_id . "/" . $newPhoto));
                            }
                        }
                        return false;
                    } else {
                        $newPhotos[$i] = PhotoHelper::createAdImages(Yii::getAlias("@webroot/images/users/" . $ad->user_id . "/" . $photoName), $ad::type());
                    }
                }
            }

            if (is_array($newPhotos)) {
                $photos = [];
                if (!$ad->isNewRecord) {
                    $oldPhotos = $ad->getPhotos();
                    $photos = $newPhotos + $oldPhotos;
                    ksort($photos);

                    // Удаление
                    foreach ($oldPhotos as $photo) {
                        if (!in_array($photo, $photos)) {
                            PhotoHelper::removePhotos($photo, $ad->filesPath);
                            //@unlink($ad->filesPath . "/" . $photo);
                        }
                    }
                } else {
                    $photos = $newPhotos;
                }

                $result = [];
                foreach ($photos as $photo) {
                    $result[] = $photo;
                }

                $ad->photo = serialize($result);
            }
        }*/

        if (is_array($form->photo)) {
            $oldPhotos = $ad->getPhotos();
            $newPhotos = [];
            $tmpPath = Yii::getAlias('@webroot/tmp/');
            $path = Yii::getAlias("@webroot/images/users/" . $ad->user_id . "/");
            foreach ($form->photo as $photo) {
                if ($photo) {
                    $newPhotos[] = str_replace('old/', '', $photo);
                    if (substr($photo, 0, 4) != 'old/' && is_file($tmpPath . $photo)) {
                        $fileName = pathinfo($photo, PATHINFO_FILENAME);
                        $ext = pathinfo($photo, PATHINFO_EXTENSION);
                        rename($tmpPath . $photo, $path . $photo);
                        if (is_file($tmpPath . $fileName . PhotoHelper::TYPE_LK . '.' . $ext)) {
                            rename($tmpPath . $fileName . PhotoHelper::TYPE_LK . '.' . $ext, $path . $fileName . PhotoHelper::TYPE_LK . '.' . $ext);
                        }
                        if (is_file($tmpPath . $fileName . PhotoHelper::TYPE_MN . '.' . $ext)) {
                            rename($tmpPath . $fileName . PhotoHelper::TYPE_MN . '.' . $ext, $path . $fileName . PhotoHelper::TYPE_MN . '.' . $ext);
                        }
                        if (is_file($tmpPath . $fileName . PhotoHelper::TYPE_LT . '.' . $ext)) {
                            rename($tmpPath . $fileName . PhotoHelper::TYPE_LT . '.' . $ext, $path . $fileName . PhotoHelper::TYPE_LT . '.' . $ext);
                        }
                        if (is_file($tmpPath . $fileName . PhotoHelper::TYPE_TH . '.' . $ext)) {
                            rename($tmpPath . $fileName . PhotoHelper::TYPE_TH . '.' . $ext, $path . $fileName . PhotoHelper::TYPE_TH . '.' . $ext);
                        }
                    }
                }
            }

            foreach ($oldPhotos as $oldPhoto) {
                if (!in_array($oldPhoto, $newPhotos)) {
                    PhotoHelper::removePhotos($oldPhoto, substr($path, 0, -1));
                }
            }

            $ad->photo = serialize($newPhotos);
        }

        return true;
    }

    /**
     * @param Ad $ad
     */
    private static function prolongByType($ad, $userId)
    {
        $adIDs = [];
        foreach ($ad::find()->where([
            'status' => [Ad::STATUS_ACTIVE, Ad::STATUS_INACTIVE],
            'user_id' => $userId,
        ])->each() as $ad) {
            $adIDs[] = $ad->id;
        }
        if ($adIDs) {
            $ad::updateAll([
                'active_till' => time() + Yii::$app->params['ad.defaultActiveTime'],
                'status' => Ad::STATUS_ACTIVE
            ], ['id' => $adIDs]);
        }
    }
}