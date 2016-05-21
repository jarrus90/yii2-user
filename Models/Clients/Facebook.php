<?php

namespace jarrus90\User\Models\Clients;

use yii\authclient\clients\Facebook as BaseFacebook;

class Facebook extends BaseFacebook implements ClientInterface {

    /** @inheritdoc */
    public function getEmail() {
        return isset($this->getUserAttributes()['email']) ? $this->getUserAttributes()['email'] : null;
    }

    /** @inheritdoc */
    public function getUsername() {
        return;
    }

}
