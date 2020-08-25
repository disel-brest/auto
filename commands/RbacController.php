<?php

namespace app\commands;

use app\rbac\IsAuthorRule;
use Yii;
use yii\console\Controller;
use app\rbac\Rbac;

/**
 * RBAC generator
 */
class RbacController extends Controller
{
    /**
     * Generates roles
     */
    public function actionInit()
    {
        $this->stdout('RBAC Init' . PHP_EOL);
        $auth = Yii::$app->getAuthManager();
        $auth->removeAll();

        /* Создаём правила */
        $isAuthorRule = new IsAuthorRule();
        $auth->add($isAuthorRule);

        /* Создаём permissions */
        $adManage = $auth->createPermission(Rbac::PERMISSION_AD_MANAGE);
        $adManage->description = 'CRUD permissions for ads';
        $auth->add($adManage);

        $ownAdManage = $auth->createPermission(Rbac::PERMISSION_OWN_AD_MANAGE);
        $ownAdManage->description = 'CRUD permissions for own ads';
        $ownAdManage->ruleName = $isAuthorRule->name;
        $auth->add($ownAdManage);

        $userPermission = $auth->createPermission(Rbac::PERMISSION_USER);
        $userPermission->description = 'User permissions';
        $auth->add($userPermission);

        $moderatePermission = $auth->createPermission(Rbac::PERMISSION_MODERATE);
        $moderatePermission->description = 'Moderator permissions';
        $auth->add($moderatePermission);

        $adminPermission = $auth->createPermission(Rbac::PERMISSION_ADMIN);
        $adminPermission->description = 'Admin permissions';
        $auth->add($adminPermission);

        /* Создаём Роли */
        $waiting = $auth->createRole(Rbac::ROLE_WAITING);
        $waiting->description = 'Waiting for register confirmation';
        $auth->add($waiting);

        $banned = $auth->createRole(Rbac::ROLE_BANNED);
        $banned->description = 'Banned User';
        $auth->add($banned);

        $user = $auth->createRole(Rbac::ROLE_USER);
        $user->description = 'User';
        $auth->add($user);

        $moderator = $auth->createRole(Rbac::ROLE_MODERATOR);
        $moderator->description = 'Moderator';
        $auth->add($moderator);

        $admin = $auth->createRole(Rbac::ROLE_ADMIN);
        $admin->description = 'Admin';
        $auth->add($admin);

        /* Создаём зависимости */
        $auth->addChild($admin, $adminPermission);
        $auth->addChild($admin, $moderator);

        $auth->addChild($moderator, $moderatePermission);
        $auth->addChild($moderator, $user);

        $auth->addChild($moderatePermission, $adManage);

        $auth->addChild($user, $userPermission);

        $auth->addChild($userPermission, $ownAdManage);

        $auth->addChild($ownAdManage, $adManage);

        $this->stdout('Done!' . PHP_EOL);
    }
}
