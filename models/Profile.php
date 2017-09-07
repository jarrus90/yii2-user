<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace jarrus90\User\models;

use yii\db\ActiveRecord;
use yii\helpers\Url;
use jarrus90\User\traits\ModuleTrait;

/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property string  $name
 * @property string  $public_email
 * @property string  $bio
 * @property string  $timezone
 * @property User    $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class Profile extends ActiveRecord {

    use ModuleTrait;

    /** @var \jarrus90\User\Module */
    protected $module;

    /** @inheritdoc */
    public function init() {
        $this->module = \Yii::$app->getModule('user');
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            'bioString' => ['bio', 'string'],
            'timeZoneValidation' => ['timezone', 'validateTimeZone'],
            'publicEmailPattern' => ['public_email', 'email'],
            'nameLength' => ['name', 'string', 'max' => 255],
            'publicEmailLength' => ['public_email', 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => \Yii::t('user', 'Name'),
            'surname' => \Yii::t('user', 'Surname'),
            'public_email' => \Yii::t('user', 'Email (public)'),
            'bio' => \Yii::t('user', 'Bio'),
            'timezone' => \Yii::t('user', 'Time zone'),
        ];
    }

    /**
     * Validates the timezone attribute.
     * Adds an error when the specified time zone doesn't exist.
     * @param string $attribute the attribute being validated
     * @param array $params values for the placeholders in the error message
     */
    public function validateTimeZone($attribute, $params) {
        if (!in_array($this->$attribute, timezone_identifiers_list())) {
            $this->addError($attribute, \Yii::t('user', 'Time zone is not valid'));
        }
    }

    /**
     * Get the user's time zone.
     * Defaults to the application timezone if not specified by the user.
     * @return \DateTimeZone
     */
    public function getTimeZone() {
        try {
            return new \DateTimeZone($this->timezone);
        } catch (\Exception $e) {
            // Default to application time zone if the user hasn't set their time zone
            return new \DateTimeZone(\Yii::$app->timeZone);
        }
    }

    /**
     * Set the user's time zone.
     * @param \DateTimeZone $timezone the timezone to save to the user's profile
     */
    public function setTimeZone(\DateTimeZone $timeZone) {
        $this->setAttribute('timezone', $timeZone->getName());
    }

    /**
     * Converts DateTime to user's local time
     * @param \DateTime the datetime to convert
     * @return \DateTime
     */
    public function toLocalTime(\DateTime $dateTime = null) {
        if ($dateTime === null) {
            $dateTime = new \DateTime();
        }

        return $dateTime->setTimezone($this->getTimeZone());
    }

    public function getAvatarUrl($size = false, $forceGravatar = false, $default = false, $url = false) {
        if(!$forceGravatar && !empty($this->avatar)) {
            return ($url ? : $this->module->avatarUrlDefault) . $this->avatar;
        } else if($forceGravatar || $this->module->avatarGravatarEnable) {
            return "//www.gravatar.com/avatar/{$this->gravatar_id}?" . http_build_query([
                's' => ($size ? : $this->module->avatarGravatarDefaultSize),
                'd' => ($default ? : $this->module->avatarGravatarDefault),
                'r' => $this->module->avatarGravatarRating,
            ]);
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert) {
        if ($this->isAttributeChanged('public_email')) {
            $this->setAttribute('gravatar_id', md5(strtolower(trim($this->getAttribute('public_email')))));
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user_profile}}';
    }

}
