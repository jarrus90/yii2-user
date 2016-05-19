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
class PrivatePhoneSettings extends \jarrus90\Core\Models\Model {

    protected $uid;

    /** @var string */
    public $phone;

    /** @var string */
    public $current_password;

    /** @var User */
    protected $_user;

    /** @inheritdoc */
    public function __construct($id = null) {
        if ($id) {
            $this->uid = $id;
        } else {
            $this->uid = Yii::$app->user->id;
        }
        $this->setAttributes(['phone' => $this->user->phone], false);
        parent::__construct();
    }

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

    /**
     * Validation rules
     * @return array
     */
    public function rules() {
        $rules = [
            'phoneLength' => ['phone', 'string', 'min' => 6],
            'currentPasswordRequired' => ['current_password', 'required'],
            'currentPasswordValidate' => ['current_password', function ($attr) {
                    if (!Password::validate($this->$attr, $this->user->password_hash)) {
                        $this->addError($attr, Yii::t('user', 'Current password is not valid'));
                    }
                }],
        ];
        return $rules;
    }

    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'phone' => Yii::t('user', 'Phone'),
            'current_password' => Yii::t('user', 'Current password'),
        ];
    }

    /**
     * Returns custom form name
     * @return string
     */
    public function formName() {
        return 'changephone-settings-form';
    }

    /**
     * Saves new account settings.
     *
     * @return bool
     */
    public function save() {
        if ($this->validate()) {
            $this->user->scenario = 'settings';
            $this->user->phone = $this->phone;
            return $this->user->save();
        }
        return false;
    }

}
