<?php

namespace jarrus90\User\Forms;

use jarrus90\User\Helpers\Password;
use jarrus90\User\Models\User;
use Yii;

/**
 * SettingsForm gets user's username, email and password and changes them.
 *
 * @property User $user
 *
 */
class PrivatePasswordSettings extends \jarrus90\Core\Models\Model {

    protected $uid;

    /** @var string */
    public $new_password;

    /** @var string */
    public $current_password;

    /** @var User */
    protected $_user;
    public $password_repeat;

    /** @return User */
    public function getUser() {
        if ($this->_user == null) {
            $this->_user = User::findOne($this->uid);
            if ($this->_user === null) {
                throw new \yii\web\NotFoundHttpException('The requested page does not exist');
            }
        }
        return $this->_user;
    }

    /** @inheritdoc */
    public function __construct($id = null) {
        if ($id) {
            $this->uid = $id;
        } else {
            $this->uid = Yii::$app->user->id;
        }
        $this->setAttributes(['email' => $this->user->unconfirmed_email ? : $this->user->email], false);
        parent::__construct();
    }

    /**
     * Validation rules
     * @return array
     */
    public function rules() {
        $rules = [
            // password rules
            'passwordRequired' => ['password', 'required', 'on' => ['register']],
            'passwordLength' => ['password', 'string', 'min' => 6, 'on' => ['register', 'create']],
            'newPasswordLength' => ['new_password', 'string', 'min' => 6],
            'currentPasswordRequired' => ['current_password', 'required'],
            'currentPasswordValidate' => ['current_password', function ($attr) {
                    if (!Password::validate($this->$attr, $this->user->password_hash)) {
                        $this->addError($attr, Yii::t('user', 'Old password is not valid'));
                    }
                }],
            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute' => 'new_password', 'message' => "Passwords don't match"]
        ];
        return $rules;
    }

    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'new_password' => Yii::t('user', 'New password'),
            'current_password' => Yii::t('user', 'Old password'),
            'password_repeat' => Yii::t('user', 'Confirm<br>new password'),
        ];
    }

    /**
     * Returns custom form name
     * @return string
     */
    public function formName() {
        return 'changepass-settings-form';
    }

    /**
     * Saves new account settings.
     *
     * @return bool
     */
    public function save() {
        $this->validate();
        if ($this->validate()) {
            $this->user->scenario = 'settings';
            $this->user->password = $this->new_password;
            return $this->user->save();
        }

        return false;
    }

}
