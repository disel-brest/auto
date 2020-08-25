<?php

namespace app\modules\admin;

use app\rbac\Rbac;
use Yii;
use yii\filters\AccessControl;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$app->user->loginUrl = ['/admin/default/login'];
        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapAsset'] = [];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => [Rbac::PERMISSION_MODERATE],
                    ],
                ],
            ],
        ];
    }
}
