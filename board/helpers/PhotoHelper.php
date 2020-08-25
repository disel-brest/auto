<?php

namespace app\board\helpers;


use app\modules\main\models\Ad;
use Intervention\Image\ImageManager;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use yii\base\Model;
use yii\helpers\Html;

class PhotoHelper
{
    const TYPE_LK = '_lk';
    const TYPE_LT = '_lt';
    const TYPE_TH = '_th';
    const TYPE_MN = '_mn';

    public static function createAdImages($path, $adType)
    {
        $interventionManager = new ImageManager(['driver' => 'imagick']);
        $optimizer = OptimizerChainFactory::create();
        //$optimizer = $optimizerFactory->get();

        $dirName = pathinfo($path, PATHINFO_DIRNAME);
        $fileName = pathinfo($path, PATHINFO_FILENAME);

        $basicImage = $interventionManager->make($path)
            ->encode('jpg', 92);
        $basicImage->save($dirName . '/' . $fileName . '.jpg');
        $optimizer->optimize($dirName . '/' . $fileName . '.jpg');

        if ($dirName . '/' . $fileName . '.jpg' != $path) {
            unlink($path);
        }

        if ($adType == Ad::TYPE_PART) {
            $cabinetWidth = 72;
            $cabinetHeight = 42;
            $cabinetImage = $interventionManager->make($dirName . '/' . $fileName . '.jpg');
            $cabinetImage->fit($cabinetWidth, $cabinetHeight, function ($constraint) {
                $constraint->upsize();
            })->save($dirName . '/' . $fileName . self::TYPE_LK . '.jpg', 90);
            $optimizer->optimize($dirName . '/' . $fileName . self::TYPE_LK . '.jpg');
        }

        $cabinetImage = $interventionManager->make($dirName . '/' . $fileName . '.jpg');
        $cabinetWidth = 213;
        $cabinetHeight = 160;
        $cabinetImage->fit($cabinetWidth, $cabinetHeight, function ($constraint) {
            $constraint->upsize();
        })->save($dirName . '/' . $fileName . self::TYPE_LT . '.jpg', 90);
        $optimizer->optimize($dirName . '/' . $fileName . self::TYPE_LT . '.jpg');

        $thumbImage = $interventionManager->make($dirName . '/' . $fileName . '.jpg');
        $thumbImage->fit(114, 84, function ($constraint) {
            $constraint->upsize();
        })->save($dirName . '/' . $fileName . self::TYPE_TH . '.jpg', 90);
        $optimizer->optimize($dirName . '/' . $fileName . self::TYPE_TH . '.jpg');

        $mainImage = $interventionManager->make($dirName . '/' . $fileName . '.jpg');
        $mainImage->fit(513, 410, function ($constraint) {
            $constraint->upsize();
        })->save($dirName . '/' . $fileName . self::TYPE_MN . '.jpg', 90);
        $optimizer->optimize($dirName . '/' . $fileName . self::TYPE_MN . '.jpg');

        return $fileName . '.jpg';
    }

    public static function getNameFor($name, $for = "")
    {
        $fileName = pathinfo($name, PATHINFO_FILENAME);
        return $fileName . $for . '.' . pathinfo($name, PATHINFO_EXTENSION);
    }

    public static function removePhotos($photos, $filePath)
    {
        if (is_array($photos)) {
            foreach ($photos as $photo) {
                @unlink($filePath . '/' . $photo);
                @unlink($filePath . '/' . self::getNameFor($photo, self::TYPE_LK));
                @unlink($filePath . '/' . self::getNameFor($photo, self::TYPE_LT));
                @unlink($filePath . '/' . self::getNameFor($photo, self::TYPE_TH));
                @unlink($filePath . '/' . self::getNameFor($photo, self::TYPE_MN));
            }
        } else {
            @unlink($filePath . '/' . $photos);
            @unlink($filePath . '/' . self::getNameFor($photos, self::TYPE_LK));
            @unlink($filePath . '/' . self::getNameFor($photos, self::TYPE_LT));
            @unlink($filePath . '/' . self::getNameFor($photos, self::TYPE_TH));
            @unlink($filePath . '/' . self::getNameFor($photos, self::TYPE_MN));
        }
    }

    public static function renderPhotosForm($type, $savedPhotos, Model $form)
    {
        for ($i = 1; $i < 6; $i++) {
            ?>
            <div class="add-photo" data-num="<?= $i ?>" data-type="<?= $type ?>">
                <div class="photo-btn-delete<?= isset($savedPhotos[$i-1]) ? ' shown' : '' ?>">X</div>
                <div class="add-photo-img">
                    <?php if (isset($savedPhotos[$i-1])) : ?>
                        <img src="<?= $savedPhotos[$i-1] ?>">
                    <?php else : ?>
                        <p>Прикрепить фото <?= $i ?></p>
                    <?php endif; ?>
                </div>
                <input type="file" class="hidden car-photo-file-input">
                <?= Html::activeHiddenInput($form, 'photo[' . ($i-1) . ']', ['id' => 'photo-hidden-' . $i]) ?>
                <?php //= $addTireForm->field($formModel, 'photo[]')->hiddenInput(['id' => 'photo-hidden-' . $i])->label(false) ?>
            </div>
            <?php
        }
        ?><div class="hidden help-block help-block-error" id="photo-error-block">Добавьте хотя бы одну фотографию</div><?php
    }
}