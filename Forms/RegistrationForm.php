<?php

namespace jarrus90\User\Forms;

use Yii;
use jarrus90\User\Models\User;

class RegistrationForm extends \jarrus90\Core\Models\Model {

    public $email;
    public $password;
    public $name;
    public $surname;

    /**
     * Validation rules
     * @return array
     */
    public function rules() {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => \Yii::t('user', 'This email address has already been taken.')],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['name', 'filter', 'filter' => 'trim'],
            ['surname', 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'email' => \Yii::t('user', 'E-mail'),
            'password' => \Yii::t('user', 'Password'),
            'name' => \Yii::t('user', 'First Name'),
            'surname' => \Yii::t('user', 'Last Name'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup() {
        if ($this->validate()) {
            $user = new User();
            $user->scenario = 'register';
            $user->email = $this->email;
            $user->name = $this->name;
            $user->surname = $this->surname;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }
        return false;
    }

}
