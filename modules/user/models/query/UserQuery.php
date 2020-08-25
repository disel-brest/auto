<?php

namespace app\modules\user\models\query;

use yii\db\ActiveQuery;
use app\modules\user\models\User;

/**
 * This is the ActiveQuery class for [[\app\modules\user\models\User]].
 *
 * @see \app\modules\user\models\User
 */
class UserQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere(['>', 'status', User::STATUS_BANNED]);
    }

    /**
     * @inheritdoc
     * @return \app\modules\user\models\User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\user\models\User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
