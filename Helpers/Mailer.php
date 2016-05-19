<?php

namespace jarrus90\User\Helpers;

use jarrus90\User\Models\Token;
use jarrus90\User\Models\User;
use Yii;
use yii\base\Component;

/**
 * Mailer.
 *
 */
class Mailer extends Component {

    public $viewPath = '@app/modules/User/views/mail';

    /** @var string|array Default: `Yii::$app->params['adminEmail']` OR `no-reply@example.com` */
    public $sender;

    /** @var string */
    public $welcomeSubject;

    /** @var string */
    public $confirmationSubject;

    /** @var string */
    public $reconfirmationSubject;

    /** @var string */
    public $recoverySubject;

    /** @var \jarrus90\User\Module */
    protected $module;

    /** @inheritdoc */
    public function init() {
        $this->module = Yii::$app->getModule('user');
        parent::init();
    }

    /**
     * Sends an email to a user after registration.
     *
     * @param User  $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendWelcomeMessage(User $user, Token $token = null) {
        return $this->sendMessage($user->email, $this->welcomeSubject, 'welcome', ['user' => $user, 'token' => $token, 'module' => $this->module]
        );
    }

    /**
     * Sends an email to a user with confirmation link.
     *
     * @param User  $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendConfirmationMessage(User $user, Token $token) {
        return $this->sendMessage($user->email, $this->confirmationSubject, 'confirmation', ['user' => $user, 'token' => $token]
        );
    }

    /**
     * Sends an email to a user with reconfirmation link.
     *
     * @param User  $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendReconfirmationMessage(User $user, Token $token) {
        if ($token->type == Token::TYPE_CONFIRM_NEW_EMAIL) {
            $email = $user->unconfirmed_email;
        } else {
            $email = $user->email;
        }

        return $this->sendMessage($email, $this->reconfirmationSubject, 'reconfirmation', ['user' => $user, 'token' => $token]
        );
    }

    /**
     * Sends an email to a user with recovery link.
     *
     * @param User  $user
     * @param Token $token
     *
     * @return bool
     */
    public function sendRecoveryMessage(User $user, Token $token) {
        return $this->sendMessage($user->email, $this->recoverySubject, 'recovery', ['user' => $user, 'token' => $token]
        );
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array  $params
     *
     * @return bool
     */
    protected function sendMessage($to, $subject, $view, $params = []) {
        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = Yii::$app->mailer;
        $mailer->viewPath = $this->viewPath;
        $mailer->getView()->theme = Yii::$app->view->theme;

        if ($this->sender === null) {
            $this->sender = isset(Yii::$app->params['adminEmail']) ? Yii::$app->params['adminEmail'] : 'no-reply@example.com';
        }

        return $mailer->compose(['html' => $view, 'text' => 'text/' . $view], $params)
                        ->setTo($to)
                        ->setFrom($this->sender)
                        ->setSubject($subject)
                        ->send();
    }

}
