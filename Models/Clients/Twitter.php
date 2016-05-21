<?php

namespace jarrus90\User\Models\Clients;

use yii\authclient\clients\Twitter as BaseTwitter;
use yii\helpers\ArrayHelper;

class Twitter extends BaseTwitter implements ClientInterface {

    /**
     * @return string
     */
    public function getUsername() {
        return ArrayHelper::getValue($this->getUserAttributes(), 'screen_name');
    }

    /**
     * @return null Twitter does not provide user's email address
     */
    public function getEmail() {
        return null;
    }

}
