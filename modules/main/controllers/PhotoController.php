<?php

namespace app\modules\main\controllers;


use app\board\helpers\PhotoHelper;
use app\rbac\Rbac;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\validators\ImageValidator;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class PhotoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['add', 'remove'],
                'rules' => [
                    [
                        //'actions' => ['add', 'remove'],
                        'allow' => true,
                        'roles' => [Rbac::PERMISSION_USER],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'upload' => ['post'],
                ],
            ],
        ];
    }

    public function actionUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $validator = new ImageValidator([
            'extensions' => 'jpg, jpeg, gif, png',
            'maxSize' => 1024 * 1024 * 5,
            'tooBig' => "Размер фото не должен превышать 5 мегабайт",
            'wrongExtension' => 'Недопустимый формат файла'
        ]);
        $file = UploadedFile::getInstanceByName('file');
        //return $file;

        $result = [];

        if (!$validator->validate($file, $error)) {
            return ['result' => 'error', 'message' => $error];
        }
        $fileName = md5(Yii::$app->user->id . Yii::$app->security->generateRandomString(64));
        $name = $fileName . "." . $file->extension;
        //$editName = $fileName . '_e.' . $file->extension;

        $path = Yii::getAlias('@webroot/tmp/');
        if (!$file->saveAs($path . $name)) {
            return ['result' => 'error', 'message' => "Ошибка при соранении файла"];
        }

        PhotoHelper::createAdImages($path . $name, Yii::$app->request->post('type'));
        $result = ['name' => $fileName . '.jpg'];

        return ['result' => 'success', 'file' => $result];
    }
}