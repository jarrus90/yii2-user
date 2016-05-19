<?php

namespace jarrus90\User\Forms;

use Yii;
use jarrus90\User\Models\User;
use jarrus90\User\Models\Token;
use jarrus90\User\Helpers\Mailer;

/**
 * Model for collecting data on password recovery.
 *
 * @property \jarrus90\User\Module $module
 *
 */
class RecoveryForm extends \jarrus90\Core\Models\Model {

    /** @var string */
    public $email;

    /** @var string */
    public $password;

    /** @var User */
    protected $user;

    /** @var \jarrus90\User\Module */
    protected $module;

    /**
     * @param array $config
     */
    public function __construct($config = []) {
        $this->module = Yii::$app->getModule('user');
        parent::__construct($config);
    }

    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels() {
        return [
            'email' => Yii::t('user', 'E-mail'),
            'password' => Yii::t('user', 'Password'),
        ];
    }

    /**
     * Available scenarios list
     * @return array
     */
    public function scenarios() {
        return [
            'request' => ['email'],
            'reset' => ['password'],
        ];
    }

    /**
     * Validation rules
     * @return array
     */
    public function rules() {
        return [
            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
            'emailUnconfirmed' => [
                'email',
                function ($attribute) {
                    $this->user = User::findByEmail($this->email);
                }
            ],
            'passwordRequired' => ['password', 'required'],
            'passwordLength' => ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Sends recovery message.
     *
     * @return bool
     */
    public function sendRecoveryMessage() {
        if ($this->validate()) {
            /** @var Token $token */
            if ($this->user) {
                $token = Yii::createObject([
                    'class' => Token::className(),
                    'user_id' => $this->user->id,
                    'type' => Token::TYPE_RECOVERY,
                ]);
                $token->save(false);
                $mailer = new Mailer();
                $mailer->sendRecoveryMessage($this->user, $token);
            }
            return true;
        }

        return false;
    }

    /**
     * Resets user's password.
     *
     * @param Token $token
     *
     * @return bool
     */
    public function resetPassword(Token $token) {
        if (!$this->validate() || $token->user === null) {
            return false;
        }

        if ($token->user->resetPassword($this->password)) {
            Yii::$app->session->setFlash('success', Yii::t('user', 'Your password has been changed successfully.'));
            $token->delete();
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'An error occurred and your password has not been changed. Please try again later.'));
        }

        return true;
    }

    /**
     * Returns custom form name
     * @return string
     */
    public function formName() {
        return 'recovery-form';
    }

}
