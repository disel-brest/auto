<?php

namespace app\components;


use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class PhotoSaver
{
    private $intervention;
    private $optimizer;

    public function __construct()
    {
        $this->intervention = new ImageManager(['driver' => 'imagick']);
        $this->optimizer = OptimizerChainFactory::create();
    }

    public function createRecipeImages($image)
    {
        //$optimizer->optimize($path . $name);
        //$extension = pathinfo($image, PATHINFO_EXTENSION);

        // Базовое изображение
        $basic = $this->intervention->make($image);

        // Основное изображение
        $main = clone $basic;
        $mainImage = $main->fit(870, 570, function ($constraint) {
            $constraint->upsize();
        });

        $small = clone $mainImage;

        $mainImage->insert(__DIR__ . '/watermark.png', 'bottom-right', 10, 10)
            ->save($image, 90);
        $this->optimizer->optimize($image);

        // Превью
        //$this->create300x200($image, $small);
        $this->fitManager($image, $small, 530, 353);
    }

    public function create300x200($image, Image $manager = null)
    {
        if ($manager === null) {
            $manager = $this->intervention->make($image);
        }
        $name = pathinfo($image, PATHINFO_BASENAME);
        $path = pathinfo($image, PATHINFO_DIRNAME) . '/';

        $manager->fit(300, 200, function ($constraint) {
                $constraint->upsize();
            })
            ->insert(__DIR__ . '/watermark.png', 'bottom-right', 10, 10)
            ->save($path . 'sm_' . $name, 90);
        $this->optimizer->optimize($path . 'sm_' . $name);
    }

    public function fitManager($image, Image $manager, $width, $height = null)
    {
        $name = pathinfo($image, PATHINFO_BASENAME);
        $path = pathinfo($image, PATHINFO_DIRNAME) . '/';

        $manager->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        })
            ->insert(__DIR__ . '/watermark.png', 'bottom-right', 10, 10)
            ->save($path . 'sm_' . $name, 90);
        $this->optimizer->optimize($path . 'sm_' . $name);
    }

    public function createStepImage($image)
    {
        $this->intervention->make($image)
            ->resize(620, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->insert(__DIR__ . '/watermark.png', 'bottom-right', 10, 10)
            ->save($image, 90);
        $this->optimizer->optimize($image);
    }

    public function fitBySize($image, $width, $height, $newName = null, $addWaterMark = false)
    {
        $saveTo = $newName ?: $image;
        $manager = $this->intervention->make($image)
            ->fit($width, $height, function ($constraint) {
                $constraint->upsize();
            });

        if ($addWaterMark) {
            $manager->insert(__DIR__ . '/watermark.png', 'bottom-right', 10, 10);
        }

        $manager->save($saveTo, 90);
        $this->optimizer->optimize($saveTo);
    }

    public function addWatermark($image)
    {
        $this->intervention->make($image)
            ->insert(__DIR__ . '/watermark.png', 'bottom-right', 10, 10)
            ->save($image, 90);
        $this->optimizer->optimize($image);
    }

    public function crop($path, $width, $height, $x, $y, $saveTo = null)
    {
        $this->intervention->make($path)
            ->crop($width, $height, $x, $y)
            ->save($saveTo ?: $path, 90);
        $this->optimizer->optimize($saveTo ?: $path);
    }
}