<?php

namespace jarrus90\User\Models;

use Yii;
use yii\authclient\ClientInterface as BaseClientInterface;
use yii\db\ActiveRecord;
use jarrus90\User\Models\User;

/**
 * @property integer $id          Id
 * @property integer $user_id     User id, null if account is not bind to user
 * @property string  $provider    Name of service
 * @property string  $client_id   Account id
 * @property string  $data        Account properties returned by social network (json encoded)
 * @property string  $decodedData Json-decoded properties
 * @property string  $code
 * @property integer $created_at
 * @property string  $email
 * @property string  $username
 *
 * @property User    $user        User that this account is connected for.
 *
 */
class Account extends ActiveRecord {

    /**
     * Table name
     * @return string
     */
    public static function tableName() {
        return '{{%user_social_account}}';
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Tries to find an account and then connect that account with current user.
     *
     * @param BaseClientInterface $client
     */
    public static function connectWithUser(BaseClientInterface $client) {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'Something went wrong'));

            return;
        }

        $account = static::fetchAccount($client);

        if ($account->user === null) {
            $account->link('user', Yii::$app->user->identity);
            Yii::$app->session->setFlash('success', Yii::t('user', 'Your account has been connected'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'This account has already been connected to another user'));
        }
    }

    /**
     * Tries to find account, otherwise creates new account.
     *
     * @param BaseClientInterface $client
     *
     * @return Account
     * @throws \yii\base\InvalidConfigException
     */
    static function fetchAccount(BaseClientInterface $client) {
        $account = self::findOne([
                    'provider' => $client->getId(),
                    'client_id' => $client->getUserAttributes()['id'],
        ]);

        if (null === $account) {
            $account = Yii::createObject([
                        'class' => static::className(),
                        'provider' => $client->getId(),
                        'client_id' => $client->getUserAttributes()['id'],
                        'data' => json_encode($client->getUserAttributes()),
            ]);
            $account->save(false);
        }

        return $account;
    }

}
