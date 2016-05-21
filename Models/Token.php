<?php

namespace jarrus90\User\Models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * Token Active Record model.
 *
 * @property integer $user_id
 * @property string  $code
 * @property integer $created_at
 * @property integer $type
 * @property string  $url
 * @property bool    $isExpired
 * @property User    $user
 *
 */
class Token extends ActiveRecord {

    const TYPE_CONFIRMATION = 0;
    const TYPE_RECOVERY = 1;
    const TYPE_CONFIRM_NEW_EMAIL = 2;
    const TYPE_CONFIRM_OLD_EMAIL = 3;

    /** @var \jarrus90\User\Module */
    protected $module;

    /** @inheritdoc */
    public function init() {
        $this->module = Yii::$app->getModule('user');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(\jarrus90\User\Models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return string
     */
    public function getUrl() {
        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
                $route = '/user/confirm';
                break;
            case self::TYPE_RECOVERY:
                $route = '/user/recovery/reset';
                break;
            case self::TYPE_CONFIRM_NEW_EMAIL:
            case self::TYPE_CONFIRM_OLD_EMAIL:
                $route = '/user/settings/confirm';
                break;
            default:
                throw new \RuntimeException();
        }

        return Url::to([$route, 'id' => $this->user_id, 'code' => $this->code], true);
    }

    /**
     * @return bool Whether token has expired.
     */
    public function getIsExpired() {
        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
            case self::TYPE_CONFIRM_NEW_EMAIL:
            case self::TYPE_CONFIRM_OLD_EMAIL:
                $expirationTime = $this->module->confirmWithin;
                break;
            case self::TYPE_RECOVERY:
                $expirationTime = $this->module->recoverWithin;
                break;
            default:
                throw new \RuntimeException();
        }
        return ($this->created_at + $expirationTime) < time();
    }

    /** @inheritdoc */
    public function beforeSave($insert) {
        if ($insert) {
            $this->setAttribute('created_at', time());
            $this->setAttribute('code', Yii::$app->security->generateRandomString());
        }

        return parent::beforeSave($insert);
    }

    /**
     * Table name
     * @return string
     */
    public static function tableName() {
        return '{{%user_token}}';
    }

    /**
     * Get combined primary key
     * @return array
     */
    public static function primaryKey() {
        return ['user_id', 'code', 'type'];
    }

}