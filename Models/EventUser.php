<?php

namespace jarrus90\User\Events;

use yii\base\Event;

/**
 * Class EventUser
 * @package jarrus90\User\Events
 */
class EventUser extends Event {

    /**
     * const
     */
    const EVENT_NEW_USER = 'new-user';


    /**
     * @var
     */
    public $user;

}
