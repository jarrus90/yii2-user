<?php

namespace jarrus90\User\Models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\modules\User\Helpers\Password;
use app\modules\User\Helpers\Mailer;

/**
 * User model
 *
 * @property integer $id
 * @property string $login
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 0;
    const ACCOUNT_TYPE_CLIENT = 'user_client';
    const ACCOUNT_TYPE_DOCTOR = 'user_doctor';
    const ACCOUNT_TYPE_CLINIC = 'user_clinic';

    /** @var string Plain password. Used for model validation. */
    public $password;

    /** @var string Plain type. Used for model validation. */
    public $type;

    /**
     * Table name
     * @return string
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'email' => Yii::t('user', 'E-mail'),
            'registration_ip' => Yii::t('user', 'Registration ip'),
            'unconfirmed_email' => Yii::t('user', 'New email'),
            'password' => Yii::t('user', 'Password'),
            'name' => Yii::t('user', 'First Name'),
            'surname' => Yii::t('user', 'Last Name'),
            'created_at' => Yii::t('user', 'Registration time'),
            'confirmed_at' => Yii::t('user', 'Confirmation time'),
            'type' => Yii::t('user', 'Account type'),
            'phone' => Yii::t('user', 'Phone number'),
        ];
    }

    /**
     * Available scenarios list
     * @return array
     */
    public function scenarios() {
        return [
            'connect' => ['email'],
            'settings' => ['name', 'surname', 'phone', 'country_id', 'city_id', 'dob', 'gender', 'salutation', 'timezone'],
            'register' => ['email', 'password', 'phone'],
            'privateSettings' => ['email', 'password'],
            'languageSettings' => ['lang'],
        ];
    }

    /**
     * List of attached behaviors
     * 
     * @return array
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * Validation rules
     * @return array
     */
    public function rules() {
        return [
            'statusDefault' => ['status', 'default', 'value' => self::STATUS_ACTIVE],
            'statusRange' => ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            'emailRequired' => ['email', 'required', 'on' => ['register', 'connect', 'create', 'update']],
            'emailPattern' => ['email', 'email'],
            'emailLength' => ['email', 'string', 'max' => 255],
            'emailUnique' => ['email', 'unique', 'message' => Yii::t('user', 'This email address has already been taken')],
            'emailTrim' => ['email', 'trim'],
            'passwordRequired' => ['password', 'required', 'on' => ['register']],
            'passwordLength' => ['password', 'string', 'min' => 6, 'on' => ['register', 'create']],
            'phoneLength' => ['phone', 'string', 'min' => 6],
            'blockedReason' => ['blocked_reason', 'safe'],
            'userSafe' => [['name', 'surname'], 'safe'],
            'dobFormat' => ['dob', 'date', 'format' => 'php:Y-m-d'],
            'locationInt' => [['country_id', 'city_id'], 'integer'],
            'langExists' => ['lang', 'exist', 'targetClass' => Language::className(), 'targetAttribute' => 'code'],
            'cityExists' => ['city_id', 'exist', 'targetClass' => CityModel::className(), 'targetAttribute' => 'id'],
            'countryExists' => ['country_id', 'exist', 'targetClass' => CountryModel::className(), 'targetAttribute' => 'id'],
            'genderRange' => ['gender', 'in', 'range' => [User::GENDER_MALE, User::GENDER_FEMALE]],
            'salutationLength' => ['salutation', 'string', 'max' => 10],
            'salutationTrim' => ['salutation', 'trim'],
            'descriptionTrim' => ['description', 'trim']
        ];
    }

    /**
     * Find user by id
     * @return static
     */
    public static function findIdentity($id) {
        //$db = Yii::$app->db;
        //return $db->cache(function ($db) use ($id) {
        return static::findOne(['id' => $id]);
        //}, 45);
    }

    /**
     * Find user by email
     * @return static
     */
    public static function findByEmail($email) {
        return static::findOne(['email' => $email]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Get id based on primary key
     * @return string|array
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Set password hash based on password
     *
     * @param string $password
     * @return string
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        return $this->password_hash;
    }

    /**
     * Reset password.
     *
     * @param string $password
     * @return bool
     */
    public function resetPassword($password) {
        return (bool) $this->updateAttributes(['password_hash' => Password::hash($password)]);
    }

    /**
     * Generate authentication key
     *
     * @return string
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
        return $this->auth_key;
    }

    /**
     * Confirms the user by setting 'confirmed_at' field to current time.
     * @return bool
     */
    public function confirm() {
        return (bool) $this->updateAttributes(['confirmed_at' => time()]);
    }

    /**
     * Blocks the user by setting 'blocked_at' field to current time and regenerates auth_key.
     * @return bool
     */
    public function block() {

        $auth = Yii::$app->authManager;
        $role = $auth->getRole('user_blocked');
        $auth->assign($role, $this->id);

        return (bool) $this->updateAttributes([
                    'blocked_at' => time(),
                    'blocked_by' => Yii::$app->user->id,
                    'auth_key' => Yii::$app->security->generateRandomString(),
        ]);
    }

    /**
     * UnBlocks the user by setting 'blocked_at' field to null.
     * @return bool
     */
    public function unblock() {

        $auth = Yii::$app->authManager;
        $role = $auth->getRole('user_blocked');
        $auth->revoke($role, $this->id);

        return (bool) $this->updateAttributes([
                    'blocked_at' => null,
                    'blocked_by' => Yii::$app->user->id,
                    'blocked_reason' => null,
        ]);
    }

    public function getAccounts() {
        $connected = [];
        $accounts = $this->hasMany(\app\modules\User\Models\Account::className(), ['user_id' => 'id'])->all();

        /** @var Account $account */
        foreach ($accounts as $account) {
            $connected[$account->provider] = $account;
        }

        return $connected;
    }

    /**
     * @return bool Whether the user is confirmed or not.
     */
    public function getIsConfirmed() {
        return $this->confirmed_at != null;
    }

    /**
     * @return bool Whether the user is blocked or not.
     */
    public function getIsBlocked() {
        return $this->blocked_at != null;
    }

    public function getBlockedBy() {
        return $this->hasOne(self::className(), ['id' => 'blocked_by']);
    }

    /**
     * This method is used to register new user account. If Module::enableConfirmation is set true, this method
     * will generate new confirmation token and use mailer to send it to the user.
     *
     * @return bool
     */
    public function register() {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }
        $this->confirmed_at = Yii::$app->getModule('user')->enableConfirmation ? null : time();
        $this->lang = Yii::$app->language;
        $this->name = htmlspecialchars(strip_tags(trim($this->name)));
        $this->surname = htmlspecialchars(strip_tags(trim($this->surname)));
        if (!$this->save()) {
            return false;
        }

        if (Yii::$app->getModule('user')->enableConfirmation) {
            /** @var Token $token */
            $token = Yii::createObject(['class' => Token::className(), 'type' => Token::TYPE_CONFIRMATION]);
            $token->link('user', $this);
        }
        $mailer = new Mailer();
        $mailer->sendWelcomeMessage($this, isset($token) ? $token : null);

        Yii::$app->trigger(EventUser::EVENT_NEW_USER, new EventUser(['user' => $this]));

        return true;
    }

    /** @inheritdoc */
    public function beforeSave($insert) {
        if ($insert) {
            $this->setAttribute('auth_key', Yii::$app->security->generateRandomString());
            if (Yii::$app instanceof WebApplication) {
                $this->setAttribute('registration_ip', Yii::$app->request->userIP);
            }
        }

        if (!empty($this->password)) {
            $this->setAttribute('password_hash', Password::hash($this->password));
        }

        return parent::beforeSave($insert);
    }

    /**
     * Attempts user confirmation.
     *
     * @param string $code Confirmation code.
     *
     * @return boolean
     */
    public function attemptConfirmation($code) {
        $token = Token::findOne(['user_id' => $this->id, 'code' => $code, 'type' => Token::TYPE_CONFIRMATION]);
        if ($token instanceof Token && !$token->isExpired) {
            $token->delete();
            if (($success = $this->confirm())) {
                Yii::$app->user->login($this, Yii::$app->getModule('user')->rememberFor);
                $message = Yii::t('user', 'Thank you, registration is now complete.');
            } else {
                $message = Yii::t('user', 'Something went wrong and your account has not been confirmed.');
            }
        } else {
            $success = false;
            $message = Yii::t('user', 'The confirmation link is invalid or expired. Please try requesting a new one.');
        }
        Yii::$app->session->setFlash($success ? 'success' : 'error', $message);
        return $success;
    }

}
