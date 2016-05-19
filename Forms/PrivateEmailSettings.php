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
class PrivateEmailSettings extends \jarrus90\Core\Models\Model {

    protected $uid;

    /** @var string */
    public $email;

    /** @var string */
    public $current_password;

    /** @var User */
    protected $_user;

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
            'emailUnique' => ['email', 'unique', 'when' => function ($model, $attribute) {
                    return $this->user->email != $model->email;
                }, 'targetClass' => \jarrus90\User\Models\User::className()],
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
            'email' => Yii::t('user', 'E-mail'),
            'current_password' => Yii::t('user', 'Current password'),
        ];
    }

    /**
     * Returns custom form name
     * @return string
     */
    public function formName() {
        return 'changemail-settings-form';
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
            $this->user->email = $this->email;
            return $this->user->save();
        }

        return false;
    }

}
