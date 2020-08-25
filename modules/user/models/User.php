<?php

namespace app\modules\user\models;

use app\modules\main\models\AdCar;
use app\modules\main\models\AdPart;
use app\modules\main\models\AdTire;
use app\modules\main\models\AdWheel;
use app\modules\main\models\City;
use app\modules\main\models\Complaint;
use app\modules\user\models\query\UserQuery;
use app\rbac\Rbac;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property int $city_id
 * @property string $email
 * @property string $phone
 * @property string $phone_operator
 * @property string $call_time
 * @property string $avatar
 * @property integer $status
 *
 * @property string $auth_key
 * @property string $email_confirm_token
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property string $role
 * @property array $phoneOperatorsArray
 * @property string $storagePath
 * @property string $storageUrl
 * @property string $avatarUrl
 * @property array $callTime
 * @property array $callTimeFrom
 * @property array $callTimeTo
 * @property int $adCount
 *
 * @property AdCar[] $adCars
 * @property AdPart[] $adParts
 * @property AdTire[] $adTires
 * @property AdWheel[] $adWheels
 * @property Complaint[] $complaints
 * @property City $city
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_WAITING = 0;
    const STATUS_BANNED = 1;
    const STATUS_USER = 10;
    const STATUS_MODERATOR = 15;
    const STATUS_ADMIN = 100;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['username'], 'required'],
            [['username'], 'string', 'max' => 25],
            //[['username'], 'unique'],

            [['email'], 'string', 'max' => 255],
            //[['email'], 'unique'],

            ['phone', 'string', 'min' => 5, 'max' => 32],
            ['phone_operator', 'in', 'range' => array_keys(User::getPhoneOperatorsArray())],

            ['status', 'integer'],
            //['status', 'in', ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя',
            'city' => 'Город',
            'auth_key' => 'Auth Key',
            'email_confirm_token' => 'Email Confirm Token',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'E-mail',
            'status' => 'Статус',
            'statusLabel' => 'Статус',
            'created_at' => 'Дата регистрации',
            'updated_at' => 'Последнее изменение',
            'callTimeFrom' => 'Звонить с',
            'callTimeTo' => 'Звонить до',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
                $this->setCallTime(8, 22);
            }

            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            FileHelper::createDirectory($this->storagePath);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        if ($this->avatar && is_file($this->storagePath . "/" . $this->avatar)) {
            unlink($this->storagePath . "/" . $this->avatar);
        }

        parent::afterDelete();
    }

    /**
     * @return string
     */
    public function getStoragePath()
    {
        return Yii::getAlias("@webroot/images/users/" . $this->id);
    }

    /**
     * @return string
     */
    public function getStorageUrl()
    {
        return Yii::getAlias("@web/images/users/" . $this->id);
    }

    /**
     * @return array
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_WAITING => 'Ожидает активации',
            self::STATUS_BANNED => 'Забанен',
            self::STATUS_USER => 'Пользователь',
            self::STATUS_MODERATOR => 'Модератор',
            self::STATUS_ADMIN => 'Админ',
        ];
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }

    public function getStatusLabel()
    {
        switch ($this->status) {
            case self::STATUS_WAITING:
                $class = 'warning';
                break;
            case self::STATUS_BANNED:
                $class = 'danger';
                break;
            case self::STATUS_USER:
                $class = 'success';
                break;
            case self::STATUS_MODERATOR:
                $class = 'primary';
                break;
            case self::STATUS_ADMIN:
                $class = 'purple';
                break;
            default: $class = 'default';
        }
        return Html::tag("span", $this->statusName, ['class' => 'label label-' . $class]);
    }

    /**
     * @return array
     */
    public static function getRolesArray()
    {
        return [
            self::STATUS_WAITING => Rbac::ROLE_WAITING,
            self::STATUS_BANNED => Rbac::ROLE_BANNED,
            self::STATUS_USER => Rbac::ROLE_USER,
            self::STATUS_MODERATOR => Rbac::ROLE_MODERATOR,
            self::STATUS_ADMIN => Rbac::ROLE_ADMIN,
        ];
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return ArrayHelper::getValue(self::getRolesArray(), $this->status);
    }

    /**
     * @return bool
     */
    public function hasModeratorPermissions()
    {
        return $this->status == self::STATUS_MODERATOR || $this->status == self::STATUS_ADMIN;
    }

    /**
     * @return array
     */
    public static function getPhoneOperatorsArray()
    {
        return [
            'Мтс' => 'Мтс', 'Velcom' => 'Velcom', 'Life' => 'Life'
        ];
    }

    /**
     * @return array
     */
    public static function getCallTimeArray()
    {
        return [
            8 => '8:00',
            9 => '9:00',
            10 => '10:00',
            11 => '11:00',
            12 => '12:00',
            13 => '13:00',
            14 => '14:00',
            15 => '15:00',
            16 => '16:00',
            17 => '17:00',
            18 => '18:00',
            19 => '19:00',
            20 => '20:00',
            21 => '21:00',
            22 => '22:00',
            23 => '23:00',
            24 => '24:00',
        ];
    }

    /**
     * @param integer $from
     * @param integer $to
     */
    public function setCallTime($from, $to)
    {
        $this->call_time = serialize(['from' => $from, 'to' => $to]);
    }

    /**
     * @return array
     */
    public function getCallTime()
    {
        $callTime = unserialize($this->call_time);
        return is_array($callTime) ? $callTime : ['from' => 8, 'to' => 24];
    }

    /**
     * @return string
     */
    public function getCallTimeFrom()
    {
        return ArrayHelper::getValue(self::getCallTimeArray(), $this->getCallTime()['from']);
    }

    /**
     * @return string
     */
    public function getCallTimeTo()
    {
        return ArrayHelper::getValue(self::getCallTimeArray(), $this->getCallTime()['to']);
    }

    /**
     * @param string $ext
     * @return string
     */
    public static function genAvatarName($ext)
    {
        return 'av' . time() . "." . $ext;
    }

    /**
     * @return string
     */
    public function getAvatarUrl()
    {
        return $this->avatar ? $this->storageUrl . '/' . $this->avatar . '?v=' . filemtime($this->storagePath . '/' . $this->avatar) : '/images/no-ava.png';
    }

    public function getAdCount()
    {
        return $this->getAdCars()->count() + $this->getAdParts()->count() + $this->getAdTires()->count() + $this->getAdWheels()->count();
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return User|array|ActiveRecord
     */
    public static function findByEmail($email)
    {
        return static::find()->where(['email' => $email])->active()->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User|array|ActiveRecord
     */
    public static function findByUsername($username)
    {
        return static::find()->where(['username' => $username])->active()->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdCars()
    {
        return $this->hasMany(AdCar::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdParts()
    {
        return $this->hasMany(AdPart::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdTires()
    {
        return $this->hasMany(AdTire::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdWheels()
    {
        return $this->hasMany(AdWheel::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComplaints()
    {
        return $this->hasMany(Complaint::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery|\app\board\dummy\City
     */
    public function getCity()
    {
        if (!$this->city_id) {
            return new \app\board\dummy\City();
        }
        return $this->hasOne(City::className(), ['id' => 'city_id'])->inverseOf('users');
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public static function findIdentity($id)
    {
        return static::find()
            ->where(['id' => $id])
            ->active()
            ->one();
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return array|ActiveRecord|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::find()->where(['password_reset_token' => $token])->active()->one();
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @param string $email_confirm_token
     * @return static|null
     */
    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAITING]);
    }

    /**
     * Generates email confirmation token
     */
    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Removes email confirmation token
     */
    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }
}
