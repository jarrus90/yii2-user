<?php

namespace jarrus90\User\Forms;

use Yii;
use jarrus90\User\Helpers\Password;

class LoginForm extends \jarrus90\Core\Models\Model {

    /** @var string email */
    public $email;

    /** @var string User's plain password */
    public $password;

    /** @var \jarrus90\User\Module */
    protected $module;

    /** @var string Whether to remember the user */
    public $rememberMe = false;
    private $_user = false;

    /**
     * @param Finder $finder
     * @param array  $config
     */
    public function __construct($config = []) {
        $this->module = Yii::$app->getModule('user');
        parent::__construct($config);
    }

    /**
     * Validation rules
     * @return array
     */
    public function rules() {
        return [
            'requiredFields' => [['email', 'password'], 'required'],
            'emailTrim' => ['email', 'trim'],
            'emailEmail' => ['email', 'email'],
            'rememberMe' => ['rememberMe', 'boolean'],
            'passwordValidate' => [
                'password',
                function ($attribute) {
                    if ($this->user === null || !Password::validate($this->password, $this->user->password_hash)) {
                        $this->addError($attribute, Yii::t('user', 'Invalid login or password'));
                    }
                }
            ],
        ];
    }

    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'email' => \Yii::t('user', 'Email'),
            'password' => \Yii::t('user', 'Password'),
            'rememberMe' => Yii::t('user', 'Remember me next time'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, \Yii::t('user', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*365 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser() {
        if ($this->_user === false) {
            $this->_user = \jarrus90\User\Models\User::findByEmail($this->email);
        }
        return $this->_user;
    }

}
