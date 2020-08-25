<?php

namespace app\components;

use app\modules\user\models\User;
use yii\rbac\Assignment;
use yii\rbac\PhpManager;
use Yii;

class AuthManager extends PhpManager
{
    public function getAssignments($userId)
    {
        if ($userId && $user = $this->getUser($userId)) {
            $assignment = new Assignment();
            $assignment->userId = $userId;
            $assignment->roleName = $user->role;
            return [$assignment->roleName => $assignment];
        }
        return [];
    }

    public function getAssignment($roleName, $userId)
    {
        if ($userId && $user = $this->getUser($userId)) {
            if ($user->role == $roleName) {
                $assignment = new Assignment();
                $assignment->userId = $userId;
                $assignment->roleName = $user->role;
                return $assignment;
            }
        }
        return null;
    }

    public function getUserIdsByRole($roleName)
    {
        $roleID = array_search($roleName, User::getRolesArray());
        return User::find()->where(['status' => $roleID])->select('id')->column();
    }

    public function assign($role, $userId)
    {
        if ($userId && $user = $this->getUser($userId)) {
            $assignment = new Assignment([
                'userId' => $userId,
                'roleName' => $role->name,
                'createdAt' => time(),
            ]);
            $this->setRole($user, $role->name);
            return $assignment;
        }
        return null;
    }

    public function revoke($role, $userId)
    {
        if ($userId && $user = $this->getUser($userId)) {
            if ($user->status == array_search($role->name, User::getRolesArray())) {
                $this->setRole($user, null);
                return true;
            }
        }
        return false;
    }

    public function revokeAll($userId)
    {
        if ($userId && $user = $this->getUser($userId)) {
            $this->setRole($user, null);
            return true;
        }
        return false;
    }

    /**
     * @param integer $userId
     * @return null|\yii\web\IdentityInterface|User
     */
    private function getUser($userId)
    {
        $webUser = Yii::$app->get('user', false);
        if ($webUser && !$webUser->getIsGuest() && $webUser->getId() == $userId) {
            return $webUser->getIdentity();
        } else {
            return User::findOne($userId);
        }
    }

    /**
     * @param User $user
     * @param string $roleName
     */
    private function setRole(User $user, $roleName)
    {
        $user->status = array_search($roleName, User::getRolesArray());
        $user->updateAttributes(['status' => array_search($roleName, User::getRolesArray())]);
    }
}