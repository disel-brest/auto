<?php

namespace app\modules\user\controllers;

use app\modules\main\models\City;
use app\modules\main\models\filters\CarsFilter;
use app\modules\main\models\filters\TiresFilter;
use app\modules\main\models\filters\WheelsFilter;
use app\modules\user\models\User;
use app\rbac\Rbac;
use udokmeci\yii2PhoneValidator\PhoneValidator;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\validators\FileValidator;
use yii\validators\StringValidator;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Default controller for the `user` module
 */
class CabinetController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout', 'signup', 'login', 'password-reset-request'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Rbac::PERMISSION_USER],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'set-new-password' => ['post'],
                    'set-new-username' => ['post'],
                    'set-new-city' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $curType = Yii::$app->request->get('type');
        $user = Yii::$app->user->identity;
        //$carsProvider = (new CarsFilter())->search([], true);
        $tiresProvider = (new TiresFilter())->search([], true);
        $wheelsProvider = (new WheelsFilter())->search([], true);

        return $this->render('index', [
            'user' => $user,
            //'carsProvider' => $carsProvider,
            'tiresProvider' => $tiresProvider,
            'wheelsProvider' => $wheelsProvider,
            'curType' => $curType,
        ]);
    }

    /**
     * @return array
     * @throws ForbiddenHttpException
     */
    public function actionSetNewPassword()
   {
       if (!Yii::$app->request->isAjax) {
           throw new ForbiddenHttpException("Доступ запрещён");
       }

       Yii::$app->response->format = Response::FORMAT_JSON;

       $oldPassword = Yii::$app->request->post('oldPassword');
       $newPassword = Yii::$app->request->post('newPassword');
       $newPasswordVerify = Yii::$app->request->post('newPasswordVerify');

       /* @var $user User */
       $user = Yii::$app->user->identity;
       if ($user->validatePassword($oldPassword) && $newPassword && $newPassword === $newPasswordVerify) {
           $user->setPassword($newPassword);
           if ($user->save()) {
               return ['result' => 'success'];
           }
       }

       return ['result' => false];
   }

    /**
     * @return array
     * @throws ForbiddenHttpException
     */
    public function actionSetNewUsername()
   {
       if (!Yii::$app->request->isAjax) {
           throw new ForbiddenHttpException("Доступ запрещён");
       }

       Yii::$app->response->format = Response::FORMAT_JSON;
       $username = Yii::$app->request->post('username');

       $validator = new StringValidator();
       if ($validator->validate($username)) {
           if (Yii::$app->user->identity->updateAttributes(['username' => $username])) {
               return ['result' => 'success'];
           }
       }

       return ['result' => false];
   }

    /**
     * @return array
     * @throws ForbiddenHttpException
     */
    public function actionSetNewCity()
   {
       if (!Yii::$app->request->isAjax) {
           throw new ForbiddenHttpException("Доступ запрещён");
       }

       Yii::$app->response->format = Response::FORMAT_JSON;
       $cityId = Yii::$app->request->post('city');

       if (($cityModel = City::findOne(['id' => $cityId])) === null) {
           return ['result' => 'false', 'message' => 'Такого города нет в базе'];
       }

       if (Yii::$app->user->identity->updateAttributes(['city_id' => $cityModel->id])) {
           return ['result' => 'success'];
       }

       return ['result' => false, 'message' => 'Ошибка сохранения'];
   }

    /**
     * @return array
     * @throws ForbiddenHttpException
     */
    public function actionSetNewPhone()
   {
       if (!Yii::$app->request->isAjax) {
           throw new ForbiddenHttpException("Доступ запрещён");
       }

       Yii::$app->response->format = Response::FORMAT_JSON;
       $phone = Yii::$app->request->post('phone');
       $phoneOperator = Yii::$app->request->post('phone_operator');

       $phone = \app\board\validators\PhoneValidator::validate($phone);
       if (!$phone) {
           return ['result' => 'false', 'message' => "Неверный телефон"];
       }

       if (!in_array($phoneOperator, User::getPhoneOperatorsArray())) {
           return ['result' => 'false', 'message' => 'Выберите оператора из списка'];
       }

       if (empty($phone)) {
           return ['result' => 'false', 'message' => 'Укажите телефон!'];
       }

       if (Yii::$app->user->identity->updateAttributes(['phone' => $phone, 'phone_operator' => $phoneOperator])) {
           return ['result' => 'success', 'phone' => $phone];
       }

       return ['result' => false, 'message' => 'Ошибка сохранения'];
   }

    /**
     * @return array
     * @throws ForbiddenHttpException
     */
    public function actionSetNewCalltime()
   {
       if (!Yii::$app->request->isAjax) {
           throw new ForbiddenHttpException("Доступ запрещён");
       }

       Yii::$app->response->format = Response::FORMAT_JSON;
       $from = Yii::$app->request->post('from');
       $to = Yii::$app->request->post('to');

       if (!key_exists($from, User::getCallTimeArray()) || !key_exists($to, User::getCallTimeArray())) {
           return ['result' => 'false', 'message' => 'Выберите значение из списка'];
       }

       /* @var $user User */
       $user = Yii::$app->user->identity;
       $user->setCallTime($from, $to);
       if ($user->save(false)) {
           return ['result' => 'success'];
       }

       return ['result' => false, 'message' => 'Ошибка сохранения'];
   }

   public function actionSetNewAvatar()
   {
       if (!Yii::$app->request->isAjax) {
           throw new ForbiddenHttpException("Доступ запрещён");
       }

       Yii::$app->response->format = Response::FORMAT_JSON;

       $validator = new FileValidator([
           'maxSize' => 1024 * 500 * 1,
           'tooBig' => 'Максимальный размер аватарки - 500 килобайт.'
       ]);

       $file = UploadedFile::getInstanceByName('avatar');
       $extension = strtolower(pathinfo($file->type, PATHINFO_FILENAME));
       if ($validator->validate($file, $error) && in_array($extension, ['png', 'jpg', 'jpeg', 'gif'])) {
           $avatarName = User::genAvatarName($extension);
           $savedPath = Yii::$app->user->identity->storagePath . '/' . $avatarName;
           //echo $avatarName;exit;
           if ($file->saveAs($savedPath)) {
               // Удаляем старые аватары
               if (Yii::$app->user->identity->avatar) {
                   if (is_file(Yii::$app->user->identity->storagePath . '/' . Yii::$app->user->identity->avatar)) {
                       unlink(Yii::$app->user->identity->storagePath . '/' . Yii::$app->user->identity->avatar);
                   }
               }

               if (Yii::$app->user->identity->updateAttributes(['avatar' => $avatarName])) {
                   return [
                       'result' => 'success',
                       'image' => '/images/users/' . Yii::$app->user->identity->id . '/' . $avatarName,
                   ];
               } else {
                   unlink($savedPath);
               }
           }
       } else {
           return ['result' => 'false', 'message' => 'Максимальный размер аватарки - 500 килобайт, типы разрешённых файлов - png, jpg и gif'];
       }

       return ['result' => false, 'message' => 'Ошибка сохранения'];
   }
}
